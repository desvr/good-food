<?php

namespace App\Listeners;

use App\Events\CreatedOrderEvent;
use App\Notifications\Orders\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class OrderCreatedListener
{
    public int $tries = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param CreatedOrderEvent $event Event
     *
     * @return void
     */
    public function handle(CreatedOrderEvent $event)
    {
        $order_id = $event->order->id;
        $order_price = $event->order->result_price;

        $telegram_user_id = Auth::user() ? Auth::user()->telegram_user_id : null;
        if (empty($telegram_user_id)) {
            return;
        }

        Notification::route('telegram', $telegram_user_id)
            ->notify(new OrderCreatedNotification($order_id, $order_price));
    }
}
