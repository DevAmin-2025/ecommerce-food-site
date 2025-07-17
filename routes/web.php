<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('about-us', function () {
    return view('about-us');
})->name('about-us');

Route::group(['prefix' => 'contact-us'], function () {
    Route::get('/', [ContactUsController::class, 'index'])->name('contact-us.index');
    Route::post('store', [ContactUsController::class, 'store'])->name('contact-us.store');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('products/show/{product:slug}', 'show')->name('product.show');
    Route::get('products/menu', 'menu')->name('product.menu');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerPost')->name('register.post');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginPost')->name('login.post');
    Route::post('logout', 'logout')->name('logout');
});

Route::controller(ForgetPasswordController::class)->group(function () {
    Route::get('forget-password', 'forgetPassword')->name('forget.password');
    Route::post('forget-password', 'forgetPasswordPost')->name('forget.password.post');
    Route::get('reset-password/{token}', 'resetPassword')->name('reset.password');
    Route::post('reset-password', 'resetPasswordPost')->name('reset.password.post');
});

Route::prefix('profile')->controller(ProfileController::class)->group(function () {
    Route::get('edit-password', 'editPassword')->name('profile.edit.password');
    Route::put('update-password', 'updatePassword')->name('profile.update.password');
    Route::get('/', 'index')->name('profile.index');
    Route::put('{user}', 'update')->name('profile.update');
    Route::get('addresses', 'addresses')->name('profile.address');
    Route::get('addresses/create', 'addressCreate')->name('profile.address.create');
    Route::post('addresses', 'addressStore')->name('profile.address.store');
    Route::get('addresses/{address}/edit', 'addressEdit')->name('profile.address.edit');
    Route::put('addresses/{address}', 'addressUpdate')->name('profile.address.update');
    Route::delete('addresses/{address}', 'addressDestroy')->name('profile.address.destroy');
    Route::get('wishlist', 'wishlist')->name('profile.wishlist');
    Route::get('wishlist/add', 'addToWishlist')->name('profile.add.wishlist');
    Route::delete('wishlist/{wishlist}', 'removeFromWishlist')->name('profile.remove.wishlist');
});
