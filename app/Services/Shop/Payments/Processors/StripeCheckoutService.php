<?php

namespace App\Services\Shop\Payments\Processors;

use App\Contracts\Shop\Services\Orders\OrderServiceContract;
use App\Contracts\Shop\Services\Payments\PaymentProcessorContract;
use App\Contracts\Shop\Services\Payments\PaymentServiceContract;
use App\Contracts\Shop\Services\Payments\PaymentWebhookContract;
use App\Enum\OrderStatus;
use App\Events\ChangedOrderPaymentEvent;
use App\Exceptions\PaymentException;
use App\Models\Order;
use App\Models\OrderTransactions;
use App\Models\Payment;
use Carbon\Carbon;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeCheckoutService implements PaymentProcessorContract, PaymentWebhookContract
{
    private StripeClient $stripe_client;
    private string $processor;
    private int $payment_id;

    public function __construct(
        private readonly PaymentServiceContract $paymentService,
        private readonly OrderServiceContract $orderService,
    ) {
        $this->stripe_client = new StripeClient(config('payments.stripe_checkout.secret_key'));
        $this->processor = config('payments.stripe_checkout.db.processor');
        $this->payment_id = $this->paymentService->getPaymentIdByProcessor($this->processor);
    }

    /**
     * Get payment URL
     *
     * @param int $order_id Order ID
     *
     * @return string Url to redirect
     * @throws ApiErrorException
     */
    public function getPaymentUrl(int $order_id): string
    {
        $checkout_session = $this->checkoutSessionCreate($order_id);

        return $checkout_session->url;
    }

    /**
     * Success event handle
     *
     * @param string $session_id Session ID
     *
     * @return array
     *
     * @throws PaymentException
     */
    public function success(string $session_id): array
    {
        try {
            $session = $this->stripe_client->checkout->sessions->retrieve($session_id);

            $order_transactions_order_id = OrderTransactions::query()
                ->where('payment_id', $this->payment_id)
                ->where('transaction_key', $session->id)
                ->orderByDesc('id')
                ->value('order_id');
            if (empty($order_transactions_order_id)) {
                throw new PaymentException('Order transaction не найден.');
            }

            $this->orderService->updateOrderStatus($order_transactions_order_id, OrderStatus::PAID->value);

            /** Handle the event: Order payment changed. */
            ChangedOrderPaymentEvent::dispatch(
                Order::where('id', $order_transactions_order_id)->firstOrFail(),
                [
                    'session_id' => $session->id,
                    'status'     => 'success',
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]
            );

            return ['order.show', ['order_id' => $order_transactions_order_id]];
        } catch (\Exception $e) {
            throw new PaymentException('Возникла ошибка в success сценарии.');
        }
    }

    /**
     * Cancel event handle
     *
     * @param string $session_id Session ID
     *
     * @return array
     *
     * @throws PaymentException
     */
    public function cancel(string $session_id): array
    {
        try {
            $session = $this->stripe_client->checkout->sessions->retrieve($session_id);

            $order_transactions_order_id = OrderTransactions::query()
                ->where('payment_id', $this->payment_id)
                ->where('transaction_key', $session->id)
                ->orderByDesc('id')
                ->value('order_id');
            if (empty($order_transactions_order_id)) {
                throw new PaymentException('Order transaction не найден.');
            }

            $this->orderService->updateOrderStatus($order_transactions_order_id, OrderStatus::FAILED->value);

            /** Handle the event: Order payment changed. */
            ChangedOrderPaymentEvent::dispatch(
                Order::where('id', $order_transactions_order_id)->firstOrFail(),
                [
                    'session_id' => $session->id,
                    'status'     => 'cancel',
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]
            );

            return ['order.show', ['order_id' => $order_transactions_order_id]];
        } catch (\Exception $e) {
            throw new PaymentException('Возникла ошибка в cancel сценарии.');
        }
    }

    /**
     * Stripe Webhooks install
     *
     * @return void
     *
     * @throws ApiErrorException
     */
    public function webhookInstall(): void
    {
        $payment = Payment::query()
            ->where('processor', $this->processor)
            ->where('id', $this->payment_id)
            ->first();
        if (!empty($payment->webhook_key)) {
            $this->stripe_client->webhookEndpoints->update($payment->webhook_key, ['disabled' => true]);
            $payment->webhook_key = null;
            $payment->save();
        }

        $webhook_endpoints = $this->stripe_client->webhookEndpoints->create([
            'enabled_events' => [
                'checkout.session.completed',
                'checkout.session.async_payment_succeeded',
                'checkout.session.async_payment_failed',
            ],
            'metadata' => [
                'payment_id' => $this->payment_id,
                'processor' => $this->processor,
            ],
            'url' => rtrim(config('app.url'), '/') . '/stripe/webhookHandle',
        ]);

        if (!empty($webhook_endpoints->id)) {
            $payment->webhook_key = $webhook_endpoints->id;
            $payment->save();
        }
    }

    /**
     * Webhook handle.
     *
     * @param string $payload    Webhook payload
     * @param string $sig_header Sig header
     *
     * @return array
     *
     * @throws PaymentException
     */
    public function webhookHandle(string $payload, string $sig_header): array
    {
        try {
            $event = Webhook::constructEvent($payload, $sig_header, config('payments.stripe_checkout.webhook_secret'));
        } catch(\UnexpectedValueException $e) {
            return ['', 400];
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return ['', 400];
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $order_transactions_order_id = OrderTransactions::query()
                    ->where('payment_id', $this->payment_id)
                    ->where('transaction_key', $session->id)
                    ->value('order_id');
                if (empty($order_transactions_order_id)) {
                    throw new PaymentException('Order transaction не найден.');
                }

                $order_id = $order_transactions_order_id;
                $this->orderService->updateOrderStatus($order_id, OrderStatus::PAID->value);

                /** Handle the event: Order payment changed. */
                ChangedOrderPaymentEvent::dispatch(
                    Order::where('id', $order_id)->firstOrFail(),
                    [
                        'session_id' => $session->id,
                        'status'     => 'completed',
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]
                );
            case 'checkout.session.async_payment_succeeded':
                $session = $event->data->object;

                $order_transactions_order_id = OrderTransactions::query()
                    ->where('payment_id', $this->payment_id)
                    ->where('transaction_key', $session->id)
                    ->value('order_id');
                if (empty($order_transactions_order_id)) {
                    throw new PaymentException('Order transaction не найден.');
                }

                $order_id = $order_transactions_order_id;
                $this->orderService->updateOrderStatus($order_id, OrderStatus::PAID->value);

                /** Handle the event: Order payment changed. */
                ChangedOrderPaymentEvent::dispatch(
                    Order::where('id', $order_id)->firstOrFail(),
                    [
                        'session_id' => $session->id,
                        'status'     => 'async_succeeded',
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]
                );
            case 'checkout.session.async_payment_failed':
                $session = $event->data->object;

                $order_transactions_order_id = OrderTransactions::query()
                    ->where('payment_id', $this->payment_id)
                    ->where('transaction_key', $session->id)
                    ->value('order_id');
                if (empty($order_transactions_order_id)) {
                    throw new PaymentException('Order transaction не найден.');
                }

                $order_id = $order_transactions_order_id;
                $this->orderService->updateOrderStatus($order_id, OrderStatus::FAILED->value);

                /** Handle the event: Order payment changed. */
                ChangedOrderPaymentEvent::dispatch(
                    Order::where('id', $order_id)->firstOrFail(),
                    [
                        'session_id' => $session->id,
                        'status'     => 'async_failed',
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]
                );
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return ['', 200];
    }

    /**
     * Create checkout session
     *
     * @param int $order_id Order ID
     *
     * @return Session
     *
     * @throws ApiErrorException|PaymentException
     */
    private function checkoutSessionCreate(int $order_id): Session
    {
        if (empty($order_id)) {
            throw new PaymentException('Заказ отсутствует');
        }

        $order = Order::with('order_products')->findOrFail($order_id);

        [$items, $order_amount] = $this->prepareCheckoutLineItems($order);
        if (empty($items)) {
            throw new PaymentException('Товары для оплаты в Stripe Checkout отсутствуют');
        }

        $checkout_session = $this->stripe_client->checkout->sessions->create([
            'line_items' => $items,
            'metadata' => [
                'order_id' => $order_id,
            ],
            'locale' => 'ru',
            'mode' => 'payment',
            'success_url' => route('payments.stripe_checkout.success') . "?session_id={CHECKOUT_SESSION_ID}",
            'cancel_url' => route('payments.stripe_checkout.cancel') . "?session_id={CHECKOUT_SESSION_ID}",
        ]);

        if (empty($checkout_session->id)) {
            $this->orderService->updateOrderStatus($order_id, OrderStatus::FAILED->value);

            /** Handle the event: Order payment changed. */
            ChangedOrderPaymentEvent::dispatch($order, [
                'session_id' => $checkout_session->id,
                'status'     => 'checkout_failed',
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            throw new PaymentException('Stripe Checkout session not created.');
        }

        OrderTransactions::create([
            'order_id'        => $order_id,
            'payment_id'      => $this->payment_id,
            'transaction_key' => $checkout_session->id,
            'amount'          => $order_amount
        ]);

        /** Handle the event: Order payment changed. */
        ChangedOrderPaymentEvent::dispatch($order, [
            'session_id' => $checkout_session->id,
            'status'     => 'checkout_created',
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        return $checkout_session;
    }

    private function prepareCheckoutLineItems(Order $order): array
    {
        $items = [];
        $order_amount = 0;

        foreach ($order->order_products as $product) {
            $product_name = $product['data']['name'];
            if (!empty($product['data']['variations'])) {
                $is_first_variation = true;
                $product_name .= ' (';
                foreach ($product['data']['variations'] as $variation => $value) {
                    if (!$is_first_variation) {
                        $product_name .= '; ';
                    }
                    $product_name .= $variation . ': ' . $value;
                }
                $product_name .= ')';
            }

            $items[] = [
                'price_data' => [
                    'currency' => 'rub',
                    'product_data' => [
                        'name' => $product_name,
                    ],
                    'unit_amount' => $product['result_item_price'] . '00',
                ],
                'quantity' => $product['quantity'],
            ];

            $order_amount += $product['result_item_price'];
        }

        return [$items, $order_amount];
    }
}
