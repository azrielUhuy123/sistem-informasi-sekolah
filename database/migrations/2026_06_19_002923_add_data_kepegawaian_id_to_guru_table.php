<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {

            $table->foreignId('data_kepegawaian_id')
                ->nullable()
                ->after('user_id')
                ->constrained('data_kepegawaian')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {

            $table->dropConstrainedForeignId(
                'data_kepegawaian_id'
            );

        });
    }
};