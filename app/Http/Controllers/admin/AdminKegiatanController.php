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
        return $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal' => 'required|string|max:100',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:dibuka,ditutup,selesai',
        ]);
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return back()->with('status', 'Kegiatan dihapus.');
    }
}