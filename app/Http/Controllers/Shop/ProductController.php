<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\Repositories\ProductRepositoryContract;
use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryContract $productRepository,
        protected CartServiceContract $cartService,
    ) {}

    public function show(Request $request, int $product_id)
    {
        $product = $this->productRepository->getProductCardData($product_id);
        $vsp_cart_collection = $this->cartService->getSortedCartContent()->toArray();

        return view(
            'shop.components.products.modals.product_page',
            compact('product', 'vsp_cart_collection')
        );
    }

    /**
     * {POST} {Ajax} Load variation product by selected variation.
     *
     * @param Request $request
     */
    public function loadVariationProduct(Request $request)
    {
        $product_id = $request->get('product_id');
        $is_modal = $request->get('is_modal');

        $product_addition_data = $this->productRepository->getAdditionVariationProductData($product_id);
        $product_addition_data['product_quantity_cart'] = $this->cartService->getProductQuantityCart($product_id);

        if ($product_addition_data['product_quantity_cart'] > 0) {
            if (!empty($is_modal) && $is_modal === 'true') {
                $view_regulator_blade = 'shop.components.products.modals.button_product_cart_regulator_big';
            } else {
                $view_regulator_blade = 'shop.components.products.button_product_cart_regulator_medium';
            }

            $product_addition_data['product_quantity_regulator'] = Blade::render(
                $view_regulator_blade,
                [
                    'product_id' => $product_id,
                    'product_quantity_cart' => $product_addition_data['product_quantity_cart']
                ]
            );
        } else {
            if ($is_modal === 'true') {
                $product_addition_data['product_quantity_regulator'] = Blade::render(
                    'shop.components.products.modals.button_add_product_cart_big',
                    ['product_id' => $product_id]
                );
            } else {
                $product_addition_data['product_quantity_regulator'] = Blade::render(
                    'shop.components.products.button_add_product_cart_medium',
                    ['product_id' => $product_id]
                );
            }
        }

        return response()->json($product_addition_data);
    }
}
