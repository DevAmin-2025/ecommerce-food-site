<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ForgetPasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('about-us', function () {
    return view('about-us');
})->name('about-us');

Route::prefix('contact-us')->controller(ContactUsController::class)->name('contact-us.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('store', 'store')->name('store');
});

Route::prefix('products')->controller(ProductController::class)->name('product.')->group(function () {
    Route::get('show/{product:slug}', 'show')->name('show');
    Route::get('menu', 'menu')->name('menu');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerPost')->name('register.post');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginPost')->name('login.post');
    Route::post('logout', 'logout')->name('logout')->middleware('auth');
});

Route::controller(ForgetPasswordController::class)->group(function () {
    Route::get('forget-password', 'forgetPassword')->name('forget.password');
    Route::post('forget-password', 'forgetPasswordPost')->name('forget.password.post');
    Route::get('reset-password/{token}', 'resetPassword')->name('reset.password');
    Route::post('reset-password', 'resetPasswordPost')->name('reset.password.post');
});

Route::prefix('profile')->controller(ProfileController::class)->name('profile.')->middleware('auth')->group(function () {
    Route::get('edit-password', 'editPassword')->name('edit.password');
    Route::put('update-password', 'updatePassword')->name('update.password');
    Route::get('/', 'index')->name('index');
    Route::put('{user}', 'update')->name('update');
    Route::get('addresses', 'addresses')->name('address');
    Route::get('addresses/create', 'addressCreate')->name('address.create');
    Route::post('addresses', 'addressStore')->name('address.store');
    Route::get('addresses/{address}/edit', 'addressEdit')->name('address.edit');
    Route::put('addresses/{address}', 'addressUpdate')->name('address.update');
    Route::delete('addresses/{address}', 'addressDestroy')->name('address.destroy');
    Route::get('wishlist', 'wishlist')->name('wishlist');
    Route::get('wishlist/add', 'addToWishlist')->name('add.wishlist');
    Route::delete('wishlist/{wishlist}', 'removeFromWishlist')->name('remove.wishlist');
    Route::get('orders', 'orders')->name('order');
    Route::get('transactions', 'transactions')->name('transaction');
});

Route::prefix('cart')->controller(CartController::class)->name('cart.')->middleware('auth')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('increment', 'increment')->name('increment');
    Route::get('decrement', 'decrement')->name('decrement');
    Route::get('remove', 'remove')->name('remove');
    Route::delete('clear', 'clear')->name('clear');
    Route::get('add', 'add')->name('add');
    Route::get('check-coupon', 'checkCoupon')->name('check.coupon');
    Route::delete('destroy-coupon', 'destroyCoupon')->name('destroy.coupon');
});

Route::prefix('payment')->controller(PaymentController::class)->name('payment.')->middleware('auth')->group(function () {
    Route::post('send', 'send')->name('send');
    Route::get('verify', 'verify')->name('verify');
    Route::get('status', 'status')->name('status');
});
