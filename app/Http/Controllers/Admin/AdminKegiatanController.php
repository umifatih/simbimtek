<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class AdminKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::withCount('pendaftaran')->latest()->get();

        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function store(Request $request)
    {
        $validated = $this->validasi($request);

        Kegiatan::create($validated);

        return back()->with('status', 'Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $validated = $this->validasi($request);

        $kegiatan->update($validated);

        return back()->with('status', 'Kegiatan berhasil diperbarui.');
    }

  private function validasi(Request $request): array
{
    if ($request->filled('waktu')) {
        $request->merge(['waktu' => substr($request->waktu, 0, 5)]);
    }

    return $request->validate([
        'nama' => 'required|string|max:255',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'waktu' => 'required|date_format:H:i',
        'lokasi' => 'required|string|max:255',
        'kuota' => 'required|integer|min:1',
        'status' => 'required|in:dibuka,ditutup,selesai',
        'nomor_surat_dasar' => 'nullable|string|max:255',
        'tanggal_surat_dasar' => 'nullable|date',
        'nama_panitia' => 'nullable|string|max:255',
        'nip_panitia' => 'nullable|string|max:50',
        'nama_sekretaris' => 'nullable|string|max:255',
        'nip_sekretaris' => 'nullable|string|max:50',
    ]);
}

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return back()->with('status', 'Kegiatan dihapus.');
    }
}