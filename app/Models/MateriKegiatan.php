<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriKegiatan extends Model
{
    protected $table = 'materi_kegiatan';

    protected $fillable = [
        'kegiatan_id',
        'urutan',
        'nama_materi',
        'jumlah_jp',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}