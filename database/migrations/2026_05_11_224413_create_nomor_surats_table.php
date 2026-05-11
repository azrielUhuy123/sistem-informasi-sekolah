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
        Schema::create('nomor_surats', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            /*
            |--------------------------------------------------------------------------
            | DATA NOMOR SURAT
            |--------------------------------------------------------------------------
            */
            $table->string('pengirim');

            $table->string('jenis_surat');

            $table->string('nomor_surat')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */
            $table->enum(
                'status',
                [
                    'digunakan'
                ]
            )->default('digunakan');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nomor_surats');
    }
};