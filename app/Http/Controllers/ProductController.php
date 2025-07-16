<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        $randomProducts = Product::inRandomOrder()->limit(4)->get();
        return view('products.show', compact('product', 'randomProducts'));
    }

    public function menu(Request $request): View
    {
        $categories = Category::where('status', 1)->get();
        $products = Product::search($request->search)
            ->filterCategory()
            ->filterAttribute()
            ->where('status', 1)
            ->paginate(9);
        return view('products.menu', compact('products', 'categories'));
    }
}
