<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_masters', function (Blueprint $table) {
            $table->id();
            $table->string('data_sekolah')->nullable(); // nama sekolah versi KAPITAL, dipakai di kop surat
            $table->string('unit_kerja')->unique();      // nama sekolah versi title-case, dipakai di dropdown & disimpan ke peserta
            $table->string('nama_kepsek')->nullable();
            $table->string('nip_kepsek', 30)->nullable();
            $table->string('desa')->nullable();
            $table->timestamps();

            $table->index('unit_kerja');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_masters');
    }
};