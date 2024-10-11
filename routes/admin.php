<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::controller(AuthController::class)->middleware(['guest:admin'])->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login_process', 'login')->name('login_process');
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('index', [AdminHomeController::class, 'index'])->name('home');

        Route::controller(AdminProductController::class)->prefix('product')->name('product.')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::get('/{product_id}/modify', 'edit')->name('modify');
            Route::post('store', 'store')->name('store');
            Route::post('/{product_id}/update', 'update')->name('update');
            Route::post('/{product_id}/delete', 'delete')->name('delete');
            Route::post('/{product_id}/restore', 'restore')->name('restore_product');
        });

        Route::controller(SupportController::class)->prefix('support')->name('chat.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('loadMessages/chat/{chat_id}', 'loadMessages')->name('load_messages');
            Route::post('sendMessage', 'sendMessage')->name('send');
            Route::post('markMessageAsRead/{message_id}', 'markMessageAsRead')->name('mark_message_as_read');
        });

        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::fallback(function () {
    return redirect()->route('admin.home');
});
