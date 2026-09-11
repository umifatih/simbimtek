<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\MateriKegiatan;
use App\Models\Pendaftaran;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdminSertifikatController extends Controller
{
    public function index(Request $request)
    {
        $setting = SiteSetting::current();

        $kegiatanList = Kegiatan::orderByDesc('tanggal_mulai')->get();

        $kegiatanId = $request->query('kegiatan_id', optional($kegiatanList->first())->id);

        $pendaftaranList = collect();
        $materiList = collect();

        if ($kegiatanId) {
            $pendaftaranList = Pendaftaran::with(['peserta', 'kegiatan'])
                ->where('kegiatan_id', $kegiatanId)
                ->get()
                ->filter(fn (Pendaftaran $p) => $p->absensiLengkap())
                ->values();

            $materiList = MateriKegiatan::where('kegiatan_id', $kegiatanId)
                ->orderBy('urutan')
                ->get();
        }

        return view('admin.sertifikat.index', compact(
            'setting',
            'kegiatanList',
            'kegiatanId',
            'pendaftaranList',
            'materiList'
        ));
    }

    public function updateKetua(Request $request)
    {
        $data = $request->validate([
            'nama_ketua_k3s' => ['nullable', 'string', 'max:100'],
            'nip_ketua_k3s' => ['nullable', 'string', 'max:20'],
        ]);

        SiteSetting::current()->update($data);

        return back()->with('status_ketua', 'Nama & NIP Ketua K3S berhasil disimpan.');
    }

    public function storeMateri(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => ['required', 'exists:kegiatan,id'],
            'nama_materi' => ['required', 'string', 'max:255'],
            'urutan'      => ['required', 'integer', 'min:1'],
            'jumlah_jp'   => ['required', 'integer', 'min:1'],
        ]);

        MateriKegiatan::create($validated);

        return back()
            ->with('status_materi', 'Materi berhasil ditambahkan.')
            ->withInput(['kegiatan_id' => $validated['kegiatan_id']]);
    }

    public function destroyMateri(MateriKegiatan $materi)
    {
        $kegiatanId = $materi->kegiatan_id;
        $materi->delete();

        return redirect()
            ->route('admin.sertifikat.index', ['kegiatan_id' => $kegiatanId])
            ->with('status_materi', 'Materi berhasil dihapus.');
    }
}