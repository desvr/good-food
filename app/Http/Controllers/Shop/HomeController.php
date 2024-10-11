<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\Repositories\CategoryRepositoryContract;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Количество отображаемых карточек каждого типа товаров на главной странице
     * (если кол-во > $limit_view_category_products, отображается empty_card)
     */
    protected int $limitViewCategoryProducts = 12;

    public function index(CategoryRepositoryContract $categoryRepository)
    {
        $categories = $categoryRepository->getCategoryList($this->limitViewCategoryProducts);
        $limit_view_category_products = $this->limitViewCategoryProducts - 1;

        $promotions = [
            'storage/images/promo/1.jpeg',
            'storage/images/promo/2.jpeg',
        ];

        return view('shop.pages.home', compact('categories', 'limit_view_category_products', 'promotions'));
    }

    public function worktime()
    {
        return view('shop.pages.worktime');
    }
}
