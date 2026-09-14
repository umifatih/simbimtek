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
            'nama_ketua_k3s' => ['nullable', 'string', 'max:100'],
            'nip_ketua_k3s' => ['nullable', 'string', 'max:50'],
            'tagline' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tentang_program_judul' => ['required', 'string', 'max:255'],
            'tentang_program_deskripsi' => ['required', 'string'],
            'syarat_judul' => ['required', 'string', 'max:255'],
            'syarat_list_text' => ['required', 'string'],
            'kenapa_simbimtek_judul' => ['required', 'string', 'max:255'],
            'fitur' => ['required', 'array', 'size:4'],
            'fitur.*.judul' => ['required', 'string', 'max:100'],
            'fitur.*.desc' => ['required', 'string', 'max:255'],
        ]);

        // Pecah textarea jadi array, buang baris kosong
        $data['syarat_list'] = collect(explode("\n", $data['syarat_list_text']))
            ->map(fn ($baris) => trim($baris))
            ->filter(fn ($baris) => $baris !== '')
            ->values()
            ->all();

        unset($data['syarat_list_text']);

        // [DI SINI] Validasi tambahan: minimal 1 syarat setelah baris kosong dibuang
        if (empty($data['syarat_list'])) {
            return back()
                ->withErrors(['syarat_list_text' => 'Minimal isi satu syarat.'])
                ->withInput();
        }

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