<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const INDEX_NAME = 'ppdb_settings_tahun_ajaran_unique';

    public function up(): void
    {
        $duplicate = DB::table('ppdb_settings')
            ->select('tahun_ajaran_id')
            ->selectRaw('COUNT(*) AS duplicate_count')
            ->groupBy('tahun_ajaran_id')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicate) {
            throw new RuntimeException(
                sprintf(
                    'Cannot add %s: duplicate ppdb_settings rows exist for tahun_ajaran_id %s (%s rows). Resolve duplicates manually before retrying.',
                    self::INDEX_NAME,
                    $duplicate->tahun_ajaran_id,
                    $duplicate->duplicate_count
                )
            );
        }

        Schema::table('ppdb_settings', function (Blueprint $table) {
            $table->unique('tahun_ajaran_id', self::INDEX_NAME);
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_settings', function (Blueprint $table) {
            $table->dropUnique(self::INDEX_NAME);
        });
    }
};