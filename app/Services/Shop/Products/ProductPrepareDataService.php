<?php

namespace App\Services\Shop\Products;

use App\Contracts\Shop\Services\Products\ProductPrepareDataServiceContract;
use App\Enum\ProductType;
use App\Jobs\ImagesOptimizeJob;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductPrepareDataService implements ProductPrepareDataServiceContract
{
    /**
     * Prepare data for creating product
     *
     * @param array $product_data Product data
     *
     * @return array<array, array>
     */
    public function prepareCreateProductData(array $product_data): array
    {
        $product_data['parent_id'] = null;

        if (empty($product_data['type'])) {
            $product_data['type'] = ProductType::PRODUCT->value;
        }

        if (!empty(trim($product_data['name']))) {
            $product_data['name'] = mb_strtoupper(trim($product_data['name']));
        }

        if (!empty(trim($product_data['slug'] ?? ''))) {
            $product_data['slug'] = str_replace(' ', '-', trim($product_data['slug']));
        } else {
            $product_data['slug'] = Str::slug($product_data['name']);
        }

        if (empty($product_data['code'])) {
            $product_data['code'] = uniqid();
        }

        if (!empty($product_data['label'])) {
            $product_data['label'] = mb_strtolower($product_data['label']);
        } else {
            $product_data['label'] = null;
        }

        $categories = '';
        if (!empty($product_data['categories'])) {
            $categories = $product_data['categories'];
            unset($product_data['categories']);
        }

        $product_data = $this->updateProductImage($product_data);

        return [$product_data, $categories];
    }

    /**
     * Prepare data for updating product
     *
     * @param Product $product      Product model
     * @param array   $product_data Product data
     *
     * @return array<array, array>
     */
    public function prepareUpdateProductData(Product $product, array $product_data): array
    {
        if (empty($product_data['type'])) {
            $product_data['type'] = ProductType::PRODUCT->value;
        }

        if (
            !empty(trim($product_data['name']))
            && $product_data['name'] !== $product->name
        ) {
            $product_data['name'] = mb_strtoupper(trim($product_data['name']));
            $product_data['slug'] = Str::slug($product_data['name']);
        }

        if (!empty($product_data['label'])) {
            $product_data['label'] = mb_strtolower($product_data['label']);
        } else {
            $product_data['label'] = null;
        }

        $categories = '';
        if (!empty($product_data['categories'])) {
            $categories = $product_data['categories'];
            unset($product_data['categories']);
        }

        $product_data = $this->updateProductImage($product_data, $product->image ?? '');

        return [$product_data, $categories];
    }

    /**
     * Update product image
     *
     * @param array  $product_data  Product data
     * @param string $product_image Product image if product updating and has image
     *
     * @return array
     */
    private function updateProductImage(array $product_data, string $product_image = ''): array
    {
        if (!empty($product_data['image']) && !empty($product_data['image']->getClientOriginalExtension())) {
            if (!empty($product_image)) {
                Storage::disk('public')->delete(strstr($product_image, 'images/'));
            }

            $filenameWithExt = $product_data['image']->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extention = $product_data['image']->getClientOriginalExtension();
            $fileNameToStore = "images/products/loads/" . $filename . "_" . time() . "." . $extention;

            $product_data['image']->storePubliclyAs('public', $fileNameToStore);
            $product_data['image'] = 'storage/' . $fileNameToStore;

            /** Handle the Job: image optimize */
            ImagesOptimizeJob::dispatch(public_path($product_data['image']));
        } elseif (!empty($product_data['image']) && $product_data['image']->getClientOriginalExtension() === '') {
            $product_data['image'] = null;
            Storage::disk('public')->delete(strstr($product_image, 'images/'));
        }

        return $product_data;
    }
}
