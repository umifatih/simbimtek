<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AdminPesertaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['peserta', 'kegiatan'])->latest();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->whereHas('peserta', function ($q) use ($cari) {
                $q->where('nama_gelar', 'like', "%{$cari}%")
                  ->orWhere('unit_kerja', 'like', "%{$cari}%")
                  ->orWhere('nip', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peserta = $query->paginate(20)->withQueryString();
        $kegiatan = Kegiatan::orderBy('nama')->get();

        return view('admin.peserta.index', compact('peserta', 'kegiatan'));
    }

    public function verifikasi()
    {
        $antrean = Pendaftaran::with(['peserta', 'kegiatan'])
            ->where('status', 'daftar')
            ->oldest()
            ->get();

        return view('admin.peserta.verifikasi', compact('antrean'));
    }

    public function setujui(Pendaftaran $pendaftaran)
    {
        $pendaftaran->update(['status' => 'verifikasi']);

        return back()->with('status', "{$pendaftaran->nama_gelar} berhasil diverifikasi.");
    }

    public function tolak(Pendaftaran $pendaftaran)
    {
        // TODO: putuskan kebijakan sebenarnya — hapus pendaftaran, atau simpan dengan
        // status 'ditolak' + alasan? Untuk sekarang pendaftaran dihapus.
        $nama = $pendaftaran->nama_gelar;
        $pendaftaran->delete();

        return back()->with('status', "Pendaftaran {$nama} ditolak dan dihapus.");
    }
}