<?php

namespace App\Repositories;

use App\Contracts\Shop\Repositories\OrderRepositoryContract;
use App\Exceptions\OrderException;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class OrderRepository implements OrderRepositoryContract
{
    /**
     * Get array order data
     *
     * @var int $order_id Order ID
     *
     * @return array
     *
     * @throws OrderException
     */
    public function getOrderData(int $order_id): array
    {
        if (empty($order_id)) {
            throw new OrderException('Номер заказа отсутствует.');
        }

        $order_data = Cache::tags(['orders'])->remember(
            \App\Enum\Cache\Repository\Order::CACHE_KEY_ORDER_DATA . $order_id,
            \App\Enum\Cache\Repository\Order::CACHE_TTL_ORDER_DATA,
            function() use ($order_id) {
                $order_data = [];
                $order = $this->getOrder($order_id);
                if (empty($order)) {
                    return $order_data;
                }

                $order_data = $order->toArray();
                $list_product_ids = array_column($order_data['order_products'], 'product_id');
                $detailed_product_data = Product::find([$list_product_ids]);
                foreach ($order_data['order_products'] as $key => $product_data) {
                    $product_id = $product_data['product_id'];
                    $detailed_current_product_data = $detailed_product_data->find($product_id)->toArray();
                    $order_data['order_products'][$key]['detailed_data'] = $detailed_current_product_data;
                }

                return $order_data;
            }
        );

        return $order_data;
    }

    /**
     * Get order model
     *
     * @var int $order_id Order ID
     *
     * @return Order|bool
     */
    public function getOrder(int $order_id): Order|bool
    {
        $order = Order::query()
            ->where('id', '=', $order_id)
            ->with('order_products')
            ->firstOrFail();

        return $order ?? false;
    }

    /**
     * Get array order data by user ID
     *
     * @var int $user_id User ID
     *
     * @return array
     *
     * @throws OrderException
     */
    public function getOrderDataByUser(int $user_id): array
    {
        if (empty($user_id)) {
            throw new OrderException('ID пользователя отсутствует.');
        }

        $orders = Order::select(['id', 'name', 'phone', 'shipping_type', 'result_price', 'status', 'receipt_code', 'created_at'])
            ->where('user_id', '=', $user_id)
            ->with('order_products')
            ->latest('created_at')
            ->get();

        if (empty($orders)) {
            return [];
        }

        $orders_data = Cache::tags(['orders'])->remember(
            \App\Enum\Cache\Repository\Order::CACHE_KEY_ORDER_DATA_BY_USER . $user_id,
            \App\Enum\Cache\Repository\Order::CACHE_TTL_ORDER_DATA_BY_USER,
            function() use ($orders) {
                $orders_data = [];
                foreach ($orders as $order_key => $order) {
                    $orders_data[] = $order->toArray();
                    $list_product_ids = array_column($orders_data[$order_key]['order_products'], 'product_id');
                    $detailed_product_data = Product::find([$list_product_ids]);
                    foreach ($orders_data[$order_key]['order_products'] as $product_key => $product_data) {
                        $product_id = $product_data['product_id'];
                        $detailed_current_product_data = $detailed_product_data->find($product_id)->toArray();
                        $orders_data[$order_key]['order_products'][$product_key]['detailed_data'] = $detailed_current_product_data;
                    }
                }

                return $orders_data;
            }
        );

        return $orders_data;
    }
}
