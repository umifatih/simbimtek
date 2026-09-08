<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function indexScan()
{
    $kegiatan = Kegiatan::where('status', 'dibuka')
        ->whereDate('tanggal_mulai', '<=', now())
        ->whereDate('tanggal_selesai', '>=', now())
        ->orderBy('tanggal_mulai')
        ->first();

    if (! $kegiatan) {
        $kegiatan = Kegiatan::where('status', 'dibuka')
            ->orderBy('tanggal_mulai')
            ->first();
    }

    $jadwal_harian = [];

    if ($kegiatan) {
        $periode = CarbonPeriod::create($kegiatan->tanggal_mulai, $kegiatan->tanggal_selesai);
        $hariKe = 1;
        foreach ($periode as $tanggal) {
            $jadwal_harian[$tanggal->toDateString()] = "Hari ke-{$hariKe} (" . $tanggal->translatedFormat('d M Y') . ")";
            $hariKe++;
        }
    }

    return view('admin.absensi.scan', compact('kegiatan', 'jadwal_harian'));
}

    public function riwayat(Request $request, Kegiatan $kegiatan)
    {
        $tanggal = $request->query('tanggal', now()->toDateString());

        $data = Absensi::with('pendaftaran')
            ->whereHas('pendaftaran', fn ($q) => $q->where('kegiatan_id', $kegiatan->id))
            ->where('tanggal_hadir', $tanggal)
            ->orderByDesc('waktu_scan')
            ->get()
            ->map(fn ($a) => [
                'nama'       => $a->pendaftaran->nama_gelar,
                'unit_kerja' => $a->pendaftaran->unit_kerja,
                'jam'        => $a->waktu_scan->format('H:i'),
            ]);

        return response()->json($data);
    }

    public function scan(Request $request)
    {
        $validated = $request->validate([
            'kode'    => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $pendaftaran = Pendaftaran::with(['kegiatan', 'peserta'])
            ->where('token_kehadiran', $validated['kode'])
            ->first();

        if (! $pendaftaran) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'QR tidak dikenali atau tidak valid.'
            ]);
        }

        $kegiatan = $pendaftaran->kegiatan;
        $tanggalDipilih = $validated['tanggal'];

        if ($tanggalDipilih < $kegiatan->tanggal_mulai->toDateString() || $tanggalDipilih > $kegiatan->tanggal_selesai->toDateString()) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Tanggal terpilih di luar rentang kegiatan.'
            ]);
        }

        $sudahAbsen = Absensi::where('pendaftaran_id', $pendaftaran->id)
            ->where('tanggal_hadir', $tanggalDipilih)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'duplikat',
                'nama'   => $pendaftaran->nama_gelar,
                'pesan'  => 'Peserta sudah diabsen untuk tanggal tersebut.'
            ]);
        }

        Absensi::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal_hadir'  => $tanggalDipilih,
            'waktu_scan'     => now(),
        ]);

        $sudahLengkap = $pendaftaran->absensiLengkap();
        if ($sudahLengkap && $pendaftaran->status !== 'sertifikat') {
            $pendaftaran->update(['status' => 'sertifikat']);
        }

        return response()->json([
            'status'     => 'berhasil',
            'nama'       => $pendaftaran->nama_gelar,
            'unit_kerja' => $pendaftaran->unit_kerja,
            'kegiatan'   => $kegiatan->nama,
            'jam'        => now()->format('H:i'),
        ]);
    }
}