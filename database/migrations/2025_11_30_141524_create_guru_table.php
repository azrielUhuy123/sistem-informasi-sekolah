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
    Schema::create('guru', function (Blueprint $table) {
        $table->id();
        $table->string('nip')->unique();
        $table->string('nama');
        $table->string('jenis_kelamin');
        $table->string('mapel')->nullable(); // mata pelajaran
        $table->string('telepon')->nullable();
        $table->string('email')->nullable();
        $table->string('alamat')->nullable();
        $table->string('foto')->nullable(); // foto profil
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
