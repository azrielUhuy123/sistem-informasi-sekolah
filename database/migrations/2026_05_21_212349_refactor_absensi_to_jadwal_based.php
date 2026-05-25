<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_siswa', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | JADWAL
            |--------------------------------------------------------------------------
            */

            $table->foreignId('jadwal_id')
                ->nullable()
                ->after('id')
                ->constrained('pengajuan_jadwal')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL
            |--------------------------------------------------------------------------
            */

            if (
                Schema::hasColumn(
                    'absensi_siswa',
                    'mapel_id'
                )
            ) {

                $table->dropForeign(['mapel_id']);

                $table->dropColumn('mapel_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_siswa', function (Blueprint $table) {

            $table->dropForeign(['jadwal_id']);

            $table->dropColumn('jadwal_id');
        });
    }
};