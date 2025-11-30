<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('tahunajaran_id');

            $table->timestamps();

            $table->foreign('guru_id')->references('id')->on('guru')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('kelas_id')->references('id')->on('kelas')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('tahunajaran_id')->references('id')->on('tahun_ajaran')
                ->onDelete('cascade')->onUpdate('cascade');

            // Cegah 1 kelas punya lebih dari 1 wali di tahun ajaran yang sama
            $table->unique(['kelas_id', 'tahunajaran_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wali_kelas');
    }
};
