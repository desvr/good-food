<?php

namespace App\Contracts\Shop\Services\Payments;

interface PaymentProcessorContract
{
    /**
     * Get payment URL
     *
     * @param int $order_id Order ID
     *
     * @return string Url to redirect
     */
    public function getPaymentUrl(int $order_id): string;
}
