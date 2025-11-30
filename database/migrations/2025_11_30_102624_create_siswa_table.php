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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();

            // Data dasar siswa
            $table->string('nama');
            $table->string('nis')->unique();   // Nomor Induk Siswa
            $table->string('nisn')->nullable();  // Nomor Induk Siswa Nasional

            // Relasi ke kelas
            $table->unsignedBigInteger('kelas_id');
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');

            // Data tambahan siswa
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('telepon_wali')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
