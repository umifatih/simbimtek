<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\MateriKegiatan;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AdminSertifikatController extends Controller
{
    public function index(Request $request)
    {
        $kegiatanList = Kegiatan::orderByDesc('tanggal_mulai')->get();

        $kegiatanId = $request->query('kegiatan_id', optional($kegiatanList->first())->id);

        $kegiatanTerpilih = $kegiatanList->firstWhere('id', $kegiatanId);

        $pendaftaranList = collect();

        if ($kegiatanId) {
            $pendaftaranList = Pendaftaran::with(['peserta', 'kegiatan'])
                ->where('kegiatan_id', $kegiatanId)
                ->get()
                ->filter(fn (Pendaftaran $p) => $p->absensiLengkap())
                ->values();
        }

        $materiList = MateriKegiatan::where('kegiatan_id', $kegiatanId)
            ->orderBy('urutan')
            ->get();

        return view('admin.sertifikat.index', compact(
            'kegiatanList', 'kegiatanId', 'kegiatanTerpilih', 'pendaftaranList', 'materiList'
        ));
    }

    public function updatePanitia(Request $request, Kegiatan $kegiatan)
    {
        $data = $request->validate([
            'nama_panitia' => ['nullable', 'string', 'max:255'],
            'nip_panitia'  => ['nullable', 'string', 'max:30'],
        ]);

        $kegiatan->update($data);

        return redirect()
            ->route('admin.sertifikat.index', ['kegiatan_id' => $request->input('kegiatan_id', $kegiatan->id)])
            ->with('status_panitia', 'Nama & NIP Ketua Panitia berhasil disimpan.');
    }

    public function storeMateri(Request $request)
    {
        $data = $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatan,id'],
            'nama_materi' => ['required', 'string', 'max:255'],
            'urutan'      => ['required', 'integer', 'min:1'],
            'jumlah_jp'   => ['required', 'integer', 'min:1'],
        ]);

        MateriKegiatan::create($data);

        return redirect()
            ->route('admin.sertifikat.index', ['kegiatan_id' => $data['kegiatan_id']])
            ->with('status_materi', 'Materi kegiatan berhasil ditambahkan.');
    }

    public function destroyMateri(MateriKegiatan $materi)
    {
        $kegiatanId = $materi->kegiatan_id;
        $materi->delete();

        return redirect()
            ->route('admin.sertifikat.index', ['kegiatan_id' => $kegiatanId])
            ->with('status_materi', 'Materi kegiatan berhasil dihapus.');
    }
}