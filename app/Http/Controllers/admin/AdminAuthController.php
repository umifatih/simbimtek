<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // TODO: ganti dengan auth asli begitu model Admin/User siap, contoh:
        // if (! Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        //     return back()->withErrors(['email' => 'Email atau kata sandi salah.']);
        // }
        // $request->session()->regenerate();

        return redirect('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        // Auth::guard('admin')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}