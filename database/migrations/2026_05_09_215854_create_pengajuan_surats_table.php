<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_surats', function (Blueprint $table) {

            $table->id();

            // Pengaju
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Data Surat
            $table->string('nama_surat');

            $table->string('kategori_surat');

            $table->string('tujuan_surat')
                ->nullable();

            $table->string('file_surat');

            // Status
            $table->enum('status', [
                'pending',
                'disetujui',
                'ditolak'
            ])->default('pending');

            // Validasi Kepsek
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('validated_at')
                ->nullable();

            // File Final
            $table->string('file_final')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_surats');
    }
};