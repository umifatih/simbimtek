<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Kegiatan::withCount(['pendaftaran as kuota_terisi'])
            ->orderBy('tanggal_mulai');

        if (in_array($status, ['dibuka', 'ditutup', 'selesai'], true)) {
            $query->where('status', $status);
        }

        $kegiatanList = $query->get();

        return view('jadwal.index', compact('kegiatanList', 'status'));
    }
}