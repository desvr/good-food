<?php

namespace App\Contracts\Shop\Services\Orders;

use App\Enum\OrderDataType;

interface OrderDataServiceContract
{
    /**
     * Get order data list.
     *
     * @param string $key_by    KeyBy mapping field.
     * @param array  $order_ids Order IDs.
     * @param array  $types     Order data type.
     *
     * @return array
     */
    public function getOrderDataList(string $key_by = '', array $order_ids = [], array $types = []): array;

    /**
     * Update order data
     *
     * @param int           $order_id Order ID
     * @param array         $data     Order data
     * @param OrderDataType $type     Order data type
     *
     * @return void
     */
    public function updateOrderData(int $order_id, array $data, OrderDataType $type): void;
}
