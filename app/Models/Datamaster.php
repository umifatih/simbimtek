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
}