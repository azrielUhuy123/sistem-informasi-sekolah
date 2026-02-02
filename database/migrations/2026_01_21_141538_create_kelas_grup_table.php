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
        Schema::create('kelas_grup', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('kelas_master_id');
        $table->unsignedBigInteger('tahun_ajaran_id');
        $table->timestamps();
        $table->unique(['kelas_master_id', 'tahun_ajaran_id']);


});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_grup');
    }
};
