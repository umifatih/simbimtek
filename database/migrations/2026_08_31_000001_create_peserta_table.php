<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama_gelar');
            $table->string('unit_kerja');
            $table->string('pangkat_golongan');
            
            // [UPDATE] Dipisah menjadi tempat_lahir dan tanggal_lahir
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            
            $table->string('jabatan'); 
            $table->string('email');
            $table->string('nama_gelar_kepsek');
            $table->string('nip_kepsek')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta');
    }
};