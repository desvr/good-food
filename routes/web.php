<?php

use App\Http\Controllers\Shop\AuthController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\Notifications\TelegramBotController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\Payments\StripeCheckoutController;
use App\Http\Controllers\Shop\Payments\YookassaController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\SupportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('worktime', 'worktime')->name('worktime');
});

Route::controller(ProductController::class)->prefix('products')->name('product.')->group(function () {
    Route::get('{product_id}', 'show')->name('show');
    Route::post('loadVariationProduct', 'loadVariationProduct')->name('loadVariationProduct');
});

Route::controller(CategoryController::class)->prefix('categories')->name('category.')->group(function () {
    Route::get('{category}', 'show')->name('show');
});

Route::controller(CartController::class)->group(function () {
    Route::get('checkout', 'checkout')->name('checkout');
    Route::get('cart', 'index')->name('cart');
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::post('addProduct', 'addProduct')->name('addProduct');
        Route::post('increaseProduct/{product?}', 'increaseProduct')->name('increaseProduct');
        Route::post('decreaseProduct/{product?}', 'decreaseProduct')->name('decreaseProduct');
        Route::post('removeProduct/{product?}', 'removeProduct')->name('removeProduct');
        Route::post('clearCart', 'clearCart')->name('clearCart');
        Route::post('applyPromotion', 'applyPromotion')->name('applyPromotion');
        Route::post('removePromotion', 'removePromotion')->name('removePromotion');
    });
});

Route::middleware(['auth'])->controller(OrderController::class)->name('order.')->group(function () {
    Route::get('orders', 'index')->name('index');
    Route::prefix('order')->group(function () {
        Route::get('{order_id}', 'show')->name('show');
        Route::post('store', 'store')->name('store');
    });
});

Route::prefix('payments')->name('payments.')->group(function () {
    Route::controller(StripeCheckoutController::class)->prefix('stripe')->name('stripe_checkout.')->group(function () {
        Route::get('checkoutSessionCreate', 'checkoutSessionCreate')->name('checkoutSessionCreate');
        Route::get('success', 'success')->name('success');
        Route::get('cancel', 'cancel')->name('cancel');
        Route::post('webhookHandle', 'webhookHandle')->name('webhookHandle');
        Route::get('webhookInstall', 'webhookInstall')->name('webhookInstall');
    });

    Route::controller(YookassaController::class)->prefix('yookassa')->name('yookassa.')->group(function () {
        Route::get('paymentCreate', 'paymentCreate')->name('paymentCreate');
        Route::post('callback', 'callback')->name('callback');
    });
});

Route::controller(TelegramBotController::class)->prefix('telegram/bot')->name('telegram.bot.')->group(function () {
    Route::get('webhookInstall', 'webhookInstall')->name('webhookInstall');
    Route::post('webhookInitHandle', 'webhookInitHandle')->name('webhookInitHandle');
});

Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('login', 'loginPage')->name('loginPage');
        Route::get('register', 'registerPage')->name('registerPage');
    });
    Route::middleware(['auth'])->group(function () {
        Route::post('logout', 'logout')->name('logout');
    });
    Route::get('getEnterCodeModal', 'getEnterCodeModal')->name('getEnterCodeModal');
    Route::post('sendCode', 'sendCode')->name('sendCode');
    Route::post('verifyCode', 'verifyCode')->name('verifyCode');
});

Route::middleware(['auth'])->controller(SupportController::class)->name('support.')->group(function () {
    Route::get('support', 'show')->name('chat');
    Route::prefix('support')->name('chat.')->group(function () {
        Route::post('createChat', 'store')->name('store');
        Route::post('sendMessage', 'sendMessage')->name('send');
        Route::post('markMessageAsRead/{message_id}', 'markMessageAsRead')->name('mark_message_as_read');
    });
});

Route::fallback(function () {
    return redirect()->route('home');
});
