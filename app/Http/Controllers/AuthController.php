<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(): View
    {
        return view('auth.register');
    }

    public function registerPost(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($user) {
            return redirect()->route('login')->with('success', 'ثبت نام با موفقیت انجام شد. لطفا وارد شوید.');
        }
        return redirect()->back()->with('error', 'ثبت نام با خطا مواجه شد. لطفا دوباره تلاش کنید.');
    }

    public function login(): View
    {
        return view('auth.login');
    }

    public function loginPost(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'کاربر با این ایمیل وجود ندارد.');
        }

        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'رمزعبور اشتباه است.');
        }

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'ورود با موفقیت انجام شد.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('warning', 'خروج با موفقیت انجام شد.');
    }
}
