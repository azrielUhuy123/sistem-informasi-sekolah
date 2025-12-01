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
        Schema::create('arsip_dokumen', function (Blueprint $table) {
            $table->id();

            // Metadata dokumen
            $table->string('nama_surat');               // judul/ nama dokumen
            $table->string('nomor_surat')->nullable();  // opsional
            $table->date('tanggal_dibuat')->nullable();

            // Kategori (opsional, bebas disesuaikan)
            $table->string('kategori')->nullable();

            // Path file yang diupload
            $table->string('file_path'); // storage/app/public/arsip

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip_dokumen');
    }
};
