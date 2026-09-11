<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AdminSertifikatController extends Controller
{
    public function index(Request $request)
    {
        $kegiatanList = Kegiatan::orderByDesc('tanggal_mulai')->get();

        $kegiatanId = $request->query('kegiatan_id', optional($kegiatanList->first())->id);

        $pendaftaranList = collect();

        if ($kegiatanId) {
            $pendaftaranList = Pendaftaran::with(['peserta', 'kegiatan'])
                ->where('kegiatan_id', $kegiatanId)
                ->get()
                ->filter(fn (Pendaftaran $p) => $p->absensiLengkap())
                ->values();
        }

        return view('admin.sertifikat.index', compact('kegiatanList', 'kegiatanId', 'pendaftaranList'));
    }
}