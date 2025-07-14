<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactUsController extends Controller
{
    public function index(): View
    {
        return view('contact-us');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:5',
            'email' => 'required|email|string',
            'subject' => 'required|string|min:5',
            'body' => 'required|string|min:15'
        ]);

        ContactUs::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'body' => $request->body
        ]);

        return redirect()->back()->with("success", "پیام شما با موفقیت ثبت شد.");
    }
}
