<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $duplicate = DB::table('ekskul_siswa')
            ->select('ekskul_id', 'siswa_id', 'tahun_ajaran_id')
            ->groupBy('ekskul_id', 'siswa_id', 'tahun_ajaran_id')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicate) {
            throw new RuntimeException(
                'Duplicate data found in ekskul_siswa for the same ekskul_id + siswa_id + tahun_ajaran_id. Resolve the duplicates manually before adding the unique constraint.'
            );
        }

        Schema::table('ekskul_siswa', function (Blueprint $table) {
            $table->unique(
                ['ekskul_id', 'siswa_id', 'tahun_ajaran_id'],
                'ekskul_siswa_unique_membership'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ekskul_siswa', function (Blueprint $table) {
            $table->dropUnique('ekskul_siswa_unique_membership');
        });
    }
};
