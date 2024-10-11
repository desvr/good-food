<?php

namespace App\Listeners;

use App\Contracts\Shop\Services\Orders\OrderDataServiceContract;
use App\Enum\OrderDataType;
use App\Events\ChangedOrderPaymentEvent;
use App\Services\Shop\Orders\OrderDataService;

class OrderDataPaymentChangedListener
{
    public int $tries = 3;

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
     * Handle the event: Updates payments "data" in "order_data" table when the order payment status changes.
     *
     * @param ChangedOrderPaymentEvent $event
     *
     * @return void
     */
    public function handle(ChangedOrderPaymentEvent $event)
    {
        $this->orderDataService->updateOrderData($event->order->id, $event->data, OrderDataType::PAYMENT);
    }

    /**
     * Handle the failed handle.
     *
     * @param \App\Events\ChangedOrderPaymentEvent $event
     * @param \Throwable                          $exception
     *
     * @return void
     */
    public function failed(ChangedOrderPaymentEvent $event, $exception)
    {
    }
}
