<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoreController::class, 'home'])->name('home');
Route::get('/store', [StoreController::class, 'index'])->name('store.index');
Route::get('/product/{product:slug}', [StoreController::class, 'show'])->name('products.show');
Route::get('/product/{product:slug}/preview', PreviewController::class)->name('products.preview');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.store');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/orders/{order}', [AccountController::class, 'show'])->name('account.orders.show');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/notifications/read-all', [AccountController::class, 'markNotificationsRead'])->name('account.notifications.read-all');
    Route::post('/account/support', [AccountController::class, 'storeSupport'])->name('account.support.store');
    Route::post('/account/orders/{order}/request-review', [AccountController::class, 'requestReview'])->name('account.orders.request-review');
    Route::post('/account/orders/{order}/request-download', [AccountController::class, 'requestDownload'])->name('account.orders.request-download');
    Route::post('/wishlist/{product:slug}', [AccountController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/checkout/{product:slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/order/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});

Route::get('/order/{order}/receipt', ReceiptController::class)->name('orders.receipt')->middleware('signed');
Route::get('/download/{token}', DownloadController::class)->name('download');

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->name('admin.login.store');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin.auth')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/screenshot', [AdminOrderController::class, 'screenshot'])->name('orders.screenshot');
        Route::get('/orders/{order}/receipt', ReceiptController::class)->name('orders.receipt');
        Route::post('/orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('orders.approve');
        Route::post('/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
        Route::post('/orders/{order}/refresh-download', [AdminOrderController::class, 'refreshDownload'])->name('orders.refresh-download');

        Route::resource('products', AdminProductController::class)->except('show');
        Route::resource('coupons', AdminCouponController::class)->except('show');
    });
