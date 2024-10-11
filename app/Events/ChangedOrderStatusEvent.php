<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangedOrderStatusEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @var Order $order Order
     * @var array $data Order Data
     *
     * @return void
     */
    public function __construct(
        public Order $order,
        public array $data,
    ) {}
}
