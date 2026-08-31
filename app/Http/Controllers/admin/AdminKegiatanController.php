<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;

class AdminKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::withCount('pendaftaran')->latest()->get();

        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function store()
    {
        // TODO: validasi + Kegiatan::create([...])
    }

    public function update(Kegiatan $kegiatan)
    {
        // TODO: validasi + $kegiatan->update([...])
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return back()->with('status', 'Kegiatan dihapus.');
    }
}