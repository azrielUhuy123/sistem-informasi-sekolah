<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * USERS
         */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('tu'); // kamu pakai role
            $table->rememberToken();
            $table->timestamps();
        });

        /**
         * PASSWORD RESET TOKENS
         */
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        /**
         * SESSIONS
         */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        /**
         * KELAS
         */
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->string('tingkatan')->nullable();
            $table->timestamps();
        });

        /**
         * SISWA
         */
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nis')->unique();
            $table->date('tanggal_lahir')->nullable();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->timestamps();
        });

        /**
         * GURU
         */
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('mapel')->nullable();
            $table->timestamps();
        });

        /**
         * MATA PELAJARAN
         */
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mapel');
            $table->timestamps();
        });

        /**
         * JADWAL
         */
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->string('hari');
            $table->string('jam_mulai');
            $table->string('jam_selesai');
            $table->timestamps();
        });

        /**
         * TAHUN AJARAN
         */
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->boolean('aktif')->default(false);
            $table->timestamps();
        });

        /**
         * RUANGAN
         */
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ruangan');
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });

        /**
         * WALI KELAS
         */
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->timestamps();
        });

        /**
         * ARSIP DOKUMEN
         */
        Schema::create('arsip_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('kategori')->nullable();
            $table->timestamps();
        });

        /**
         * DATA KEPEGAWAIAN (VERSI BARU SESUAI MODEL)
         */
        Schema::create('data_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('status_kerja')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('riwayat_mengajar')->nullable();
            $table->integer('pengalaman_tahun')->nullable();
            $table->bigInteger('gaji')->nullable();
            $table->bigInteger('tunjangan')->nullable();
            $table->integer('absensi')->nullable();
            $table->string('penilaian_kinerja')->nullable();
            $table->text('pelatihan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_kepegawaian');
        Schema::dropIfExists('arsip_dokumen');
        Schema::dropIfExists('wali_kelas');
        Schema::dropIfExists('ruangan');
        Schema::dropIfExists('tahun_ajaran');
        Schema::dropIfExists('jadwal');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('guru');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
