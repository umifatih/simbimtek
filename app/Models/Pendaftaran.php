<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'peserta_id',
        'kegiatan_id',
        'nomor_pendaftaran',
        'token_kehadiran',
        'status',
        'hadir_pada',
    ];

    protected $casts = [
        'hadir_pada' => 'datetime',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    /**
     * Accessor kompatibilitas: view lama (daftar/sukses, status/index, sertifikat/index, dll)
     * sudah ditulis mengakses $pendaftaran->nama_gelar / ->unit_kerja / ->jabatan langsung
     * (bukan lewat ->peserta->...). Daripada mengubah semua view, alias-kan di sini.
     */
    public function getNamaGelarAttribute()
    {
        return $this->peserta?->nama_gelar;
    }

    public function getUnitKerjaAttribute()
    {
        return $this->peserta?->unit_kerja;
    }

    public function getJabatanAttribute()
    {
        return $this->peserta?->jabatan;
    }
}