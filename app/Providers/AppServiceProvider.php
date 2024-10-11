<?php

namespace App\Providers;

use App\Contracts\Shop\ImagesOptimize\ImagesOptimizeContract;
use App\Contracts\Shop\Repositories\CategoryRepositoryContract;
use App\Contracts\Shop\Repositories\OrderRepositoryContract;
use App\Contracts\Shop\Repositories\ProductRepositoryContract;
use App\Contracts\Shop\Senders\SenderContract;
use App\Contracts\Shop\Services\Auth\AuthServiceContract;
use App\Contracts\Shop\Services\Auth\SecureCodeServiceContract;
use App\Contracts\Shop\Services\Cart\CartServiceContract;
use App\Contracts\Shop\Services\Orders\OrderAddressServiceContract;
use App\Contracts\Shop\Services\Orders\OrderDataServiceContract;
use App\Contracts\Shop\Services\Orders\OrderPrepareDataServiceContract;
use App\Contracts\Shop\Services\Orders\OrderProductServiceContract;
use App\Contracts\Shop\Services\Orders\OrderServiceContract;
use App\Contracts\Shop\Services\Payments\PaymentServiceContract;
use App\Contracts\Shop\Services\Products\ProductPrepareDataServiceContract;
use App\Contracts\Shop\Services\Products\ProductServiceContract;
use App\Contracts\Shop\Services\Support\SupportChatServiceContract;
use App\Contracts\Shop\Services\Support\SupportMessageServiceContract;
use App\Contracts\Shop\Services\Support\SupportServiceContract;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Services\Shop\Auth\AuthService;
use App\Services\Shop\Auth\SecureCodeService;
use App\Services\Shop\Cart\DarryldecodeCartService;
use App\Services\Shop\ImagesOptimize\TinypngService;
use App\Services\Shop\Orders\OrderAddressService;
use App\Services\Shop\Orders\OrderDataService;
use App\Services\Shop\Orders\OrderPrepareDataService;
use App\Services\Shop\Orders\OrderProductService;
use App\Services\Shop\Orders\OrderService;
use App\Services\Shop\Payments\PaymentService;
use App\Services\Shop\Products\ProductPrepareDataService;
use App\Services\Shop\Products\ProductService;
use App\Services\Shop\Support\SupportChatService;
use App\Services\Shop\Support\SupportMessageService;
use App\Services\Shop\Support\SupportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CartServiceContract::class, DarryldecodeCartService::class);
        $this->app->singleton(CategoryRepositoryContract::class, CategoryRepository::class);
        $this->app->singleton(ProductRepositoryContract::class, ProductRepository::class);
        $this->app->singleton(ProductServiceContract::class, ProductService::class);
        $this->app->singleton(OrderServiceContract::class, OrderService::class);
        $this->app->singleton(OrderDataServiceContract::class, OrderDataService::class);
        $this->app->singleton(OrderRepositoryContract::class, OrderRepository::class);
        $this->app->singleton(SecureCodeServiceContract::class, function () {
            return new SecureCodeService(true);
        });
        $this->app->singleton(AuthServiceContract::class, AuthService::class);
        $this->app->singleton(SenderContract::class, config('auth.senders.auth_secret_code'));
        $this->app->singleton(PaymentServiceContract::class, PaymentService::class);
        $this->app->singleton(ImagesOptimizeContract::class, TinypngService::class);
        $this->app->singleton(SupportServiceContract::class, SupportService::class);
        $this->app->singleton(SupportChatServiceContract::class, SupportChatService::class);
        $this->app->singleton(SupportMessageServiceContract::class, SupportMessageService::class);
        $this->app->singleton(OrderAddressServiceContract::class, OrderAddressService::class);
        $this->app->singleton(OrderPrepareDataServiceContract::class, OrderPrepareDataService::class);
        $this->app->singleton(OrderProductServiceContract::class, OrderProductService::class);
        $this->app->singleton(ProductPrepareDataServiceContract::class, ProductPrepareDataService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            \URL::forceScheme('https');
        }
    }
}
