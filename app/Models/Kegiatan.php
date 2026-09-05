<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu',
        'lokasi',
        'kuota',
        'status',
        'nomor_surat_dasar',
        'tanggal_surat_dasar',
        'nama_panitia',
        'nip_panitia',
        'nama_sekretaris',
        'nip_sekretaris',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_surat_dasar' => 'date',
    ];

    protected $appends = ['kuota_terisi', 'teks_jadwal', 'hari_tanggal', 'status_efektif'];

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function getKuotaTerisiAttribute(): int
    {
        return $this->pendaftaran()->count();
    }

    public function getTeksJadwalAttribute(): string
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return '-';
        }

        $mulai = $this->tanggal_mulai->locale('id');
        $selesai = $this->tanggal_selesai->locale('id');

        if ($mulai->isSameDay($selesai)) {
            return $mulai->translatedFormat('d F Y');
        }

        if ($mulai->isSameMonth($selesai)) {
            return $mulai->format('d') . ' - ' . $selesai->translatedFormat('d F Y');
        }

        if ($mulai->isSameYear($selesai)) {
            return $mulai->translatedFormat('d F') . ' - ' . $selesai->translatedFormat('d F Y');
        }

        return $mulai->translatedFormat('d F Y') . ' - ' . $selesai->translatedFormat('d F Y');
    }

    public function getHariTanggalAttribute(): string
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return '-';
        }

        $mulai = $this->tanggal_mulai->locale('id');
        $selesai = $this->tanggal_selesai->locale('id');

        if ($mulai->isSameDay($selesai)) {
            return $mulai->translatedFormat('l, d F Y');
        }

        return $mulai->translatedFormat('l') . '-' . $selesai->translatedFormat('l') . ', ' . $this->teks_jadwal;
    }

    public function getStatusEfektifAttribute(): string
{
    // Admin override manual selalu menang duluan
    if ($this->status === 'selesai') {
        return 'selesai';
    }

    if ($this->tanggal_selesai && $this->tanggal_selesai->lt(now()->startOfDay())) {
        return 'selesai';
    }

    if ($this->status === 'ditutup') {
        return 'ditutup';
    }

    if ($this->kuota > 0 && $this->kuota_terisi >= $this->kuota) {
        return 'ditutup';
    }

    return 'dibuka';
}
}