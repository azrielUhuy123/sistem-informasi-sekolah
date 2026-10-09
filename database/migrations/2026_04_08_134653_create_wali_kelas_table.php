<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexes = Schema::getIndexes('wali_kelas');
        $hasUniqueKelasIndex = false;

        foreach ($indexes as $index) {
            if (
                $index['name'] === 'wali_kelas_kelas_id_unique'
                && (!$index['unique'] || $index['columns'] !== ['kelas_id'])
            ) {
                throw new RuntimeException('Index wali_kelas_kelas_id_unique has an unexpected definition.');
            }

            if ($index['unique'] && $index['columns'] === ['kelas_id']) {
                $hasUniqueKelasIndex = true;
            }
        }

        if (!$hasUniqueKelasIndex) {
            Schema::table('wali_kelas', function (Blueprint $table) {
                $table->unique('kelas_id', 'wali_kelas_kelas_id_unique');
            });
        }

        if (Schema::hasIndex('wali_kelas', 'wali_kelas_kelas_id_foreign')) {
            Schema::table('wali_kelas', function (Blueprint $table) {
                $table->dropIndex('wali_kelas_kelas_id_foreign');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasIndex('wali_kelas', 'wali_kelas_kelas_id_foreign')) {
            Schema::table('wali_kelas', function (Blueprint $table) {
                $table->index('kelas_id', 'wali_kelas_kelas_id_foreign');
            });
        }

        if (Schema::hasIndex('wali_kelas', 'wali_kelas_kelas_id_unique', 'unique')) {
            Schema::table('wali_kelas', function (Blueprint $table) {
                $table->dropUnique('wali_kelas_kelas_id_unique');
            });
        }
    }
};
