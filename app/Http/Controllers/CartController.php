<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\View\View;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $addresses = Auth::user()->addresses;
        return view('cart.index', compact('cart', 'addresses'));
    }

    public function increment(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $qty = 1;
        $prodcut = Product::findOrFail($request->product_id);
        $cart = session('cart', []);
        if (isset($cart[$prodcut->id])) {
            if ($prodcut->quantity > $cart[$prodcut->id]['qty']) {
                $cart[$prodcut->id]['qty']++;
            } else {
                return redirect()->back()->with('error', 'محصول با بیشترین تعداد ممکن به سبد خرید اضافه شده است.');
            }
        } else {
            $cart[$prodcut->id] = [
                'image' => $prodcut->primary_image,
                'name' => $prodcut->name,
                'price' => $prodcut->price,
                'sale_price' => $prodcut->sale_price,
                'quantity' => $prodcut->quantity,
                'is_sale' => $prodcut->is_sale,
                'qty' => $qty
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'محصول مورد نظر به سبد خرید اضافه شد.');
    }

    public function decrement(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = session('cart', []);
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['qty']--;
            if ($cart[$request->product_id]['qty'] == 0) {
                unset($cart[$request->product_id]);
            };
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'محصول موردنظر از سبد خرید حذف شد.');
        };
        return redirect()->back()->with('error', 'محصول موردنظر در سبد خرید وجود ندارد.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = session('cart', []);
        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'محصول موردنظر از سبد خرید حذف شد.');
        }
        return redirect()->back()->with('error', 'محصول مورد نظر در سبد خرید موجود نمیباشد.');
    }

    public function clear(): RedirectResponse
    {
        session()->put('cart', []);
        return redirect()->route('home')->with('warning', 'سبد خرید با موفقیت خالی شد.');
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer'
        ]);

        $cart = session('cart', []);
        $product = Product::findOrFail($request->product_id);
        if ($request->qty > $product->quantity) {
            return redirect()->back()->with('error', 'موجودی محصول کافی نمیباشد.');
        }

        if (isset($cart[$product->id])) {
            if ($cart[$product->id]['qty'] + $request->qty <= $product->quantity) {
                $cart[$product->id]['qty'] += $request->qty;
            } else {
                return redirect()->back()->with('error', 'محصول با بیشترین تعداد ممکن به سبد خرید اضافه شده است.');
            }
        } else {
            $cart[$product->id] = [
                'image' => $product->primary_image,
                'name' => $product->name,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'quantity' => $product->quantity,
                'is_sale' => $product->is_sale,
                'qty' => $request->qty
            ];
        };

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'محصول مورد نظر به سبد خرید اضافه شد.');
    }

    public function checkCoupon(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();
        $usedCoupon = Order::where('user_id', Auth::id())->where('coupon_id', $coupon->id)->first();
        if (!$coupon) {
            return redirect()->back()->withErrors(['code' => 'کد تخفیف صحیح نمیباشد.']);
        } elseif ($coupon->expire_date < Carbon::now()) {
            return redirect()->back()->withErrors(['code' => 'کد تخفیف منقضی شده است.']);
        } elseif ($usedCoupon) {
            return redirect()->back()->withErrors(['code' => 'کد تخفیف قبلا استفاده شده است.']);
        }
        session()->put('coupon', ['code' => $coupon->code, 'percent' => $coupon->percent]);
        return redirect()->route('cart.index');
    }

    public function destroyCoupon(): RedirectResponse
    {
        session()->forget('coupon');
        return redirect()->route('cart.index');
    }
}
