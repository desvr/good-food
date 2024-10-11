<?php

namespace App\Contracts\Shop\Services\Payments;

use App\Models\Product;

interface PaymentCallbackContract
{
    /**
     * Callback handle.
     *
     * @param string $source Callback source
     */
    public function callback(string $source);
}
