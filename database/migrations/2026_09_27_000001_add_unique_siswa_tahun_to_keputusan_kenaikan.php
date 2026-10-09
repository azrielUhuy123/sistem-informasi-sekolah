<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEX_NAME = 'keputusan_kenaikan_siswa_tahun_unique';

    public function up(): void
    {
        $duplicate = DB::table('keputusan_kenaikan')
            ->select('siswa_id', 'tahun_ajaran_id')
            ->selectRaw('COUNT(*) AS duplicate_count')
            ->groupBy('siswa_id', 'tahun_ajaran_id')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicate) {
            throw new RuntimeException(
                'Cannot add keputusan_kenaikan_siswa_tahun_unique: duplicate siswa_id + tahun_ajaran_id records exist. Resolve duplicates before retrying.'
            );
        }

        Schema::table('keputusan_kenaikan', function (Blueprint $table) {
            $table->unique(
                ['siswa_id', 'tahun_ajaran_id'],
                self::INDEX_NAME
            );
        });
    }

    public function down(): void
    {
        Schema::table('keputusan_kenaikan', function (Blueprint $table) {
            $table->dropUnique(self::INDEX_NAME);
        });
    }
};