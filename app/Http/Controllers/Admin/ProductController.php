<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Shop\Services\Products\ProductServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ModifyProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\Shop\Products\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductServiceContract $productService
    ) {}

    public function index()
    {
        $products = Product::isProduct()->withTrashed()->with('categories')->orderBy('updated_at', 'desc')->paginate(15);

        return view('admin.pages.products.index', compact('products'));
    }

    public function restore(Request $request, int $product_id)
    {
        $product = Product::onlyTrashed()->findOrFail($product_id);
        $product->restore();

        return redirect()->route('admin.product.modify', ['product_id' => $product_id])
            ->with('status', 'success')->with('status_message', 'Товар успешно восстановлен! Выберите подходящую категорию товара.');
    }

    public function create(Request $request)
    {
        $categories = Category::all();

        return view('admin.pages.products.modify', compact('categories'));
    }

    public function store(ModifyProductRequest $request)
    {
        try {
            $this->productService->createProduct($request->validated());
        } catch (\Exception) {
            return back()->withInput()->with('status', 'danger')->with('status_message', 'Возникли проблемы при создании товара.');
        }

        return redirect()->route('admin.product.index')
            ->with('status', 'success')->with('status_message', 'Новый товар успешно создан!');
    }

    public function edit(Request $request, int $product_id)
    {
        $product = Product::with('categories')->findOrFail($product_id);
        $product_categories = $product->categories->pluck('slug')->toArray();
        $categories = Category::all();

        return view('admin.pages.products.modify', compact('product', 'categories', 'product_categories'));
    }

    public function update(ModifyProductRequest $request, int $product_id)
    {
        try {
            $this->productService->updateProduct($product_id, $request->validated());
        } catch (\Exception) {
            return back()->withInput()->with('status', 'danger')->with('status_message', 'Возникли проблемы при изменении товара.');
        }

        return redirect()->route('admin.product.modify', ['product_id' => $product_id])
            ->with('status', 'success')->with('status_message', 'Товар успешно изменен!');
    }

    public function delete(Request $request, int $product_id)
    {
        $this->productService->deleteProduct($product_id);

        return redirect()->route('admin.product.index')
            ->with('status', 'success')->with('status_message', 'Товар успешно удален!');
    }
}
