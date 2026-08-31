<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PendaftaranController extends Controller
{
    /**
     * AJAX: dipanggil dari form pendaftaran begitu peserta selesai mengetik NIP.
     * Kalau NIP sudah pernah dipakai daftar sebelumnya, kembalikan data dirinya
     * supaya form bisa diisi otomatis di sisi klien.
     */
    public function cariNip(string $nip)
    {
        $peserta = Peserta::where('nip', $nip)->first();

        if (! $peserta) {
            return response()->json(['ditemukan' => false]);
        }

        return response()->json([
            'ditemukan' => true,
            'data' => [
                'unit_kerja'           => $peserta->unit_kerja,
                'nama_gelar'           => $peserta->nama_gelar,
                'pangkat_golongan'     => $peserta->pangkat_golongan,
                'tempat_tanggal_lahir' => $peserta->tempat_tanggal_lahir,
                'jabatan'              => $peserta->jabatan,
                'email'                => $peserta->email,
                'nama_gelar_kepsek'    => $peserta->nama_gelar_kepsek,
                'nip_kepsek'           => $peserta->nip_kepsek,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id'          => 'required|exists:kegiatan,id',
            'unit_kerja'           => 'required|string|max:255',
            'nama_gelar'           => 'required|string|max:255',
            'nip'                  => 'required|string|max:30',
            'pangkat_golongan'     => 'required|string|max:100',
            'tempat_tanggal_lahir' => 'required|string|max:100',
            'jabatan'              => 'required|in:Bendahara BOSP,Operator BOSP',
            'email'                => 'required|email|max:255',
            'nama_gelar_kepsek'    => 'required|string|max:255',
            'nip_kepsek'           => 'nullable|string|max:30',
            'setuju'               => 'required',
        ]);

        // Cari-atau-buat profil peserta berdasarkan NIP. Kalau sudah ada, datanya
        // ditimpa dengan yang baru dikirim (jaga-jaga kalau memang ada perubahan,
        // mis. pindah unit kerja atau ganti jabatan).
        $peserta = Peserta::updateOrCreate(
            ['nip' => $validated['nip']],
            [
                'nama_gelar'           => $validated['nama_gelar'],
                'unit_kerja'           => $validated['unit_kerja'],
                'pangkat_golongan'     => $validated['pangkat_golongan'],
                'tempat_tanggal_lahir' => $validated['tempat_tanggal_lahir'],
                'jabatan'              => $validated['jabatan'],
                'email'                => $validated['email'],
                'nama_gelar_kepsek'    => $validated['nama_gelar_kepsek'],
                'nip_kepsek'           => $validated['nip_kepsek'] ?? null,
            ]
        );

        // Cegah daftar dobel untuk kegiatan yang sama (constraint unique di DB juga jaga ini)
        $sudahDaftar = Pendaftaran::where('peserta_id', $peserta->id)
            ->where('kegiatan_id', $validated['kegiatan_id'])
            ->first();

        if ($sudahDaftar) {
            return back()->withErrors(['kegiatan_id' => 'Kamu sudah terdaftar di kegiatan ini sebelumnya.']);
        }

        $tahun = now()->format('Y');
        $urutan = Pendaftaran::whereYear('created_at', $tahun)->count() + 1;

        $pendaftaran = Pendaftaran::create([
            'peserta_id' => $peserta->id,
            'kegiatan_id' => $validated['kegiatan_id'],
            'nomor_pendaftaran' => "BT-{$tahun}-" . str_pad($urutan, 4, '0', STR_PAD_LEFT),
            'token_kehadiran' => Str::random(32),
            'status' => 'daftar',
        ]);

        $pendaftaran->load(['peserta', 'kegiatan']);

        return view('daftar.sukses', compact('pendaftaran'));
    }

    public function unduh(Pendaftaran $pendaftaran, string $jenis)
    {
        // TODO: generate PDF asli dari template pakai fpdi, isi dari
        // $pendaftaran->peserta dan $pendaftaran->kegiatan.
        return "Placeholder unduh dokumen: {$jenis} untuk {$pendaftaran->nomor_pendaftaran}";
    }
    public function create()
    {
        $kegiatanList = Kegiatan::where('status', 'buka')->get();

        return view('daftar.index', compact('kegiatanList'));
}
}