<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
    'nip',
    'nama_gelar',
    'unit_kerja',
    'pangkat_golongan',
    'tempat_lahir',   
    'tanggal_lahir',   
    'jabatan',
    'email',
    'nama_gelar_kepsek',
    'nip_kepsek',
];

protected $casts = [
    'tanggal_lahir' => 'date',
];

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }
}