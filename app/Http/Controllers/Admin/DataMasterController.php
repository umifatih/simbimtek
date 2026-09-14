<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataMaster;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataMasterController extends Controller
{
    public function index(Request $request)
    {
        $cari = trim((string) $request->get('cari'));

        $dataMasterList = DataMaster::when($cari, function ($q) use ($cari) {
                $q->where('unit_kerja', 'like', "%{$cari}%")
                  ->orWhere('nama_kepsek', 'like', "%{$cari}%")
                  ->orWhere('desa', 'like', "%{$cari}%");
            })
            ->orderBy('unit_kerja')
            ->paginate(20)
            ->withQueryString();

        return view('admin.data-master.index', compact('dataMasterList', 'cari'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_master' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ]);

        $path = $request->file('file_master')->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $baris = $sheet->toArray(null, true, true, false); // array indeks numerik per baris

        // Cari baris header: baris pertama yang mengandung kolom "UNIT KERJA"
        $indexHeader = null;
        $petaKolom = [];
        foreach ($baris as $i => $row) {
            $kolomDitemukan = $this->petakanHeader($row);
            if (isset($kolomDitemukan['unit_kerja'])) {
                $indexHeader = $i;
                $petaKolom = $kolomDitemukan;
                break;
            }
        }

        if ($indexHeader === null) {
            return back()->withErrors([
                'file_master' => 'Kolom "UNIT KERJA" tidak ditemukan di file. Pastikan file punya baris judul kolom seperti pada template.',
            ]);
        }

        $diimpor = 0;
        $dilewati = 0;

        for ($i = $indexHeader + 1; $i < count($baris); $i++) {
            $row = $baris[$i];
            $unitKerja = trim((string) ($row[$petaKolom['unit_kerja']] ?? ''));

            if ($unitKerja === '') {
                $dilewati++;
                continue;
            }

            DataMaster::updateOrCreate(
                ['unit_kerja' => $unitKerja],
                [
                    'data_sekolah' => isset($petaKolom['data_sekolah'])
                        ? trim((string) ($row[$petaKolom['data_sekolah']] ?? '')) ?: strtoupper($unitKerja)
                        : strtoupper($unitKerja),
                    'nama_kepsek' => isset($petaKolom['nama_kepsek'])
                        ? trim((string) ($row[$petaKolom['nama_kepsek']] ?? ''))
                        : null,
                    'nip_kepsek' => isset($petaKolom['nip_kepsek'])
                        ? trim((string) ($row[$petaKolom['nip_kepsek']] ?? '')) ?: null
                        : null,
                    'desa' => isset($petaKolom['desa'])
                        ? trim((string) ($row[$petaKolom['desa']] ?? ''))
                        : null,
                ]
            );

            $diimpor++;
        }

        return back()->with('success', "Impor selesai: {$diimpor} sekolah tersimpan/diperbarui, {$dilewati} baris kosong dilewati.");
    }

    /** Cocokkan header kolom di satu baris terhadap nama field internal. */
    private function petakanHeader(array $row): array
    {
        $peta = [];
        foreach ($row as $kolom => $nilai) {
            $judul = strtoupper(trim((string) $nilai));
            if ($judul === '') {
                continue;
            }

            if (str_contains($judul, 'UNIT KERJA')) {
                $peta['unit_kerja'] = $kolom;
            } elseif (str_contains($judul, 'DATA SEKOLAH') || $judul === 'NAMA SEKOLAH') {
                $peta['data_sekolah'] = $kolom;
            } elseif (str_contains($judul, 'KEPALA SEKOLAH')) {
                $peta['nama_kepsek'] = $kolom;
            } elseif (str_contains($judul, 'NIP KS') || str_contains($judul, 'NIP KEPALA')) {
                $peta['nip_kepsek'] = $kolom;
            } elseif ($judul === 'DESA') {
                $peta['desa'] = $kolom;
            }
        }

        return $peta;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_sekolah' => 'nullable|string|max:255',
            'unit_kerja'   => 'required|string|max:255|unique:data_masters,unit_kerja',
            'nama_kepsek'  => 'nullable|string|max:255',
            'nip_kepsek'   => 'nullable|string|max:30',
            'desa'         => 'nullable|string|max:255',
        ]);

        $dataMaster = DataMaster::create($validated);

        return response()->json(['sukses' => true, 'data' => $dataMaster]);
    }

    public function update(Request $request, DataMaster $dataMaster)
    {
        $validated = $request->validate([
            'data_sekolah' => 'nullable|string|max:255',
            'unit_kerja'   => 'required|string|max:255|unique:data_masters,unit_kerja,' . $dataMaster->id,
            'nama_kepsek'  => 'nullable|string|max:255',
            'nip_kepsek'   => 'nullable|string|max:30',
            'desa'         => 'nullable|string|max:255',
        ]);

        $dataMaster->update($validated);

        return response()->json(['sukses' => true, 'data' => $dataMaster]);
    }

    public function destroy(DataMaster $dataMaster)
    {
        $dataMaster->delete();

        return response()->json(['sukses' => true]);
    }

    public function unduhTemplate()
    {
        $isi = "DATA SEKOLAH,UNIT KERJA,Kepala Sekolah,NIP KS,DESA\n";
        $isi .= "SD NEGERI 1 CONTOH,SD Negeri 1 Contoh,Nama Kepala Sekolah S.Pd,198000000000000000,Nama Desa\n";

        return response($isi, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template-data-master.csv"',
        ]);
    }

    public function rapikan()
    {
        $jumlah = 0;

        DataMaster::chunkById(50, function ($daftar) use (&$jumlah) {
            foreach ($daftar as $item) {
                // Set ulang atribut yang sama supaya mutator setNamaKepsekAttribute
                // & setNipKepsekAttribute di model dijalankan lagi.
                $item->nama_kepsek = $item->nama_kepsek;
                $item->nip_kepsek = $item->nip_kepsek;
                $item->save();
                $jumlah++;
            }
        });

        return back()->with('success', "Rapikan data selesai: {$jumlah} baris diperbarui (nama kepala sekolah & NIP KS).");
    }
}