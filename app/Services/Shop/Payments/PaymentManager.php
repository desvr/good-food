<?php

namespace App\Services\Shop\Payments;

use App\Contracts\Shop\Services\Payments\PaymentProcessorContract;
use App\Exceptions\PaymentException;
use App\Models\Payment;

class PaymentManager
{
    private PaymentProcessorContract $paymentMethod;

    public function __construct(string $payment_method)
    {
        $this->paymentMethod = $this->getProcessorByPaymentMethod($payment_method);
    }

    /**
     * Set processor
     *
     * @param string $payment_method Payment method
     *
     * @throws PaymentException
     */
    public function setProcessor(string $payment_method)
    {
        $this->paymentMethod = $this->getProcessorByPaymentMethod($payment_method);
    }

    /**
     * Get payment URL
     *
     * @param int $order_id Order ID
     *
     * @return string Url to redirect
     *
     * @throws PaymentException
     */
    public function getPaymentUrl(int $order_id): string
    {
        if (empty($order_id)) {
            throw new PaymentException('Заказ отсутствует.');
        }

        return $this->paymentMethod->getPaymentUrl($order_id);
    }

    /**
     * Get payment processor class by payment method string
     *
     * @param string $payment_method Payment method
     *
     * @throws PaymentException
     */
    private function getProcessorByPaymentMethod(string $payment_method): PaymentProcessorContract
    {
        $payment_service_data = Payment::select('processor')->where('method', $payment_method)->firstOrFail();
        if (empty($payment_service_data['processor'])) {
            throw new PaymentException('Payment processor is not specified.');
        }
        $payment_service_path = __NAMESPACE__ . '\\Processors\\' . $payment_service_data['processor'];

        if (!class_exists($payment_service_path)) {
            throw new PaymentException($payment_service_path . ' Class Not Found.');
        }

        return app($payment_service_path);
    }
}
