<?php

namespace App\Services\Shop\Orders;

use App\Contracts\Shop\Services\Orders\OrderDataServiceContract;
use App\Enum\OrderDataType;
use App\Models\OrderData;

class OrderDataService implements OrderDataServiceContract
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
    public function getOrderDataList(string $key_by = 'type', array $order_ids = [], array $types = []): array
    {
        $model = OrderData::class;

        if (!empty($order_ids)) {
            $model = $model::whereIn('order_id', $order_ids);

            if (!empty($types)) {
                $model = $model->whereIn('type', $types);
            }

            $order_data = $model->get();
        }

        if (!empty($key_by)) {
            $order_data = $order_data->keyBy('type');
        }

        $order_data = $order_data->toArray();
        if (empty($order_data)) {
            return [];
        }

        // Reverse history items for correct display on time->value
        if (!empty($order_data[OrderDataType::HISTORY->value]['data'])) {
            $this->reverseArrayData($order_data[OrderDataType::HISTORY->value]['data']);
        }

        if (count($types) === 1) {
            return reset($order_data);
        }

        return $order_data;
    }

    /**
     * Update order data
     *
     * @param int           $order_id Order ID
     * @param array         $data     Order data
     * @param OrderDataType $type     Order data type
     *
     * @return void
     *
     * @throws \Exception
     */
    public function updateOrderData(int $order_id, array $data, OrderDataType $type): void
    {
        $order_data_type = $type->value;
        $order_data_list = $this->getOrderDataList('type', [$order_id], [$order_data_type]);
        $addition_order_data = [];

        if (!empty($order_data_list['data'])) {
            $addition_order_data = $order_data_list['data'];
        }
        $addition_order_data[] = $data;

        OrderData::updateOrCreate(
            [
                'order_id' => $order_id,
                'type'     => $order_data_type,
            ],
            [
                'data' => $addition_order_data,
            ]
        );
    }

    /**
     * Reverse input array data by reference
     *
     * @param array $data Array data
     *
     * @return void
     */
    private function reverseArrayData(array &$data): void
    {
        $data = array_reverse($data);
    }
}
