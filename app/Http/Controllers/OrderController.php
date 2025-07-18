<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public static function create(
        int $addressId,
        ?Coupon $coupon,
        array $amounts,
        array $cart,
        string $token
    ): ?RedirectResponse
    {
        try {
            DB::beginTransaction();
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $addressId,
                'coupon_id' => $coupon ? $coupon->id : null,
                'total_amount' => $amounts['totalAmount'],
                'coupon_amount' => $amounts['couponAmount'],
                'paying_amount' => $amounts['payingAmount'],
            ]);

            foreach ($cart as $key => $item) {
                $product = Product::findOrFail($key);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $key,
                    'price' => $product->is_sale ? $product->sale_price : $product->price,
                    'quantity' => $item['qty'],
                    'subtotal' => $product->is_sale
                        ? $product->sale_price * $item['qty']
                        : $product->price * $item['qty'],
                ]);
            }

            Transaction::create([
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'amount' => $amounts['payingAmount'],
                'token' => $token
            ]);
            DB::commit();
            return null;
        } catch (\Exception) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'عملیات با خطا مواجه شد.');
        }
    }

    public static function update(string $token, ?int $refNumber): ?RedirectResponse
    {
        try {
            DB::beginTransaction();
            $transaction = Transaction::where('token', $token)->first();
            $transaction->update([
                'ref_number' => $refNumber,
                'status' => 1
            ]);

            $order = Order::findOrFail($transaction->order_id);
            $order->update([
                'status' => 1,
                'payment_status' => 1
            ]);

            $orderItems = OrderItem::where('order_id', $transaction->order_id)->get();
            foreach ($orderItems as $orderItem) {
                $product = Product::findOrFail($orderItem->product_id);
                $product->update([
                    'quantity' => $product->quantity - $orderItem->quantity
                ]);
            }
            DB::commit();
            return null;
        } catch (\Exception $e) {
            DB::rollBack();
            // return redirect()->route('cart.index')->with('error', 'عملیات با خطا مواجه شد.');
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }
}
