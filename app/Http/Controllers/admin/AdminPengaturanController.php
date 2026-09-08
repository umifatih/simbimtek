<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPengaturanController extends Controller
{
    public function edit()
    {
        $setting = SiteSetting::current();

        return view('admin.pengaturan.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = SiteSetting::current();

        $data = $request->validate([
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:1024'],
            'nama_aplikasi' => ['required', 'string', 'max:100'],
            'tagline' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tentang_program_judul' => ['required', 'string', 'max:255'],
            'tentang_program_deskripsi' => ['required', 'string'],
            'kenapa_simbimtek_judul' => ['required', 'string', 'max:255'],
            'fitur' => ['required', 'array', 'size:4'],
            'fitur.*.judul' => ['required', 'string', 'max:100'],
            'fitur.*.desc' => ['required', 'string', 'max:255'],
        ]);

        if ($request->hasFile('logo')) {
            if ($setting->logo_path) {
                Storage::disk('public')->delete($setting->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logo', 'public');
        }

        $setting->update($data);

        return back()->with('status', 'Pengaturan beranda berhasil disimpan.');
    }
}