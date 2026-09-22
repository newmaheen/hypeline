<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * পাসওয়ার্ড পরিবর্তন ফর্ম দেখানোর মেথড
     */
    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    /**
     * অ্যাডমিন পাসওয়ার্ড আপডেট করার মেথড
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'বর্তমান পাসওয়ার্ডটি সঠিক নয়!',
            'password.confirmed' => 'নতুন পাসওয়ার্ডের সাথে কনফার্ম পাসওয়ার্ডের মিল নেই।',
            'password.min' => 'নতুন পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।',
        ]);

        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();
        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'অ্যাডমিন পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে!');
    }
}