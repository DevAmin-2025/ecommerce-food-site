<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $randomProducts = Product::inRandomOrder()->limit(4)->get();
        return view('products.show', compact('product', 'randomProducts'));
    }
}
