<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            $table->string('jenis_pendidikan_asal', 50)->nullable();
            $table->string('nama_sekolah_asal', 150)->nullable();
            $table->string('nisn_sekolah_asal', 10)->nullable();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('ktp')->nullable();
            $table->string('ijazah')->nullable();
            $table->string('kartu_kip')->nullable();
        });

        Schema::table('ppdb_orangtua', function (Blueprint $table) {
            $table->string('agama', 50)->nullable();
            $table->enum('penghasilan', [
                '<500.000',
                '500.000-999.999',
                '1.000.000-1.999.999',
                '1.000.000-2.000.000',
                '2.000.000-4.999.999',
                '>5.000.000',
            ])->nullable()->change();
        });
    }

    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('ppdb_orangtua')
            ->where('penghasilan', '1.000.000-1.999.999')
            ->update(['penghasilan' => '1.000.000-2.000.000']);

        Schema::table('ppdb_orangtua', function (Blueprint $table) {
            $table->enum('penghasilan', [
                '<500.000',
                '500.000-999.999',
                '1.000.000-2.000.000',
                '2.000.000-4.999.999',
                '>5.000.000',
            ])->nullable()->change();
            $table->dropColumn('agama');
        });

        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_pendidikan_asal',
                'nama_sekolah_asal',
                'nisn_sekolah_asal',
                'nomor_hp',
                'ktp',
                'ijazah',
                'kartu_kip',
            ]);
        });
    }
};