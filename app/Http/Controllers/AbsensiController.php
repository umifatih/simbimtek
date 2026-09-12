<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Pendaftaran;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function indexScan(Request $request)
    {
        $daftarKegiatan = Kegiatan::orderByDesc('tanggal_mulai')->get();

        $kegiatan = null;
        $kegiatanIdDipilih = $request->query('kegiatan_id');

        if ($kegiatanIdDipilih) {
            $kegiatan = $daftarKegiatan->firstWhere('id', (int) $kegiatanIdDipilih);
        }

        if (! $kegiatan) {
            $kegiatan = $daftarKegiatan->first(
                fn ($k) => $k->tanggal_mulai->lte(now('Asia/Jakarta')) && $k->tanggal_selesai->gte(now('Asia/Jakarta'))
            );

            if (! $kegiatan) {
                $kegiatan = $daftarKegiatan
                    ->filter(fn ($k) => $k->tanggal_mulai->gt(now('Asia/Jakarta')))
                    ->sortBy('tanggal_mulai')
                    ->first();
            }

            if (! $kegiatan) {
                $kegiatan = $daftarKegiatan
                    ->filter(fn ($k) => $k->tanggal_selesai->lt(now('Asia/Jakarta')))
                    ->sortByDesc('tanggal_selesai')
                    ->first();
            }

            $kegiatan = $kegiatan ?? $daftarKegiatan->first();
        }

        // [PERBAIKAN]: Cek status kegiatan (buka, belum_mulai, atau tutup) menggunakan WIB
        $statusAbsen = 'tutup';
        $hariIni = now('Asia/Jakarta')->toDateString();

        if ($kegiatan) {
            $tglMulai = $kegiatan->tanggal_mulai->toDateString();
            $tglSelesai = $kegiatan->tanggal_selesai->toDateString();

            if ($hariIni < $tglMulai) {
                $statusAbsen = 'belum_mulai';
            } elseif ($hariIni > $tglSelesai) {
                $statusAbsen = 'tutup';
            } else {
                $statusAbsen = 'buka';
            }
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

        return view('admin.absensi.scan', compact('kegiatan', 'jadwal_harian', 'daftarKegiatan', 'statusAbsen', 'hariIni'));
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
                'jam'        => optional($a->waktu_scan)->format('H:i'),
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
        $hariIni = now()->toDateString();

        if ($hariIni < $tanggalDipilih) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Belum bisa absen! Jadwal absensi untuk hari ini belum dimulai.'
            ]);
        }

        if ($tanggalDipilih < $kegiatan->tanggal_mulai->toDateString() || $tanggalDipilih > $kegiatan->tanggal_selesai->toDateString()) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Tanggal terpilih di luar rentang kegiatan.'
            ]);
        }

        if ($hariIni > $kegiatan->tanggal_selesai->toDateString()) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Absensi untuk kegiatan ini sudah ditutup.'
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

    public function exportExcel(Request $request, Kegiatan $kegiatan)
    {
        $tanggal = $request->query('tanggal');

        $query = Absensi::with(['pendaftaran.peserta'])
            ->whereHas('pendaftaran', fn ($q) => $q->where('kegiatan_id', $kegiatan->id));

        if ($tanggal) {
            $query->where('tanggal_hadir', $tanggal);
        }

        $data = $query->orderBy('tanggal_hadir')->orderBy('waktu_scan')->get();

        $namaFile = 'Data_Kehadiran_' . Str::slug($kegiatan->nama) . ($tanggal ? '_' . $tanggal : '') . '.xls';

        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>';
        $html .= '<table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif;">';
        
        $html .= '<thead>';
        $html .= '<tr><th colspan="10" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #f3f4f6; padding: 10px;">Laporan Kehadiran Peserta - ' . htmlspecialchars($kegiatan->nama) . '</th></tr>';
        
        if ($tanggal) {
            $tanggalFormat = \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
            $html .= '<tr><th colspan="10" style="text-align: center; background-color: #f3f4f6; padding: 5px;">Tanggal Absensi: ' . $tanggalFormat . '</th></tr>';
        }
        
        $html .= '<tr><th colspan="10"></th></tr>';

        $headers = [
            'No', 'No. Pendaftaran', 'Nama dan Gelar', 'NIP', 'Pangkat / Golongan', 
            'Jabatan', 'Unit Kerja', 'Email', 'Tanggal Hadir', 'Waktu Scan (WIB)'
        ];

        $html .= '<tr>';
        foreach ($headers as $head) {
            $html .= '<th style="background-color: #0F2A43; color: #ffffff; font-weight: bold; text-align: center; padding: 8px;">' . $head . '</th>';
        }
        $html .= '</tr>';
        $html .= '</thead>';
        
        $html .= '<tbody>';
        foreach ($data as $i => $a) {
            $peserta = $a->pendaftaran->peserta;

            $html .= '<tr>';
            $html .= '<td style="text-align: center; padding: 5px;">' . ($i + 1) . '</td>';
            $html .= '<td style="padding: 5px;">' . htmlspecialchars($a->pendaftaran->nomor_pendaftaran) . '</td>';
            $html .= '<td style="padding: 5px;">' . htmlspecialchars($a->pendaftaran->nama_gelar) . '</td>';
            $html .= '<td style="mso-number-format:\'\@\'; padding: 5px;">' . htmlspecialchars($a->pendaftaran->nip) . '</td>';
            $html .= '<td style="padding: 5px;">' . htmlspecialchars($peserta->pangkat_golongan ?? '-') . '</td>';
            $html .= '<td style="padding: 5px;">' . htmlspecialchars($a->pendaftaran->jabatan) . '</td>';
            $html .= '<td style="padding: 5px;">' . htmlspecialchars($a->pendaftaran->unit_kerja) . '</td>';
            $html .= '<td style="padding: 5px;">' . htmlspecialchars($peserta->email ?? '-') . '</td>';
            $html .= '<td style="text-align: center; padding: 5px;">' . optional($a->tanggal_hadir)->translatedFormat('d F Y') . '</td>';
            
            // Format jam langsung tanpa konversi timezone tambahan agar konsisten dengan tampilan web
            $html .= '<td style="text-align: center; padding: 5px;">' . optional($a->waktu_scan)->format('H:i:s') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table></body></html>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"');
    }
}