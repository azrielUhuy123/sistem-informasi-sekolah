<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelas_id');
            $table->foreign('kelas_id', 'jadwal_new_kelas_id_foreign')
                ->references('id')
                ->on('kelas')
                ->cascadeOnDelete();
            $table->foreignId('hari_id')->constrained('hari_sekolah')->cascadeOnDelete();
            $table->foreignId('jam_pelajaran_id')->constrained('jam_pelajaran')->cascadeOnDelete();

            $table->foreignId('mapel_id');
            $table->foreign('mapel_id', 'jadwal_new_mapel_id_foreign')
                ->references('id')
                ->on('mata_pelajaran')
                ->cascadeOnDelete();
            $table->foreignId('guru_id');
            $table->foreign('guru_id', 'jadwal_new_guru_id_foreign')
                ->references('id')
                ->on('guru')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'kelas_id',
                'hari_id',
                'jam_pelajaran_id'
            ], 'unique_jadwal_kelas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};