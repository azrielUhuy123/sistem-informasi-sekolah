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
        Schema::table('guru', function (Blueprint $table) {

            $table->dropColumn([
                'nama',
                'nip',
                'jenis_kelamin',
                'mapel',
                'foto',
                'email',
                'telepon',
                'alamat',
            ]);

        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {

            $table->string('nama');
            $table->string('nip')->nullable();
            $table->enum('jenis_kelamin',['L','P']);
            $table->string('mapel')->nullable();
            $table->string('foto')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon',20)->nullable();
            $table->text('alamat')->nullable();

        });
    }
};
