<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->date('tanggal_hadir');
            $table->timestamp('waktu_scan');
            $table->timestamps();

            // satu peserta hanya bisa absen sekali per tanggal
            $table->unique(['pendaftaran_id', 'tanggal_hadir']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};