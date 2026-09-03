<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('waktu')->nullable();
            $table->string('lokasi');
            $table->unsignedInteger('kuota')->default(40);
            $table->enum('status', ['dibuka', 'ditutup', 'selesai'])->default('dibuka');
            $table->string('nomor_surat_dasar')->nullable();
            $table->date('tanggal_surat_dasar')->nullable();
            $table->string('nama_panitia')->nullable();
            $table->string('nip_panitia')->nullable();
            $table->string('nama_sekretaris')->nullable();
            $table->string('nip_sekretaris')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};