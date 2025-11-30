<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // R01, LAB-2, etc.
            $table->string('nama_ruangan');   // Ruang Kelas 7A, Lab Komputer
            $table->integer('kapasitas')->nullable();
            $table->string('jenis')->nullable(); // kelas / lab / kantor
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
