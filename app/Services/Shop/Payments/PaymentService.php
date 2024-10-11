<?php

namespace App\Services\Shop\Payments;

use App\Contracts\Shop\Services\Payments\PaymentServiceContract;
use App\Exceptions\PaymentException;
use App\Models\Payment;

class PaymentService implements PaymentServiceContract
{
    /**
     * Get payment URL for redirect
     *
     * @param string $payment_method Payment method
     * @param int    $order_id       Order ID
     *
     * @return string
     *
     * @throws PaymentException
     */
    public function getPaymentUrl(string $payment_method, int $order_id): string
    {
        $paymentManager = new PaymentManager($payment_method);

        return $paymentManager->getPaymentUrl($order_id);
    }

    /**
     * Get Payment ID from DB by processor name
     *
     * @param string $processor Processor name
     *
     * @return int
     */
    public function getPaymentIdByProcessor(string $processor): int
    {
        return (int) Payment::select('id')->where('processor', $processor)->firstOrFail()->id;
    }
}
