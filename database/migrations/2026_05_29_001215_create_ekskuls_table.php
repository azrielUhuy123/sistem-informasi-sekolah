<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekskuls', function (Blueprint $table) {

            $table->id();

            $table->string('nama');

            $table->text('deskripsi')
                ->nullable();

            $table->foreignId('guru_id')
                ->nullable()
                ->constrained('guru')
                ->nullOnDelete();

            $table->string('hari')
                ->nullable();

            $table->time('jam_mulai')
                ->nullable();

            $table->time('jam_selesai')
                ->nullable();

            $table->integer('kuota')
                ->default(0);

            $table->enum('status', [

                'aktif',
                'nonaktif'

            ])->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ekskuls');
    }
};