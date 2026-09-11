<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(1);
            $table->string('nama_materi');
            $table->unsignedInteger('jumlah_jp')->default(0); // jumlah jam pelajaran
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_kegiatan');
    }
};