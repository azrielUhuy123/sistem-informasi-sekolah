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
        Schema::table('kelas_grup', function (Blueprint $table) {
        $table->foreignId('kelas_master_id')
              ->nullable()
              ->after('angkatan_id')
              ->constrained('kelas_master');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_grup', function (Blueprint $table) {
            //
        });
    }
};
