<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const CLASS_GROUP_INDEX = 'kelas_grup_angkatan_master_unique';
    private const CLASS_NAME_INDEX = 'kelas_group_name_unique';

    public function up(): void
    {
        $duplicateGroup = DB::table('kelas_grup')
            ->whereNotNull('angkatan_id')
            ->whereNotNull('kelas_master_id')
            ->select('angkatan_id', 'kelas_master_id')
            ->groupBy('angkatan_id', 'kelas_master_id')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicateGroup) {
            throw new RuntimeException(
                'Duplicate kelas_grup records found for angkatan_id + kelas_master_id.'
            );
        }

        $duplicateClass = DB::table('kelas')
            ->whereNotNull('kelas_grup_id')
            ->whereNotNull('nama_kelas')
            ->select('kelas_grup_id', 'nama_kelas')
            ->groupBy('kelas_grup_id', 'nama_kelas')
            ->havingRaw('COUNT(*) > 1')
            ->first();

        if ($duplicateClass) {
            throw new RuntimeException(
                'Duplicate kelas records found for kelas_grup_id + nama_kelas.'
            );
        }

        Schema::table('kelas_grup', function (Blueprint $table) {
            $table->unique(
                ['angkatan_id', 'kelas_master_id'],
                self::CLASS_GROUP_INDEX
            );
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->unique(
                ['kelas_grup_id', 'nama_kelas'],
                self::CLASS_NAME_INDEX
            );
        });
    }

    public function down(): void
    {
        if (Schema::hasIndex('kelas', self::CLASS_NAME_INDEX)) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropUnique(self::CLASS_NAME_INDEX);
            });
        }

        if (Schema::hasIndex('kelas_grup', self::CLASS_GROUP_INDEX)) {
            Schema::table('kelas_grup', function (Blueprint $table) {
                $table->dropUnique(self::CLASS_GROUP_INDEX);
            });
        }
    }
};