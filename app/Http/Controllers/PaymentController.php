<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\OrderController;

class PaymentController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'address_id' => 'required|integer|exists:user_addresses,id'
        ]);

        $cart = session('cart', []);
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'سبد خرید خالی است.');
        }

        $totalAmount = 0;
        foreach ($cart as $key => $item) {
            $product = Product::findOrFail($key);
            if ($item['qty'] > $product->quantity) {
                return redirect()->route('cart.index')->with('error', 'تعداد محصول اشتباه است.');
            }
            $totalAmount += $product->is_sale
                ? $product->sale_price * $item['qty']
                : $product->price * $item['qty'];
        }

        $couponAmount = 0;
        $coupon = null;
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if (!$coupon) {
                return redirect()->route('cart.index')->with('error', 'کد تخفیف وارد شده صحیح نمیباشد.');
            }

            if ($coupon->expire_date < Carbon::now()) {
                return redirect()->route('cart.index')->with('error', 'کد تخفیف منقضی شده است.');
            };
            $couponAmount = $totalAmount * ($coupon->percent / 100);
        };

        $payingAmount = $totalAmount - $couponAmount;
        $amounts = [
            'totalAmount' => $totalAmount,
            'couponAmount' => $couponAmount,
            'payingAmount' => $payingAmount
        ];

        $parameters = array(
            "merchant"=> "zibal",
            "callbackUrl"=> "http://127.0.0.1:8001/payment/verify",
            "amount"=> $payingAmount . 0,
        );

        $response = $this->postToZibal('request', $parameters);
        if ($response->result == 100) {
            OrderController::create($request->address_id, $coupon, $amounts, $cart, $response->trackId);
            $startGateWayUrl = "https://gateway.zibal.ir/start/". $response->trackId;
            return redirect()->to($startGateWayUrl);
        } else {
            return redirect()->route('cart.index')->with('error', 'عملیات با خطا مواجه شد.');
        }
    }

    public function verify(Request $request)
    {
        if($request->success == 1) {
            $request->validate([
                'success' => 'required|integer',
                'trackId' => 'required|string',
                'status' => 'required|integer'
            ]);
            $parameters = array(
                "merchant" => "zibal",
                "trackId" => $request->trackId
            );
            $response = $this->postToZibal('verify', $parameters);
            if ($response->result == 100) {
                OrderController::update($request->trackId, $response->refNumber);
                session()->put('cart', []);
                return redirect()->route('payment.status', ['status' => $response->status, 'refNumber' => $response->refNumber]);
            } else {
                return redirect()->route('payment.status', ['status' => 0])->with('error', 'تراکنش با خطا مواجه شد.');
            };
        } else{
            return redirect()->route('payment.status', ['status' => 0])->with('error', 'تراکنش با خطا مواجه شد.');
        };
    }

    function postToZibal(string $path, array $parameters): object
    {
        $url = 'https://gateway.zibal.ir/v1/'. $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }

    public function status(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'refNumber' => 'nullable'
        ]);

        return view('payment.verify', [
            'status' => $request->status,
            'refNumber' => $request->refNumber ? $request->refNumber : null
        ]);
    }
}
