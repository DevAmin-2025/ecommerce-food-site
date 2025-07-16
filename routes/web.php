<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactUsController;

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
