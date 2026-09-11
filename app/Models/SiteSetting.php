<?php
// app/Models/SiteSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'logo_path',
        'nama_aplikasi',
        'nama_ketua_k3s',
        'nip_ketua_k3s',
        'tagline',
        'deskripsi',
        'tentang_program_judul',
        'tentang_program_deskripsi',
        'kenapa_simbimtek_judul',
        'fitur',
    ];

    protected $casts = [
        'fitur' => 'array',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create(['nama_aplikasi' => 'SIMBIMTEK']);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }
}