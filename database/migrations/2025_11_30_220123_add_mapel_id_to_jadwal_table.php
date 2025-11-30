<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {

            // Tambah kolom hanya jika belum ada
            if (!Schema::hasColumn('jadwal', 'mapel_id')) {
                $table->unsignedBigInteger('mapel_id')->nullable()->after('guru_id');
            }

            // Tambah foreign key hanya jika belum ada
            $foreignKeys = DB::select('SHOW KEYS FROM jadwal WHERE Key_name = "jadwal_mapel_id_foreign"');

            if (empty($foreignKeys)) {
                $table->foreign('mapel_id')
                    ->references('id')
                    ->on('mata_pelajaran')
                    ->onDelete('set null')
                    ->onUpdate('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {

            // Drop FK jika ada
            $foreignKeys = DB::select('SHOW KEYS FROM jadwal WHERE Key_name = "jadwal_mapel_id_foreign"');

            if (!empty($foreignKeys)) {
                $table->dropForeign(['mapel_id']);
            }

            // Drop kolom jika ada
            if (Schema::hasColumn('jadwal', 'mapel_id')) {
                $table->dropColumn('mapel_id');
            }
        });
    }
};
