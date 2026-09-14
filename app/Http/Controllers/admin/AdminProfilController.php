<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminProfilController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profil.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('admins', 'email')->ignore($admin->id)],
            'password_lama' => 'nullable|required_with:password_baru|current_password:admin',
            'password_baru' => 'nullable|min:8|confirmed',
        ], [
            'password_lama.current_password' => 'Password lama tidak cocok.',
        ]);

        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (!empty($data['password_baru'])) {
            $admin->password = Hash::make($data['password_baru']);
        }

        $admin->save();

        return back()->with('status', 'Profil berhasil diperbarui.');
    }
}