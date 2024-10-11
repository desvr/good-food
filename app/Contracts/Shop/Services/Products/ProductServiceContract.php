<?php

namespace App\Contracts\Shop\Services\Products;

interface ProductServiceContract
{
    /**
     * Create product.
     *
     * @param array $product_data Product data.
     *
     * @return bool
     */
    public function createProduct(array $product_data = []): bool;

    /**
     * Update product.
     *
     * @param int   $product_id   Product ID.
     * @param array $product_data Product data.
     *
     * @return bool
     */
    public function updateProduct(int $product_id, array $product_data = []): bool;

    /**
     * Delete product.
     *
     * @param int   $product_id   Product ID.
     *
     * @return bool
     */
    public function deleteProduct(int $product_id): bool;
}
