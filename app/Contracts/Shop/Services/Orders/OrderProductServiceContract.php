<?php

namespace App\Contracts\Shop\Services\Orders;

interface OrderProductServiceContract
{
    /**
     * Save order products data for creating order
     *
     * @var int $order_id Order ID
     *
     * @return void
     */
    public function saveOrderProducts(int $order_id): void;
}
