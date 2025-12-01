<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['mapel_id']);

            // Tambahkan foreign key yang benar
            $table->foreign('mapel_id')
                ->references('id')
                ->on('mata_pelajaran')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['mapel_id']);

            $table->foreign('mapel_id')
                ->references('id')
                ->on('mapel'); // balik ke salah (untuk rollback)
        });
    }
};
