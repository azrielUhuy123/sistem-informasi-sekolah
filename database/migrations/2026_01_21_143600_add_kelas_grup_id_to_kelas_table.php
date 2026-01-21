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
    Schema::table('kelas', function (Blueprint $table) {
        $table->foreignId('kelas_grup_id')
              ->nullable()
              ->after('id')
              ->constrained('kelas_grup')
              ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('kelas', function (Blueprint $table) {
        $table->dropForeign(['kelas_grup_id']);
        $table->dropColumn('kelas_grup_id');
    });
}

};
