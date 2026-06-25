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
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'pegawai_id')) {
                $table->foreignId('pegawai_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('data_kepegawaian')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('users', 'siswa_id')) {
                $table->foreignId('siswa_id')
                    ->nullable()
                    ->after('pegawai_id')
                    ->constrained('siswa')
                    ->nullOnDelete();
            }

        });
    }
};
