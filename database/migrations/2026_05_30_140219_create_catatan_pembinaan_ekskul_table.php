<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_pembinaan_ekskul', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekskul_id')
                ->constrained('ekskuls')
                ->cascadeOnDelete();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            $table->text('kehadiran')->nullable();

            $table->text('partisipasi')->nullable();

            $table->text('perkembangan')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'catatan_pembinaan_ekskul'
        );
    }
};