<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
            }

            if (!Schema::hasColumn('siswa', 'alamat')) {
                $table->text('alamat')->nullable()->after('tanggal_lahir');
            }

            if (!Schema::hasColumn('siswa', 'nama_wali')) {
                $table->string('nama_wali')->nullable()->after('alamat');
            }

            if (!Schema::hasColumn('siswa', 'telepon_wali')) {
                $table->string('telepon_wali', 20)->nullable()->after('nama_wali');
            }
        });
    }

    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            foreach ([
                'tanggal_lahir',
                'alamat',
                'nama_wali',
                'telepon_wali'
            ] as $column) {
                if (Schema::hasColumn('siswa', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
