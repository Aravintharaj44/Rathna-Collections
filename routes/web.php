<?php

use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Storefront (public) routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop & product catalog.
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('product.show');

// Cart (available to guests + users).
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/{product}', 'store')->name('cart.store');
    Route::patch('/cart/{item}', 'update')->name('cart.update');
    Route::delete('/cart/{item}', 'destroy')->name('cart.destroy');
    Route::post('/cart-coupon', 'applyCoupon')->name('cart.coupon.apply');
    Route::delete('/cart-coupon', 'removeCoupon')->name('cart.coupon.remove');
});

// CMS pages + newsletter.
Route::get('/page/{page}', [PageController::class, 'show'])->name('page.show');
Route::post('/subscribe', [PageController::class, 'subscribe'])->name('subscribe');

/*
|--------------------------------------------------------------------------
| Authenticated customer routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Reviews.
    Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('review.store');

    // Wishlist.
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Checkout + payment.
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [PaymentController::class, 'place'])->name('checkout.place');
    Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::get('/checkout/success/{order}', [PaymentController::class, 'success'])->name('checkout.success');

    // Account area.
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');

        // Addresses.
        Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
        Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });
});

// Authentication routes (login, register, password reset).
require __DIR__.'/auth.php';

// Admin panel routes (prefixed /admin, guarded by the admin middleware).
require __DIR__.'/admin.php';
