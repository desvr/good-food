<?php

namespace App\Listeners;

use App\Contracts\Shop\Services\Orders\OrderDataServiceContract;
use App\Enum\OrderDataType;
use App\Events\ChangedOrderStatusEvent;
use App\Events\CreatedOrderEvent;
use App\Services\Shop\Orders\OrderDataService;
use Illuminate\Support\Carbon;

class OrderDataStatusChangedListener
{
    public int $tries = 5;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        /** @var OrderDataService $orderDataService */
        protected OrderDataServiceContract $orderDataService
    ) {}

    /**
     * Handle the event: Updates history "data" in "order_data" table when the order status changes.
     *
     * @param ChangedOrderStatusEvent|CreatedOrderEvent $event
     *
     * @return void
     */
    public function handle(ChangedOrderStatusEvent|CreatedOrderEvent $event)
    {
        $data = !empty($event->data)
            ? $event->data
            : [
                'order_status' => $event->order->status,
                'updated_at'   => Carbon::now()->format('d.m.Y H:i:s'),
            ];

        $this->orderDataService->updateOrderData($event->order->id, $data, OrderDataType::HISTORY);
    }

    /**
     * Handle the failed handle.
     *
     * @param \App\Events\ChangedOrderStatusEvent $event
     * @param \Throwable                          $exception
     *
     * @return void
     */
    public function failed(ChangedOrderStatusEvent $event, $exception)
    {
    }
}
