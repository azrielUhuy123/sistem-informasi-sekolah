<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elearnings', function (Blueprint $table) {

            $table->foreignId('pengampu_id')
                ->nullable()
                ->after('guru_id')
                ->constrained('pengampu_mapel')
                ->nullOnDelete();

            $table->foreignId('wali_kelas_id')
                ->nullable()
                ->after('pengampu_id')
                ->constrained('wali_kelas')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('elearnings', function (Blueprint $table) {

            $table->dropConstrainedForeignId('pengampu_id');

            $table->dropConstrainedForeignId('wali_kelas_id');

        });
    }
};