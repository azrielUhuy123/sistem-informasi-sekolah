<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru_mapel', function (Blueprint $table) {

            $table->dropForeign(['mata_pelajaran_id']);

            $table->renameColumn(
                'mata_pelajaran_id',
                'mapel_id'
            );

            $table->foreign('mapel_id')
                ->references('id')
                ->on('mata_pelajaran')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('guru_mapel', function (Blueprint $table) {

            $table->dropForeign(['mapel_id']);

            $table->renameColumn(
                'mapel_id',
                'mata_pelajaran_id'
            );

            $table->foreign('mata_pelajaran_id')
                ->references('id')
                ->on('mata_pelajaran')
                ->cascadeOnDelete();

        });
    }
};