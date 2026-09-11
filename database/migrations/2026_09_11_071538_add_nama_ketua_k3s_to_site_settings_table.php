<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('site_settings', 'nama_ketua_k3s')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->string('nama_ketua_k3s')->nullable()->after('nama_aplikasi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('site_settings', 'nama_ketua_k3s')) {
            Schema::table('site_settings', function (Blueprint $table) {
                $table->dropColumn('nama_ketua_k3s');
            });
        }
    }
};