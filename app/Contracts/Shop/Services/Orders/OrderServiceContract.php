<?php

namespace App\Contracts\Shop\Services\Orders;

use App\DTO\OrderCreateInputDTO;
use App\Models\Order;

interface OrderServiceContract
{
    /**
     * Create order.
     *
     * @var OrderCreateInputDTO $order_data Order data
     *
     * @return Order|bool
     */
    public function createOrder(OrderCreateInputDTO $order_data): Order|bool;

    /**
    * Update order status
    *
    * @param int    $order_id Order ID
    * @param string $status   Order status
    *
    * @return void
    */
    public function updateOrderStatus(int $order_id, string $status): void;
}
