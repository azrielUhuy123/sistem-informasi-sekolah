<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('jadwal', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('kelas_id');
        $table->string('mapel');
        $table->unsignedBigInteger('guru_id');
        $table->string('hari'); // Senin - Jumat
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->timestamps();

        $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
        $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('jadwal');
}

};
