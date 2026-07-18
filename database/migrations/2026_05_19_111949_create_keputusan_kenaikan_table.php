<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keputusan_kenaikan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->cascadeOnDelete();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->cascadeOnDelete();

            $table->foreignId('guru_id')
                ->constrained('guru')
                ->cascadeOnDelete();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            $table->enum('status', [
                'naik',
                'tinggal'
            ]);

            $table->text('catatan')
                ->nullable();

            $table->enum('approval_status', [
                'draft',
                'pending',
                'rejected',
                'approved'
            ])->default('draft');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('catatan_kepsek')
                ->nullable();

            $table->timestamp('submitted_at')
                ->nullable();

            $table->timestamp('approved_at')
                ->nullable();
            
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keputusan_kenaikan');
    }
};