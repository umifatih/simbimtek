<?php
// app/Models/SiteSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;

class SiteSetting extends Model
{
    use LogsActivity;

    protected $fillable = [
        'logo_path',
        'nama_aplikasi',
        'tagline',
        'deskripsi',
        'tentang_program_judul',
        'tentang_program_deskripsi',
        'syarat_judul',
        'syarat_list',
        'kenapa_simbimtek_judul',
        'fitur',
    ];

    protected $casts = [
        'fitur' => 'array',
        'syarat_list' => 'array',
    ];

    public function labelAktivitas(): string
    {
        return 'pengaturan situs';
    }

    public static function current(): self
    {
        return static::first() ?? static::create(['nama_aplikasi' => 'SIMBIMTEK']);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }
}