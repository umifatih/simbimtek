<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class SertifikatController extends Controller
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

        return view('sertifikat.index', compact('pendaftaran', 'dicari'));
    }
}