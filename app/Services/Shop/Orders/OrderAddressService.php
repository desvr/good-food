<?php

namespace App\Services\Shop\Orders;

use App\Contracts\Shop\Services\Orders\OrderAddressServiceContract;
use App\Enum\ShippingType;

class OrderAddressService implements OrderAddressServiceContract
{
    /**
     * Prepare delivery address data
     *
     * @var array $order_data Order data
     *
     * @return string
     */
    public function prepareDeliveryAreaData(array $order_data): string
    {
        if (
            empty($order_data['shipping_type'])
            || $order_data['shipping_type'] !== ShippingType::DELIVERY->value
        ) {
            return '';
        }

        return $order_data['shipping_type_' . $order_data['shipping_type']];
    }

    /**
     * Prepare delivery address data
     *
     * @var array $order_data Order data
     *
     * @return string
     */
    public function prepareDeliveryAddressData(array $order_data): string
    {
        if (
            empty($order_data['shipping_type'])
            || $order_data['shipping_type'] === ShippingType::DELIVERY->value
            && empty($order_data['delivery_address'])
        ) {
            return '';
        }

        if ($order_data['shipping_type'] === ShippingType::PICKUP->value) {
            return $order_data['shipping_type_' . $order_data['shipping_type']];
        }

        if ($order_data['shipping_type'] === ShippingType::DELIVERY->value) {
            $street = !empty($order_data['delivery_address']['street']) ? $order_data['delivery_address']['street'] : '';
            $house = !empty($order_data['delivery_address']['house']) ? 'д. ' . $order_data['delivery_address']['house'] : '';
            $porch = !empty($order_data['delivery_address']['porch']) ? 'подъезд ' . $order_data['delivery_address']['porch'] : '';
            $floor = !empty($order_data['delivery_address']['floor']) ? 'этаж ' . $order_data['delivery_address']['floor'] : '';
            $flat = !empty($order_data['delivery_address']['flat']) ? 'кв. ' . $order_data['delivery_address']['flat'] : '';

            $address = implode(', ', [$street, $house, $porch, $floor, $flat]);

            if (empty(str_replace([',', ' '], '', $address))) {
                return '';
            }

            return $address;
        }

        return '';
    }
}
