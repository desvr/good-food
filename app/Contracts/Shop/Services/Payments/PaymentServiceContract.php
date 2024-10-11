<?php

namespace App\Contracts\Shop\Services\Payments;

interface PaymentServiceContract
{
    /**
     * Get payment URL
     *
     * @param int $order_id Order ID
     *
     * @return string Url to redirect
     */
    public function getPaymentUrl(string $payment_method, int $order_id): string;
}
