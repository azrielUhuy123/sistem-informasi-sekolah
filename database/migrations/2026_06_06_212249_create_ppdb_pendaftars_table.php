<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_pendaftar', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Administrasi
            |--------------------------------------------------------------------------
            */

            $table->string('no_pendaftaran')->unique();

            $table->date('tanggal_pendaftaran');

            $table->enum('status', [
                'pending',
                'verifikasi',
                'diterima',
                'ditolak'
            ])->default('pending');

            $table->text('catatan_verifikasi')->nullable();

            $table->foreignId('tahun_ajaran_id')
                ->constrained('tahun_ajaran')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Data Pribadi
            |--------------------------------------------------------------------------
            */

            $table->string('nama');

            $table->enum('jenis_kelamin', [
                'L',
                'P'
            ]);

            $table->string('nomor_kk', 20);

            $table->string('tempat_lahir');

            $table->date('tanggal_lahir');

            $table->string('nomor_akta')->nullable();

            $table->string('agama');

            $table->string('kewarganegaraan')
                ->default('Indonesia');

            $table->string('nik', 20)->unique();

            /*
            |--------------------------------------------------------------------------
            | Alamat
            |--------------------------------------------------------------------------
            */

            $table->text('alamat');

            $table->string('rt', 5)->nullable();

            $table->string('rw', 5)->nullable();

            $table->string('desa');

            $table->string('kecamatan');

            $table->string('kabupaten');

            $table->string('provinsi');

            /*
            |--------------------------------------------------------------------------
            | Kontak
            |--------------------------------------------------------------------------
            */

            $table->string('telepon_rumah')->nullable();

            $table->string('hp_ayah')->nullable();

            $table->string('hp_ibu')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Data Periodik
            |--------------------------------------------------------------------------
            */

            $table->decimal('tinggi_badan', 5, 2)->nullable();

            $table->decimal('berat_badan', 5, 2)->nullable();

            $table->integer('anak_ke')->nullable();

            $table->integer('jumlah_saudara')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Upload Berkas
            |--------------------------------------------------------------------------
            */

            $table->string('foto')->nullable();

            $table->string('akta_lahir')->nullable();

            $table->string('kartu_keluarga')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Integrasi Akademik
            |--------------------------------------------------------------------------
            */

            $table->foreignId('siswa_id')
                ->nullable()
                ->constrained('siswa')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_pendaftar');
    }
};