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
        if (! Schema::hasColumn('kelas_grup', 'tahun_ajaran_id')) {
            return;
        }

        $legacyUnique = 'kelas_grup_kelas_master_id_tahun_ajaran_id_unique';

        if (Schema::hasIndex('kelas_grup', $legacyUnique)) {
            Schema::table('kelas_grup', function (Blueprint $table) use ($legacyUnique) {
                $table->dropUnique($legacyUnique);
            });
        }

        Schema::table('kelas_grup', function (Blueprint $table) {
            $table->dropColumn('tahun_ajaran_id');
        });
}

public function down(): void
{
    Schema::table('kelas_grup', function (Blueprint $table) {
            if (! Schema::hasColumn('kelas_grup', 'tahun_ajaran_id')) {
                $table->unsignedBigInteger('tahun_ajaran_id')->nullable()->after('angkatan_id');
            }
    });
}

};
