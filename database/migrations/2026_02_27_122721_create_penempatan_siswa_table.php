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
        Schema::create('penempatan_siswa', function (Blueprint $table) {
        $table->id();

        $table->foreignId('siswa_id')
            ->constrained('siswa')
            ->cascadeOnDelete();

        $table->foreignId('kelas_id')
            ->constrained('kelas')
            ->cascadeOnDelete();

        $table->foreignId('tahun_ajaran_id')
            ->constrained('tahun_ajaran')
            ->cascadeOnDelete();

        $table->date('tanggal_masuk');
        $table->date('tanggal_keluar')->nullable();

        $table->timestamps();

        // Mencegah siswa aktif di 2 kelas pada tahun ajaran yang sama
        $table->unique(
            ['siswa_id', 'tahun_ajaran_id', 'tanggal_keluar'],
            'unique_penempatan_aktif'
        );
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatan_siswa');
    }
};
