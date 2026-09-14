<?php
// database/migrations/2026_09_09_000000_add_syarat_to_site_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('syarat_judul')->nullable()->after('tentang_program_deskripsi');
            $table->json('syarat_list')->nullable()->after('syarat_judul');
        });

        // Isi default = konten yang sekarang hardcode di view
        DB::table('site_settings')->update([
            'syarat_judul' => 'Siapa yang wajib ikut?',
            'syarat_list' => json_encode([
                'Bendahara BOSP di setiap satuan pendidikan',
                'Operator BOSP di setiap satuan pendidikan',
                'Atas penugasan dan sepengetahuan Kepala Sekolah',
            ]),
        ]);
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['syarat_judul', 'syarat_list']);
        });
    }
};