<?php

namespace App\Services\Shop\Payments\Processors;

use App\Contracts\Shop\Services\Orders\OrderServiceContract;
use App\Contracts\Shop\Services\Payments\PaymentCallbackContract;
use App\Contracts\Shop\Services\Payments\PaymentProcessorContract;
use App\Contracts\Shop\Services\Payments\PaymentServiceContract;
use App\Enum\OrderStatus;
use App\Events\ChangedOrderPaymentEvent;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\OrderTransactions;
use App\Services\Shop\Orders\OrderService;
use App\Services\Shop\Payments\PaymentService;
use Carbon\Carbon;
use YooKassa\Client;
use YooKassa\Common\Exceptions\ApiConnectionException;
use YooKassa\Common\Exceptions\ApiException;
use YooKassa\Common\Exceptions\AuthorizeException;
use YooKassa\Common\Exceptions\BadApiRequestException;
use YooKassa\Common\Exceptions\ExtensionNotFoundException;
use YooKassa\Common\Exceptions\ForbiddenException;
use YooKassa\Common\Exceptions\InternalServerError;
use YooKassa\Common\Exceptions\NotFoundException;
use YooKassa\Common\Exceptions\ResponseProcessingException;
use YooKassa\Common\Exceptions\TooManyRequestsException;
use YooKassa\Common\Exceptions\UnauthorizedException;
use YooKassa\Model\Notification\NotificationEventType;
use YooKassa\Request\Payments\CreatePaymentResponse;

class YookassaService implements PaymentProcessorContract, PaymentCallbackContract
{
    private Client $yookassa_client;
    private string $processor;
    private int $payment_id;

    public function __construct(
        private readonly PaymentServiceContract $paymentService,
        private readonly OrderServiceContract $orderService,
    ) {
        $this->yookassa_client = (new Client())->setAuth(
            config('payments.yookassa.shop_id'),
            config('payments.yookassa.secret_key'),
        );
        $this->processor = config('payments.yookassa.db.processor');
        $this->payment_id = $this->paymentService->getPaymentIdByProcessor($this->processor);
    }

    /**
     * Get payment URL
     *
     * @param int $order_id Order ID
     *
     * @return string Url to redirect
     */
    public function getPaymentUrl(int $order_id): string
    {
        $payment = $this->createPayment($order_id);

        return $payment->getConfirmation()->getConfirmationUrl();
    }

    /**
     * Callback handle.
     *
     * @param string $source Callback source
     */
    public function callback(string $source)
    {
        try {
            if (empty($source)) {
                throw new PaymentException('Source in callback not found.');
            }

            $data = json_decode($source, true);

            $factory = new \YooKassa\Model\Notification\NotificationFactory();
            $notificationObject = $factory->factory($data);
            $responseObject = $notificationObject->getObject();

            if (in_array($notificationObject->getEvent() ?? '', [
                NotificationEventType::PAYMENT_SUCCEEDED,
                NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE,
                NotificationEventType::PAYMENT_CANCELED
            ])) {
                $someData = [
                    'paymentId' => $responseObject->getId(),
                    'paymentStatus' => $responseObject->getStatus(),
                ];
            } else {
                header('HTTP/1.1 400 Something went wrong');
                exit();
            }

            if ($paymentInfo = $this->yookassa_client->getPaymentInfo($someData['paymentId'])) {
                if (
                    $paymentInfo->getStatus() === 'succeeded'
                    && $paymentInfo->paid === true
                ) {
                    $order_to_status = OrderStatus::PAID->value;
                } elseif ($paymentInfo->getStatus() === 'canceled') {
                    $order_to_status = OrderStatus::FAILED->value;
                }

                if (
                    !empty($order_to_status)
                    && !empty($order_id = $paymentInfo->metadata['order_id'])
                ) {
                    $this->orderService->updateOrderStatus($order_id, $order_to_status);

                    /** Handle the event: Order payment changed. */
                    ChangedOrderPaymentEvent::dispatch(
                        Order::where('id', $order_id)->firstOrFail(),
                        [
                            'session_id' => $someData['paymentId'],
                            'status'     => $order_to_status,
                            'updated_at' => Carbon::now()->toDateTimeString(),
                        ]
                    );

                    return redirect()->route('order.show', ['order_id' => $order_id]);
                }
            } else {
                header('HTTP/1.1 400 Something went wrong');
                exit();
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 400 Something went wrong');
            exit();
        }

        return response('OK', 200);
    }

    /**
     * Create payment
     *
     * @param int $order_id Order ID
     */
    private function createPayment(int $order_id): CreatePaymentResponse
    {
        if (empty($order_id)) {
            throw new PaymentException('Заказ отсутствует');
        }

        $order = Order::with('order_products')->findOrFail($order_id);
        $order_amount = $order->result_price;

        $idempotenceKey = uniqid('', true);
        $payment = $this->yookassa_client->createPayment(
            [
                'amount' => [
                    'value' => $order_amount,
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'locale' => 'ru_RU',
                    'return_url' => route('order.show', ['order_id' => $order_id]),
                ],
                'capture' => true,
                'description' => 'Заказ №' . $order_id,
                'metadata' => [
                    'order_id' => $order_id,
                ],
            ],
            $idempotenceKey
        );

        if (!$payment instanceof CreatePaymentResponse) {
            $this->orderService->updateOrderStatus($order_id, OrderStatus::FAILED->value);

            /** Handle the event: Order payment changed. */
            ChangedOrderPaymentEvent::dispatch($order, [
                'session_id' => $payment->id,
                'status'     => 'payment_failed',
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            throw new PaymentException('YooKassa payment not created.');
        }

        OrderTransactions::create([
            'order_id'        => $order_id,
            'payment_id'      => $this->payment_id,
            'transaction_key' => $payment->id,
            'amount'          => $order_amount,
        ]);

        /** Handle the event: Order payment changed. */
        ChangedOrderPaymentEvent::dispatch($order, [
            'session_id' => $payment->id,
            'status'     => 'created',
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        return $payment;
    }
}
