<?php

namespace App\Repositories;

use App\Contracts\Shop\Repositories\ProductRepositoryContract;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository implements ProductRepositoryContract
{
    /**
     * Get product data with relations for product card on storefront
     *
     * @param int $product_id Product ID
     *
     * @return Product
     *
     * @throws ModelNotFoundException
     */
    public function getProductCardData(int $product_id): Product
    {
        $product = Product::findOrFail($product_id);

        return $product->load([
            'children.variations' => function (Builder $query) {
                $query->select(
                    'product_variations.product_id AS product_id',
                    'product_features.id AS product_feature_id',
                    'product_features.name AS product_feature_name',
                    'product_features.value AS product_feature_value',
                    'product_feature_values.id AS product_feature_value_id',
                    'product_feature_values.name AS product_feature_value_name',
                    'product_feature_values.value AS product_feature_value_value',
                )
                    ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                    ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                    ->orderBy('product_feature_value_value');
            },
            'variations' => function (Builder $query) {
                $query->select(
                    'product_variations.product_id AS product_id',
                    'product_features.id AS product_feature_id',
                    'product_features.name AS product_feature_name',
                    'product_features.value AS product_feature_value',
                    'product_feature_values.id AS product_feature_value_id',
                    'product_feature_values.name AS product_feature_value_name',
                    'product_feature_values.value AS product_feature_value_value',
                )
                    ->join('product_features', 'product_variations.feature_id', '=', 'product_features.id')
                    ->join('product_feature_values', 'product_variations.feature_value_id', '=', 'product_feature_values.id')
                    ->orderBy('product_feature_value_value');
            },
        ]);
    }

    /**
     * Get addition product data for selected variation
     *
     * @param int $product_id Variation product ID
     *
     * @return array
     */
    public function getAdditionVariationProductData(int $product_id): array
    {
        return Product::select(['description', 'weight', 'calories', 'price'])
            ->where('id', '=', $product_id)
            ->firstOrFail()
            ->toArray();
    }
}
