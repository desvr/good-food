<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\Repositories\CategoryRepositoryContract;
use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Helpers\Filters\ProductFilter;
use App\Http\Controllers\Controller;
use App\Services\Shop\Cart\DarryldecodeCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryContract $categoryRepository
    ) {}

    public function show(Request $request, string $category_slug)
    {
        if (!$request->ajax()) {
            $category = $this->categoryRepository->getCategoryData($category_slug);
            $category_filters = $category->getCategoryFilterList();

            return view(
                'shop.pages.category',
                compact('category', 'category_filters')
            );
        } else {
            $product_filter = app(ProductFilter::class, [
                'filter_data' => $request->get('filter_data') ?? []
            ]);

            $category = $this->categoryRepository->getCategoryData($category_slug, $product_filter);

            $cart_service = app(CartServiceContract::class);
            $cart_collection = $cart_service->getSortedCartContent();
            $vsp_cart_collection_array = $cart_collection->toArray();

            return response()->json(
                Blade::render(
                    'shop.pages.categories.sections.products_section',
                    compact(['category', 'vsp_cart_collection_array']),
                ),
            );

        }
    }
}
