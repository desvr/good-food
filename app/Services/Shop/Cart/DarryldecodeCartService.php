<?php

namespace App\Services\Shop\Cart;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\DTO\CartAddProductOutputDTO;
use App\Exceptions\CartException;
use Darryldecode\Cart\Cart;
use Darryldecode\Cart\CartCollection;
use Darryldecode\Cart\CartCondition;
use Darryldecode\Cart\Exceptions\InvalidConditionException;
use Illuminate\Http\Request;

class DarryldecodeCartService extends BaseCartService implements CartServiceContract
{
    protected Cart|bool $cart = false;

    public function __construct(?Request $request = null)
    {
        if (!empty($request->cookie('cart_id'))) {
            $this->cart = \Cart::session($request->cookie('cart_id'));
        } elseif (empty($_COOKIE['cart_id'])) {
            $cart_cookie = cookie('cart_id', uniqid(), 1);
            $this->cart = \Cart::session($cart_cookie->getValue());
        } else {
            $this->cart = \Cart::session($_COOKIE['cart_id']);
        }
    }

    /**
     * Get cart.
     *
     * @return Cart
     *
     * @throws CartException
     */
    public function getCart(): Cart
    {
        if (!empty($this->cart)) {
            return $this->cart;
        }

        if (!isset($_COOKIE['cart_id'])) {
            cookie('cart_id', uniqid(), 3600 * 24 * 30);
        }

        if (empty($_COOKIE['cart_id'])) {
            throw new CartException('Отсутствует Cart ID в cookie.');
        }

        /** @var $cart Cart */
        $this->cart = $cart = \Cart::session($_COOKIE['cart_id']);

        return $cart;
    }

    /**
     * Get total quantity cart.
     *
     * @return int
     */
    public function getTotalQuantityCart(): int
    {
        $cart = $this->getCart();

        return $cart->getTotalQuantity();
    }

    /**
     * Get product or variation quantity cart.
     *
     * @param int $product_id Product or variation ID
     *
     * @return int
     */
    public function getProductQuantityCart(int $product_id): int
    {
        $cart_content = $this->getCartContent();

        if (empty($cart_content[$product_id])){
            return 0;
        }

        return $cart_content[$product_id]['quantity'];
    }

    /**
     * Get total cart content with individual product subtotal and price (With conditions applied).
     *
     * @param CartCollection $cart_collection Cart Collection
     * @param bool           $only_total      Flag return total only
     *
     * @return array
     */
    public function getPriceCartContent(CartCollection $cart_collection, bool $only_total = false): array
    {
        $result = [];

        if (empty($cart_collection)) {
            return [
                'total' => 0,
                'total_without_conditions' => 0,
            ];
        }

        $result['total'] = $this->getCart()->getSubTotal();
        $result['total_without_conditions'] = $this->getCart()->getSubTotalWithoutConditions();

        if ($only_total) {
            return $result;
        }

        foreach ($cart_collection as $product_id => $product) {
            $product_price = $product->getPriceWithConditions();
            $result['prices'][$product_id] = $product_price;
            $product_subtotal = $product->getPriceSumWithConditions();
            $result['subtotals'][$product_id] = $product_subtotal;

            $product_price = $product->price;
            $result['prices_without_conditions'][$product_id] = $product_price;
            $product_subtotal = $product->getPriceSum();
            $result['subtotals_without_conditions'][$product_id] = $product_subtotal;
        }

        return $result;
    }

    /**
     * Get cart content.
     *
     * @return CartCollection
     *
     * @throws CartException
     */
    public function getCartContent(): CartCollection
    {
        return $this->getCart()->getContent();
    }

    /**
     * Get sorted cart content.
     *
     * @return CartCollection
     *
     * @throws CartException
     */
    public function getSortedCartContent(): CartCollection
    {
        return $this->getCartContent()->sortBy(['name', 'id']);
    }

    /**
     * Add product to the cart.
     *
     * @param CartAddProductOutputDTO $entity_data_dto Product-based entity data.
     *
     * @return float
     *
     * @throws CartException
     */
    public function addProductToCart(CartAddProductOutputDTO $entity_data_dto): float
    {
        $entity_data = $entity_data_dto->toArray();

        if (empty($entity_data)) {
            throw new CartException('Отсутствуют данные для добавления товара в корзину');
        }

        $cart = $this->getCart();
        $cart->add($entity_data);

        $price_cart_content = $this->getPriceCartContent($cart->getContent(), true);

        return $price_cart_content['total'];
    }

    /**
     * Increase product in the cart.
     *
     * @param int $product_id Product ID.
     * @param int $quantity   Quantity.
     *
     * @return void
     *
     * @throws CartException
     */
    public function increaseProductInCart(int $product_id, int $quantity = 1): void
    {
        if (empty($product_id)) {
            throw new CartException('Передан некорректный product ID: ' . $product_id);
        }

        $this->getCart()->update($product_id, ['quantity' => $quantity]);
    }

    /**
     * Decrease product in the cart.
     *
     * @param int $product_id Product ID.
     * @param int $quantity   Quantity.
     *
     * @return void
     *
     * @throws CartException
     */
    public function decreaseProductInCart(int $product_id, int $quantity = -1): void
    {
        if (empty($product_id)) {
            throw new CartException('Передан некорректный product ID: ' . $product_id);
        }

        $this->getCart()->update($product_id, ['quantity' => $quantity]);
    }

    /**
     * Remove product from the cart.
     *
     * @param int $product_id Product ID.
     *
     * @return void
     *
     * @throws CartException
     */
    public function removeProductFromCart(int $product_id): void
    {
        if (empty($product_id)) {
            throw new CartException('Передан некорректный product ID: ' . $product_id);
        }

        $this->getCart()->remove($product_id);
    }

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clearCart(): void
    {
        $this->getCart()->clear();
    }

    /**
     * Check cart is empty.
     *
     * @param int $total_quantity Cart total quantity
     * @param int $total_price Cart total price
     *
     * @return bool
     */
    public function checkCartIsEmpty(int $total_quantity = 0, int $total_price = 0): bool
    {
        if (
            !empty($total_quantity)
            || !empty($total_price)
        ) {
            return false;
        }

        $cart_collection = $this->getCartContent();
        $cart_total_quantity = $this->getTotalQuantityCart();
        $cart_price = $this->getPriceCartContent($cart_collection);

        return $cart_total_quantity <= 0 || $cart_price['total'] <= 0;
    }

    /**
     * Apply promotion to the cart.
     *
     * @param string $promotion_code Promotion code.
     *
     * @return void
     *
     * @throws CartException|InvalidConditionException
     */
    public function applyPromotion(string $promotion_code): void
    {
        $promotion_code = mb_strtoupper($promotion_code);
        if (!in_array($promotion_code, ['ПРОМО500', 'ПРОМО1000'])) {
            return;
        }

        if ($promotion_code === 'ПРОМО500') {
            $condition = new CartCondition([
                'name' => 'ПРОМО500',
                'type' => 'coupon',
                'target' => 'subtotal',
                'value' => '-500',
            ]);
        }

        if ($promotion_code === 'ПРОМО1000') {
            $condition = new CartCondition([
                'name' => 'ПРОМО1000',
                'type' => 'coupon',
                'target' => 'subtotal',
                'value' => '-1000',
            ]);
        }

        $this->getCart()->condition($condition);
    }

    /**
     * Remove promotion from the cart.
     *
     * @param string $promotion_name Promotion name.
     *
     * @return void
     *
     * @throws CartException
     */
    public function removePromotion(string $promotion_name): void
    {
        if (empty($promotion_name)) {
            return;
        }

        $this->getCart()->removeCartCondition($promotion_name);
    }

    /**
     * Clear all promotions from the cart.
     *
     * @return void
     */
    public function clearPromotions(): void
    {
        $this->getCart()->clearCartConditions();
    }

    /**
     * Get applyed promotions value.
     *
     * @return array
     */
    public function getApplyedPromotions(): array
    {
        $cart_conditions = $this->getCart()->getConditions();

        $conditions = [
            'sum_value'  => 0,
            'promotions' => [],
        ];
        foreach($cart_conditions as $condition) {
            $conditions['sum_value'] += $condition->getValue();
            $conditions['promotions'][] = [
                'target' => $condition->getTarget(),
                'name' => $condition->getName(),
                'type' => $condition->getType(),
                'value' => $condition->getValue(),
                'order' => $condition->getOrder(),
                'attributes' => $condition->getAttributes(),
            ];
        }

        return $conditions;
    }
}
