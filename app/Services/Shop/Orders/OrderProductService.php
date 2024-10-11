<?php

namespace App\Services\Shop\Orders;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Contracts\Shop\Services\Orders\OrderProductServiceContract;
use App\Exceptions\CartException;
use App\Exceptions\OrderException;
use App\Models\OrderProducts;
use App\Services\Shop\Cart\DarryldecodeCartService;

class OrderProductService implements OrderProductServiceContract
{
    public function __construct(
        /** @var DarryldecodeCartService $cart_service */
        private readonly CartServiceContract $cart_service,
    ) {}

    /**
     * Save order products data for creating order
     *
     * @var int $order_id Order ID
     *
     * @return void
     *
     * @throws OrderException|CartException
     */
    public function saveOrderProducts(int $order_id): void
    {
        if (empty($order_id)) {
            throw new OrderException('Заказ не создан!');
        }

        $products_list = $this->prepareOrderProductsData();

        foreach ($products_list as $product_id => $product_data) {
            $order_products = new OrderProducts();

            $order_products->order_id = $order_id;
            $order_products->product_id = $product_id;
            $order_products->quantity = $product_data['quantity'];
            $order_products->original_item_price = $product_data['original_item_price'];
            $order_products->result_item_price = $product_data['result_item_price'];
            $order_products->original_subtotal_price = $product_data['original_subtotal_price'];
            $order_products->result_subtotal_price = $product_data['result_subtotal_price'];
            $order_products->data = $product_data;

            $order_products->save();
        }
    }

    /**
     * Prepare product and price data
     *
     * @return array
     *
     * @throws CartException
     */
    private function prepareOrderProductsData(): array
    {
        $product_data = [];

        $cart_collection = $this->cart_service->getSortedCartContent();
        $cart_price = $this->cart_service->getPriceCartContent($cart_collection);

        foreach ($cart_collection as $product_id => $product) {
            $product_data[$product_id] = [
                'name' => $product->name,
                'quantity' => $product->quantity,
                'original_item_price' => $cart_price['prices_without_conditions'][$product->id],
                'result_item_price' => $cart_price['prices'][$product->id],
                'original_subtotal_price' => $cart_price['subtotals_without_conditions'][$product->id],
                'result_subtotal_price' => $cart_price['subtotals'][$product->id],
            ];

            if ($product->attributes->has('variation')) {
                foreach($product->attributes->variation as $feature_name => $feature_value_name) {
                    $product_data[$product_id]['variations'][$feature_name] = $feature_value_name;
                }
            }
        }

        return $product_data;
    }
}
