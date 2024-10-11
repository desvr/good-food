<?php

namespace App\Providers;

use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Models\Category;
use App\Models\Product;
use App\Services\Shop\Cart\DarryldecodeCartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /** @var DarryldecodeCartService $cart_service */
    private ?CartServiceContract $cart_service = null;
    private \stdClass $base_cart_data;

    private function getCartService(): CartServiceContract
    {
        return !empty($this->cart_service)
            ? $this->cart_service
            : $this->cart_service = app(CartServiceContract::class);
    }

    private function getBaseCartData(): \stdClass
    {
        if (!empty($this->base_cart_data)) {
            return $this->base_cart_data;
        }

        $cart_service = $this->getCartService();
        $this->base_cart_data = new \stdClass;

        $this->base_cart_data->cart_collection = $cart_service->getSortedCartContent();
        $this->base_cart_data->cart_price = $cart_service->getPriceCartContent(
            $this->base_cart_data->cart_collection
        );
        $this->base_cart_data->cart_applyed_promotions = $cart_service->getApplyedPromotions();
        if ($this->base_cart_data->cart_collection->isNotEmpty()) {
            $this->base_cart_data->cart_total_quantity = $cart_service->getTotalQuantityCart();
        } else {
            $this->base_cart_data->cart_total_quantity = 0;
        }

        return $this->base_cart_data;
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Панель администратора:
         */
        View::composer([
            'admin.components.common.header_sidebar',
        ], function ($view) {
            $view->with([
                'vsp_product_count' => Product::isProduct()->active()->count(),
            ]);
        });

        /**
         * Витрина: Блок пользователя в header
         */
        View::composer(['shop/components/common/header_navbar'], function ($view) {
            $auth_id = Auth::id();
            $unread_support_messages_count = DB::table('chat_messages')
                ->join('chats', 'chat_messages.chat_id', '=', 'chats.id')
                ->whereNull('is_read')
                ->where('from_admin', '=', 1)
                ->where('chats.created_by', '=', $auth_id)
                ->count();

            $view->with([
                'vsp_user_data' => session('user_data', []),
                'vsp_app_logo' => config('app.logo'),
                'vsp_unread_support_messages_count' => $unread_support_messages_count,
            ]);
        });

        /**
         * Витрина: Количество товаров в корзине для отображения на кнопке и проверок в корзине/на чекауте
         */
        View::composer([
            'shop/components/common/header_navbar',
            'shop/pages/cart',
            'shop/pages/checkout',
        ], function ($view) {
            $base_cart_data = $this->getBaseCartData();

            $view->with([
                'vsp_cart_total_quantity'     => $base_cart_data->cart_total_quantity,
                'vsp_cart_price'              => $base_cart_data->cart_price,
                'vsp_cart_empty'              => $this->cart_service->checkCartIsEmpty(
                    $base_cart_data->cart_total_quantity,
                    $base_cart_data->cart_price['total']
                ),
                'vsp_cart_collection'         => $base_cart_data->cart_collection,
                'vsp_cart_applyed_promotions' => $base_cart_data->cart_applyed_promotions,
            ]);
        });

        /**
         * Витрина: Количество товаров в корзине для отображения на кнопке и проверок в корзине/на чекауте
         */
        View::composer([
            'shop/pages/home',
            'shop/pages/category',
        ], function ($view) {
            $base_cart_data = $this->getBaseCartData();

            $view->with([
                'vsp_cart_collection_array' => $base_cart_data->cart_collection->toArray(),
            ]);
        });

        /**
         * Витрина: Все категории, используемые для отображения в header
         */
        View::composer('shop/components/common/header_navbar', function ($view) {
            $view->with([
                'vsp_categories' => Cache::tags(['categories'])->remember(
                    \App\Enum\Cache\Repository\Category::CACHE_KEY_SIMPLE_CATEGORY_LIST,
                    \App\Enum\Cache\Repository\Category::CACHE_TTL_SIMPLE_CATEGORY_LIST,
                    function() {
                        return Category::all()
                            ->whereNotIn('slug', ['none'])
                            ->where('parent_id', '=', 0);
                    }
                ),
            ]);
        });
    }
}
