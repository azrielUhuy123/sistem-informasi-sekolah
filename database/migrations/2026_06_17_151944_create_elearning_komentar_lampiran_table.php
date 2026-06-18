<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elearning_komentar_lampiran', function (Blueprint $table) {

            $table->id();

            $table->foreignId('komentar_id')
                ->nullable()
                ->constrained('elearning_komentar')
                ->cascadeOnDelete();

            $table->foreignId('reply_id')
                ->nullable()
                ->constrained('elearning_komentar_replies')
                ->cascadeOnDelete();

            $table->string('nama_file');

            $table->string('path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elearning_komentar_lampiran');
    }
};
