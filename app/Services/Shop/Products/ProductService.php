<?php

namespace App\Services\Shop\Products;

use App\Contracts\Shop\Services\Products\ProductPrepareDataServiceContract;
use App\Contracts\Shop\Services\Products\ProductServiceContract;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductService implements ProductServiceContract
{
    public function __construct(
        private readonly ProductPrepareDataServiceContract $productPrepareDataService,
    ) {}

    /**
     * Create product.
     *
     * @param array $product_data Product data.
     *
     * @return bool
     */
    public function createProduct(array $product_data = []): bool
    {
        if (empty($product_data)) {
            return false;
        }

        $product = new Product();

        [$product_data, $categories] = $this->productPrepareDataService->prepareCreateProductData($product_data);

        try {
            DB::transaction(function() use ($product, $product_data, $categories) {
                $created_product = $product->create($product_data);

                if (!empty($categories)) {
                    $created_product->categories()->sync([$categories]);
                }

                return true;
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * Update product.
     *
     * @param int   $product_id   Product ID.
     * @param array $product_data Product data.
     *
     * @return bool
     */
    public function updateProduct(int $product_id, array $product_data = []): bool
    {
        if (empty($product_data)) {
            return false;
        }

        $product = Product::query()->where('id', $product_id);
        $product_model = Product::find($product_id);

        [$product_data, $categories] = $this->productPrepareDataService->prepareUpdateProductData($product_model, $product_data);

        try {
            DB::transaction(function() use ($product_model, $product, $product_data, $categories) {
                $product->update($product_data);

                if (!empty($categories)) {
                    $product_model->categories()->sync([$categories]);
                }

                return true;
            });
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }

    /**
     * Delete product.
     *
     * @param int   $product_id   Product ID.
     *
     * @return bool
     */
    public function deleteProduct(int $product_id): bool
    {
        $product = Product::find($product_id);
        $product->categories()->detach();
        $product->delete();

        return true;
    }
}
