<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class CekStatusController extends Controller
{
    public function index(Request $request)
    {
        $nomor = $request->query('nomor_pendaftaran');
        $dicari = filled($nomor);
        $pendaftaran = null;

        if ($dicari) {
            $pendaftaran = Pendaftaran::with(['peserta', 'kegiatan'])
                ->where('nomor_pendaftaran', strtoupper(trim($nomor)))
                ->first();
        }

        return view('status.index', compact('pendaftaran', 'dicari'));
    }
}