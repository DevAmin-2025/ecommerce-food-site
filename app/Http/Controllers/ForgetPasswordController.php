<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(): View
    {
        return view('auth.forget-password');
    }

    public function forgetPasswordPost(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if ($user) {
            return redirect()->back()->with('error', 'ایمیل بازیابی رمزعبور قبلا برای شما ارسال شده است.');
        }

        $token = str()->random(64);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forget-password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        return redirect()->back()->with('success', 'ایمیل بازیابی برای شما ارسال شد.');
    }

    public function resetPassword($token): View
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPasswordPost(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user = DB::table('password_reset_tokens')->where('token', $request->token)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'اطلاعات اشتباه است.');
        }

        try {
            DB::beginTransaction();
            User::where('email', $user->email)->update([
                'password' => Hash::make($request->password)
            ]);
            DB::table('password_reset_tokens')->where('token', $request->token)->delete();
            DB::commit();
            return redirect()->route('login')->with('success', 'رمزعبور با موفقیت تغییر کرد. لطفا وارد شوید.');
        } catch (\Exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'عملیات با موفقیت انجام نشد. لطفا دوباره تلاش کنید.');
        }
    }
}
