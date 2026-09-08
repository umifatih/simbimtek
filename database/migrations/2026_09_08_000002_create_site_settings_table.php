<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->string('nama_aplikasi')->default('SIMBIMTEK');
            $table->string('tagline')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('tentang_program_judul')->nullable();
            $table->text('tentang_program_deskripsi')->nullable();
            $table->string('kenapa_simbimtek_judul')->nullable();
            $table->json('fitur')->nullable();
            $table->timestamps();
        });

        DB::table('site_settings')->insert([
            'nama_aplikasi' => 'SIMBIMTEK',
            'tagline' => 'Dari daftar sampai sertifikat, satu alur yang jelas.',
            'deskripsi' => 'SIMBIMTEK menyatukan pendaftaran, penerbitan SPPD, dan sertifikat bimbingan teknis dalam satu tempat — peserta cukup daftar sekali dengan NIP, dan data lamanya otomatis dipakai untuk kegiatan berikutnya.',
            'tentang_program_judul' => 'Bimtek Bendahara & Operator BOSP',
            'tentang_program_deskripsi' => 'Bimbingan teknis pengelolaan Bantuan Operasional Satuan Pendidikan (BOSP) bagi Bendahara dan Operator sekolah, mulai dari perencanaan anggaran, pelaporan, sampai pertanggungjawaban dana — sesuai ketentuan yang berlaku.',
            'kenapa_simbimtek_judul' => 'Semua yang dibutuhkan peserta, tanpa bolak-balik ke panitia',
            'fitur' => json_encode([
                ['judul' => 'Pendaftaran dengan NIP', 'desc' => 'Isi NIP saja — kalau sudah pernah daftar, data lama otomatis terisi.'],
                ['judul' => 'Cetak Dokumen Otomatis', 'desc' => 'Bukti daftar, SPPD, dan sertifikat tersedia dalam format siap cetak.'],
                ['judul' => 'Absensi QR Code', 'desc' => 'Kehadiran tercatat begitu QR dipindai di lokasi kegiatan.'],
                ['judul' => 'Cek Status Real-time', 'desc' => 'Lihat status pendaftaran, SPPD, dan sertifikat kapan pun tanpa perlu bertanya.'],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};