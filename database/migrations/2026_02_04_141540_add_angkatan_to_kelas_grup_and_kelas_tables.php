<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    // =========================
    // KELAS_GRUP
    // =========================
    Schema::table('kelas_grup', function (Blueprint $table) {

        if (!Schema::hasColumn('kelas_grup', 'angkatan_id')) {
            $table->foreignId('angkatan_id')
                ->nullable()
                ->after('id')
                ->constrained('angkatan')
                ->nullOnDelete();
        }

        if (!Schema::hasColumn('kelas_grup', 'tingkat')) {
            $table->unsignedTinyInteger('tingkat')
                ->nullable()
                ->after('angkatan_id');
        }
    });

    // =========================
    // KELAS
    // =========================
    Schema::table('kelas', function (Blueprint $table) {

        if (!Schema::hasColumn('kelas', 'kelas_grup_id')) {
            $table->foreignId('kelas_grup_id')
                ->nullable()
                ->after('id')
                ->constrained('kelas_grup')
                ->nullOnDelete();
        }
    });
}


    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['kelas_grup_id']);
            $table->dropColumn('kelas_grup_id');
        });

        Schema::table('kelas_grup', function (Blueprint $table) {
            $table->dropForeign(['angkatan_id']);
            $table->dropColumn(['angkatan_id', 'tingkat']);
        });
    }
};
