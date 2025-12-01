<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();

            // Relasi ke kelas
            $table->unsignedBigInteger('kelas_id');
            
            // Relasi ke mata pelajaran
            $table->unsignedBigInteger('mapel_id'); 

            // Relasi ke guru
            $table->unsignedBigInteger('guru_id');

            $table->string('hari'); // Senin - Jumat
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();

            // Foreign keys
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('mapel_id')->references('id')->on('mata_pelajaran')->onDelete('cascade');
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
};
