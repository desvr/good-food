<?php

namespace App\Contracts\Shop\Services\Orders;

interface OrderAddressServiceContract
{
    /**
     * Prepare delivery address data
     *
     * @var array $order_data Order data
     *
     * @return string
     */
    public function prepareDeliveryAreaData(array $order_data): string;

    /**
     * Prepare delivery address data
     *
     * @var array $order_data Order data
     *
     * @return string
     */
    public function prepareDeliveryAddressData(array $order_data): string;
}
