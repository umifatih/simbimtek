<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\CarbonPeriod;

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

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }


    public function absensiLengkap(): bool
    {
        if (! $this->kegiatan?->tanggal_mulai || ! $this->kegiatan?->tanggal_selesai) {
            return false;
        }

        $periode = CarbonPeriod::create(
            $this->kegiatan->tanggal_mulai,
            $this->kegiatan->tanggal_selesai
        );

        $tanggalWajib = collect($periode)->map(fn ($t) => $t->toDateString());

        $tanggalHadir = $this->absensi()
            ->pluck('tanggal_hadir')
            ->map(fn ($t) => $t->toDateString());

        return $tanggalWajib->diff($tanggalHadir)->isEmpty();
    }

    public function progresAbsensi(): array
    {
        if (! $this->kegiatan?->tanggal_mulai || ! $this->kegiatan?->tanggal_selesai) {
            return ['hadir' => 0, 'wajib' => 0];
        }

        $periode = CarbonPeriod::create(
            $this->kegiatan->tanggal_mulai,
            $this->kegiatan->tanggal_selesai
        );

        return [
            'hadir' => $this->absensi()->count(),
            'wajib' => collect($periode)->count(),
        ];
    }


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

    public function getNipAttribute()
    {
        return $this->peserta?->nip;
    }

    public function getNamaGelarKepsekAttribute()
    {
        return $this->peserta?->nama_gelar_kepsek;
    }

    public function getNipKepsekAttribute()
    {
        return $this->peserta?->nip_kepsek;
    }

    public function getEmailAttribute()
    {
        return $this->peserta?->email;
    }
}