<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {

            // kategori mapel
            $table->enum('kategori', ['inti', 'tambahan', 'muatan_lokal'])
                ->default('inti')
                ->after('nama_mapel');

            // fase kurikulum (A:1-2, B:3-4, C:5-6)
            $table->enum('fase', ['A', 'B', 'C'])
                ->nullable()
                ->after('kategori');

            // apakah wajib
            $table->boolean('is_wajib')
                ->default(true)
                ->after('fase');

            // jumlah jam per minggu
            $table->integer('jp_per_minggu')
                ->nullable()
                ->after('is_wajib');
        });
    }

    public function down(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropColumn([
                'kategori',
                'fase',
                'is_wajib',
                'jp_per_minggu'
            ]);
        });
    }
};