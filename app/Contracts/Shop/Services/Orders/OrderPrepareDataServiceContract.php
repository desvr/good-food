<?php

namespace App\Contracts\Shop\Services\Orders;

use App\DTO\OrderCreateInputDTO;

interface OrderPrepareDataServiceContract
{
    /**
     * Prepare dataset for order
     *
     * @var OrderCreateInputDTO $order_data Order data
     *
     * @return array
     */
    public function prepareOrderDataset(OrderCreateInputDTO $order_data): array;
}
