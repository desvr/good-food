<?php

namespace App\Services\Shop\Orders;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Contracts\Shop\Services\Orders\OrderPrepareDataServiceContract;
use App\Contracts\Shop\Services\Orders\OrderProductServiceContract;
use App\Contracts\Shop\Services\Orders\OrderServiceContract;
use App\DTO\OrderCreateInputDTO;
use App\Enum\OrderStatus;
use App\Events\ChangedOrderStatusEvent;
use App\Exceptions\OrderException;
use App\Models\Order;
use DB;
use Illuminate\Support\Carbon;

class OrderService implements OrderServiceContract
{
    public function __construct(
        private readonly CartServiceContract $cartService,
        private readonly OrderProductServiceContract $orderProductService,
        private readonly OrderPrepareDataServiceContract $orderPrepareDataService,
    ) {}

    /**
     * Create order
     *
     * @var OrderCreateInputDTO $order_data Order data
     *
     * @return Order|bool
     *
     * @throws OrderException|\Throwable
     */
    public function createOrder(OrderCreateInputDTO $order_data): Order|bool
    {
        if ($this->cartService->checkCartIsEmpty()) {
            throw new OrderException('Корзина пуста');
        }

        $order_data = $this->orderPrepareDataService->prepareOrderDataset($order_data);

        try {
            $created_order = DB::transaction(function () use ($order_data) {
                $created_order = Order::create($order_data);

                $this->orderProductService->saveOrderProducts($created_order->id ?? 0);

                $this->cartService->clearCart();
                $this->cartService->clearPromotions();

                return $created_order;
            }, 2);
        } catch (\Exception $e) {
            throw new OrderException('Возникла ошибка при формировании заказа.');
        }

        return $created_order;
    }

    /**
     * Update order status
     *
     * @param int    $order_id Order ID
     * @param string $status   Order status
     *
     * @return void
     */
    public function updateOrderStatus(int $order_id, string $status): void
    {
        $order = Order::query()
            ->where('id', $order_id)
            ->where('status', OrderStatus::OPEN->value)
            ->first();

        if (!empty($order)) {
            $order->status = $status;
            $order->save();

            /** Handle the event: Order status changed. */
            ChangedOrderStatusEvent::dispatch($order, [
                'order_status' => $order->status,
                'updated_at'   => Carbon::now()->format('d.m.Y H:i:s'),
            ]);
        }
    }
}
