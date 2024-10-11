<?php

namespace App\Contracts\Shop\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface ProductRepositoryContract
{
    /**
     * Get product data with relations for product card on storefront
     *
     * @param int $product_id Product ID
     *
     * @return Product
     * @throws ModelNotFoundException
     */
    public function getProductCardData(int $product_id): Product;

    /**
     * Get addition product data for selected variation
     *
     * @param int $product_id Variation product ID
     *
     * @return array
     */
    public function getAdditionVariationProductData(int $product_id): array;
}
