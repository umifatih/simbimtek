<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMaster extends Model
{
    protected $table = 'data_masters';

    protected $fillable = [
        'data_sekolah',
        'unit_kerja',
        'nama_kepsek',
        'nip_kepsek',
        'desa',
    ];


    public function setNamaKepsekAttribute($value): void
    {
        $nama = trim((string) $value);

        if ($nama === '' || $nama === '-') {
            $this->attributes['nama_kepsek'] = $nama === '' ? null : $nama;
            return;
        }

        // pastikan ada spasi setelah koma
        $nama = preg_replace('/,\s*/', ', ', $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);

        $kataKata = explode(' ', $nama);

        foreach ($kataKata as $i => $kata) {
            if ($kata === '') {
                continue;
            }

            // token gelar/singkatan (mengandung titik) tidak diubah
            if (str_contains($kata, '.')) {
                continue;
            }

            $komaDiAkhir = str_ends_with($kata, ',');
            $inti = $komaDiAkhir ? substr($kata, 0, -1) : $kata;

            // hanya rapikan token yang murni huruf/apostrof/strip (bagian nama asli)
            if ($inti !== '' && preg_match('/^[A-Za-z\'\-]+$/u', $inti)) {
                $inti = mb_convert_case(mb_strtolower($inti, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            }

            $kataKata[$i] = $inti . ($komaDiAkhir ? ',' : '');
        }

        $this->attributes['nama_kepsek'] = implode(' ', $kataKata);
    }

    /** NIP disimpan tanpa spasi sama sekali, mis. "197307 08 1998031004" -> "197307081998031004". */
    public function setNipKepsekAttribute($value): void
    {
        $nip = trim((string) $value);

        if ($nip === '' || $nip === '-') {
            $this->attributes['nip_kepsek'] = null;
            return;
        }

        $this->attributes['nip_kepsek'] = preg_replace('/\s+/', '', $nip);
    }
}