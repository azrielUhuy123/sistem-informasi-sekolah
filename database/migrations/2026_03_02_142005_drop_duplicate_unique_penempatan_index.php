<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penempatan_siswa', function (Blueprint $table) {
            $table->dropUnique('unique_penempatan_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('penempatan_siswa', function (Blueprint $table) {
            $table->unique(
                ['siswa_id', 'tahun_ajaran_id', 'tanggal_keluar'],
                'unique_penempatan_aktif'
            );
        });
    }
};