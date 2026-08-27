<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =========================================
        // GURU → DATA KEPEGAWAIAN
        // =========================================
        Schema::table('guru', function (Blueprint $table) {
            $table->dropForeign('guru_data_kepegawaian_id_foreign');

            $table->foreign('data_kepegawaian_id')
                ->references('id')
                ->on('data_kepegawaian')
                ->cascadeOnDelete();
        });

        // =========================================
        // USERS → DATA KEPEGAWAIAN
        // =========================================
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_pegawai_id_foreign');

            $table->foreign('pegawai_id')
                ->references('id')
                ->on('data_kepegawaian')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // =========================================
        // GURU → DATA KEPEGAWAIAN
        // =========================================
        Schema::table('guru', function (Blueprint $table) {
            $table->dropForeign('guru_data_kepegawaian_id_foreign');

            $table->foreign('data_kepegawaian_id')
                ->references('id')
                ->on('data_kepegawaian')
                ->nullOnDelete();
        });

        // =========================================
        // USERS → DATA KEPEGAWAIAN
        // =========================================
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_pegawai_id_foreign');

            $table->foreign('pegawai_id')
                ->references('id')
                ->on('data_kepegawaian')
                ->nullOnDelete();
        });
    }
};