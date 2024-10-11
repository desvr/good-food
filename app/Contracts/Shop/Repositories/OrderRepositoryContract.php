<?php

namespace App\Contracts\Shop\Repositories;

use App\Models\Order;

interface OrderRepositoryContract
{
    /**
     * Get array order data
     *
     * @var int $order_id Order ID
     *
     * @return array
     */
    public function getOrderData(int $order_id): array;

    /**
     * Get order model
     *
     * @var int $order_id Order ID
     *
     * @return Order|bool
     */
    public function getOrder(int $order_id): Order|bool;

    /**
     * Get array order data by user ID
     *
     * @var int $user_id User ID
     *
     * @return array
     */
    public function getOrderDataByUser(int $user_id): array;
}
