<?php

namespace App\Contracts\Shop\Services\Products;

use App\Models\Product;

interface ProductPrepareDataServiceContract
{
    /**
     * Prepare data for creating product
     *
     * @param array $product_data Product data
     *
     * @return array<array, array>
     */
    public function prepareCreateProductData(array $product_data): array;

    /**
     * Prepare data for updating product
     *
     * @param Product $product      Product model
     * @param array   $product_data Product data
     *
     * @return array<array, array>
     */
    public function prepareUpdateProductData(Product $product, array $product_data): array;
}
