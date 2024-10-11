<?php

namespace App\Services\Shop\Cart;

use App\DTO\CartAddProductInputDTO;
use App\DTO\CartAddProductOutputDTO;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductFeatureValue;

abstract class BaseCartService
{
    /**
     * Converts product data to add to cart
     *
     * @param CartAddProductInputDTO $product_input_dto Product input DTO
     *
     * @return CartAddProductOutputDTO
     */
    public function convertsAddingProductFromVariation(CartAddProductInputDTO $product_input_dto): CartAddProductOutputDTO
    {
        $product_input = $product_input_dto->toArray();

        if (!empty($product_input['variation_data'])) {
            [$product_id, $feature_id, $feature_value_id] = explode('_', $product_input['variation_data']);
        } elseif (!empty($product_input['product_id'])) {
            $product_id = $product_input['product_id'];
        } else {
            $product_id = 0;
        }

        if (!empty($product_id)) {
            $product = Product::active()->findOrFail($product_id);
        }

        if (!empty($feature_id) && !empty($feature_value_id)) {
            $variation_data = [
                ProductFeature::getFeatureNameByID($feature_id) => ProductFeatureValue::getFeatureValueNameByID($feature_value_id)
            ];
        }

        $attributes = [];
        if (!empty($product->slug)) $attributes['slug'] = $product->slug;
        if (!empty($product->image)) $attributes['image'] = $product->image;
        if (!empty($variation_data)) $attributes['variation'] = $variation_data;

        return CartAddProductOutputDTO::from(
            $product,
            [
                'quantity' => $product_input['quantity'],
                'attributes' => $attributes
            ]
        );
    }
}
