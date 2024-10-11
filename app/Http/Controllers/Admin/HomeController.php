<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::isProduct()->withTrashed()->with('categories')->orderBy('updated_at', 'desc')->limit(5)->get();

        $products_count = Product::isProduct()->active()->count();
        $orders_count = Order::count();
        $order_sum = Order::sum('result_price');
        $users_count = User::count();

        return view(
            'admin.pages.home',
            compact('products', 'products_count', 'orders_count', 'order_sum', 'users_count')
        );
    }
}
