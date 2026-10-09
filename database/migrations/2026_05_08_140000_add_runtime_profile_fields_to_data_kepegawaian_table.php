<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_kepegawaian', function (Blueprint $table) {
            if (! Schema::hasColumn('data_kepegawaian', 'nuptk')) {
                $table->string('nuptk', 50)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'agama')) {
                $table->string('agama', 50)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'status_kepegawaian')) {
                $table->enum('status_kepegawaian', ['PNS', 'NON_PNS'])->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'status_kawin')) {
                $table->enum('status_kawin', ['K', 'TK'])->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'ijazah_terakhir')) {
                $table->string('ijazah_terakhir', 100)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'sk_terakhir_no')) {
                $table->string('sk_terakhir_no', 100)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'sk_terakhir_tmt')) {
                $table->date('sk_terakhir_tmt')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'sk_terakhir_pangkat')) {
                $table->string('sk_terakhir_pangkat', 50)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'sk_berkala_no')) {
                $table->string('sk_berkala_no', 100)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'sk_berkala_tmt')) {
                $table->date('sk_berkala_tmt')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'sk_berkala_pangkat')) {
                $table->string('sk_berkala_pangkat', 50)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'mulai_kerja')) {
                $table->date('mulai_kerja')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'masa_kerja_golongan')) {
                $table->integer('masa_kerja_golongan')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'masa_kerja_keseluruhan')) {
                $table->integer('masa_kerja_keseluruhan')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'masa_kerja_gol_th')) {
                $table->integer('masa_kerja_gol_th')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'masa_kerja_gol_bln')) {
                $table->integer('masa_kerja_gol_bln')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'masa_kerja_total_th')) {
                $table->integer('masa_kerja_total_th')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'masa_kerja_total_bln')) {
                $table->integer('masa_kerja_total_bln')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'mengajar_kelas')) {
                $table->string('mengajar_kelas', 100)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'jam_mengajar')) {
                $table->integer('jam_mengajar')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'gaji_pokok')) {
                $table->decimal('gaji_pokok', 15, 2)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'tugas_tambahan')) {
                $table->string('tugas_tambahan')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'nomor_sertifikasi')) {
                $table->string('nomor_sertifikasi', 100)->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'tahun_sertifikasi')) {
                $table->year('tahun_sertifikasi')->nullable();
            }
            if (! Schema::hasColumn('data_kepegawaian', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Existing schemas may already own guarded columns; preserve them on rollback.
    }
};