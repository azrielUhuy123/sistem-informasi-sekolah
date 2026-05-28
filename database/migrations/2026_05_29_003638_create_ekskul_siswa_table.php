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
        Schema::create('ekskul_siswa', function (Blueprint $table) {

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

        $table->enum('status', [

            'pending',
            'aktif',
            'keluar'

        ])->default('aktif');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekskul_siswa');
    }
};
