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
        Schema::create('elearning_lampiran', function (Blueprint $table) {

            $table->id();

            $table->foreignId('elearning_id')
                ->constrained('elearnings')
                ->cascadeOnDelete();

            $table->string('nama_file');

            $table->string('path');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elearning_lampirans');
    }
};
