<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ForgetPasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('about-us', function () {
    return view('about-us');
})->name('about-us');

Route::group(['prefix' => 'contact-us'], function () {
    Route::get('/', [ContactUsController::class, 'index'])->name('contact-us.index');
    Route::post('store', [ContactUsController::class, 'store'])->name('contact-us.store');
});

Route::get('products/show/{product:slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('products/menu', [ProductController::class, 'menu'])->name('product.menu');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'registerPost'])->name('register.post');
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'loginPost'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('forget-password', [ForgetPasswordController::class, 'forgetPassword'])->name('forget.password');
Route::post('forget-password', [ForgetPasswordController::class, 'forgetPasswordPost'])->name('forget.password.post');
Route::get('reset-password/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('reset.password');
Route::post('reset-password', [ForgetPasswordController::class, 'resetPasswordPost'])->name('reset.password.post');
