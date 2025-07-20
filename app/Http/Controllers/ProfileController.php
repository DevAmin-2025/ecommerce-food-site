<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Order;
use App\Models\Province;
use App\Models\Transaction;
use App\Models\Wishlist;
use Illuminate\View\View;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('profile.index');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|min:3|string',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'اطلاعات کاربری با موفقیت ویرایش شد. لطفا مجدد وارد شوید.');
    }

    public function editPassword(): View
    {
        return view('profile.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::find(Auth::id());
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'رمزعبور وارد شده صحیح نمیباشد.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'رمزعبور باموفقیت تغییر کرد. لطفا مجدد وارد شوید.');
    }

    public function addresses(): View
    {
        $user = User::find(Auth::id());
        $addresses = $user->addresses()->with('province', 'city')->get();
        return view('profile.address.index', compact('addresses'));
    }

    public function addressCreate(): View
    {
        $provinces = Province::all();
        $cities = City::all();
        return view('profile.address.create', compact('provinces', 'cities'));
    }

    public function addressStore(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                Rule::unique('user_addresses', 'title')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
            'cellphone' => ['required', 'unique:user_addresses,phone', 'regex:/^09[0-3][0-9]{8}$/'],
            'postal_code' => ['required', 'unique:user_addresses,postal_code', 'regex:/^\d{5}[ -]?\d{5}$/'],
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string']
        ]);

        UserAddress::create([
            'title' => $request->title,
            'address' => $request->address,
            'phone' => $request->cellphone,
            'postal_code' => $request->postal_code,
            'user_id' => Auth::id(),
            'province_id' => $request->province_id,
            'city_id' => $request->city_id
        ]);
        return redirect()->route('profile.address')->with('success', 'آدرس شما با موفقیت ثبت شد.');
    }

    public function addressEdit(UserAddress $address): View
    {
        $provinces = Province::all();
        $cities = City::all();
        return view('profile.address.edit', compact('provinces', 'cities', 'address'));
    }

    public function addressUpdate(Request $request, UserAddress $address): RedirectResponse
    {
        $request->validate([
            'title' => [
                'required',
                'string',
                Rule::unique('user_addresses', 'title')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($address->id)
            ],
            'cellphone' => [
                'required',
                'unique:user_addresses,phone,' . $address->id,
                'regex:/^09[0-3][0-9]{8}$/'
            ],
            'postal_code' => [
                'required',
                'unique:user_addresses,postal_code,' . $address->id,
                'regex:/^\d{5}[ -]?\d{5}$/'
            ],
            'province_id' => ['required', 'exists:provinces,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string']
        ]);

        $address->update([
            'title' => $request->title,
            'address' => $request->address,
            'phone' => $request->cellphone,
            'postal_code' => $request->postal_code,
            'user_id' => Auth::id(),
            'province_id' => $request->province_id,
            'city_id' => $request->city_id
        ]);
        return redirect()->route('profile.address')->with('success', 'آدرس شما با موفقیت ویرایش شد.');
    }

    public function addressDestroy(UserAddress $address): RedirectResponse
    {
        $hasOrder = Order::where('address_id', $address->id)->get();
        if ($hasOrder->isNotEmpty()) {
            return redirect()->route('profile.address')->with('error', 'نمیتوانید این آدرس را پاک کنید زیرا قبلا سفارش هایی را با این آدرس ثبت کرده اید.');;
        }
        $address->delete();
        return redirect()->route('profile.address')->with('warning', 'آدرس با موفقیت حذف شد.');
    }

    public function addToWishlist(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $wishlistProduct = Wishlist::where('product_id', $request->product_id)->where('user_id', Auth::id())->first();
        if ($wishlistProduct) {
            return redirect()->back()->with('warning', 'محصول مورد نظر در لیست علاقه مندی ها وجود دارد.');
        }

        Wishlist::create([
            'product_id' => $request->product_id,
            'user_id' => Auth::id()
        ]);
        return redirect()->back()->with('success', 'محصول مورد نظر به لیست علاقه مندی ها افزوده شد.');
    }

    public function wishlist(): View
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->get();
        return view('profile.wishlist', compact('wishlist'));
    }

    public function removeFromWishlist(Wishlist $wishlist): RedirectResponse
    {
        $wishlist->delete();
        return redirect()->back()->with('warning', 'محصول مورد نظر از لیست علاقه مندی ها حذف شد.');
    }

    public function orders(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->with('address', 'orderItems')
            ->paginate(10);
        return view('profile.orders', compact('orders'));
    }

    public function transactions(): View
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('profile.transactions', compact('transactions'));
    }
}
