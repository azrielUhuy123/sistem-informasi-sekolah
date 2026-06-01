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
        Schema::create('pengajuan_jadwal_ekskul', function (Blueprint $table) {

            $table->id();

            $table->foreignId('ekskul_id')
                ->constrained('ekskuls')
                ->cascadeOnDelete();

            $table->foreignId('guru_id')
                ->constrained('guru')
                ->cascadeOnDelete();

            $table->foreignId('hari_id')
                ->constrained('hari_sekolah')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            $table->time('jam_mulai');

            $table->time('jam_selesai');

            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'rejected',
                'published'
            ])->default('draft');

            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_jadwal_ekskul');
    }
};
