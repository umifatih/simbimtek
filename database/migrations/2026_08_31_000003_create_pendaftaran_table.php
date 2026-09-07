<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->cascadeOnDelete();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('token_kehadiran', 32)->unique(); 
            $table->enum('status', ['daftar', 'sppd', 'sertifikat'])->default('daftar');
            $table->timestamp('hadir_pada')->nullable(); 
            $table->timestamps();
            $table->unique(['peserta_id', 'kegiatan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};