<?php

namespace App\Contracts\Shop\Services\Cart;

use App\DTO\CartAddProductOutputDTO;
use Darryldecode\Cart\Cart;
use Darryldecode\Cart\CartCollection;

interface CartServiceContract
{
    /**
     * Get cart.
     *
     * @return Cart
     */
    public function getCart(): Cart;

    /**
     * Get total quantity cart.
     *
     * @return int
     */
    public function getTotalQuantityCart(): int;

    /**
     * Get product or variation quantity cart.
     *
     * @param int $product_id Product or variation ID
     *
     * @return int
     */
    public function getProductQuantityCart(int $product_id): int;

    /**
     * Get total cart content with individual product subtotal and price (With conditions applied).
     *
     * @param CartCollection $cart_collection Cart Collection
     * @param bool           $only_total      Flag return total only
     *
     * @return array
     */
    public function getPriceCartContent(CartCollection $cart_collection, bool $only_total = false): array;

    /**
     * Get cart content.
     *
     * @return CartCollection
     */
    public function getCartContent(): CartCollection;

    /**
     * Get sorted cart content.
     *
     * @return CartCollection
     */
    public function getSortedCartContent(): CartCollection;

    /**
     * Add product to the cart.
     *
     * @param CartAddProductOutputDTO $entity_data_dto Product-based entity data.
     *
     * @return float Cart total quantity
     */
    public function addProductToCart(CartAddProductOutputDTO $entity_data_dto): float;

    /**
     * Increase product in the cart.
     *
     * @param int $product_id Product ID.
     * @param int $quantity   Quantity.
     *
     * @return void
     */
    public function increaseProductInCart(int $product_id, int $quantity = 1): void;

    /**
     * Decrease product in the cart.
     *
     * @param int $product_id Product ID.
     * @param int $quantity   Quantity.
     *
     * @return void
     */
    public function decreaseProductInCart(int $product_id, int $quantity = -1): void;

    /**
     * Remove product from the cart.
     *
     * @param int $product_id Product ID.
     *
     * @return void
     */
    public function removeProductFromCart(int $product_id): void;

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clearCart(): void;

    /**
     * Check cart is empty.
     *
     * @param int $total_quantity Cart total quantity
     * @param int $total_price Cart total price
     *
     * @return bool
     */
    public function checkCartIsEmpty(int $total_quantity = 0, int $total_price = 0): bool;

    /**
     * Apply promotion to the cart.
     *
     * @param string $promotion_code Promotion code.
     *
     * @return void
     */
    public function applyPromotion(string $promotion_code): void;

    /**
     * Remove promotion from the cart.
     *
     * @param string $promotion_name Promotion name.
     *
     * @return void
     */
    public function removePromotion(string $promotion_name): void;

    /**
     * Clear all promotions from the cart.
     *
     * @return void
     */
    public function clearPromotions(): void;

    /**
     * Get applyed promotions value.
     *
     * @return array
     */
    public function getApplyedPromotions(): array;
}
