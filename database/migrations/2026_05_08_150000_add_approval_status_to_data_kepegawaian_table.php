<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('data_kepegawaian', 'approval_status')) {
            Schema::table('data_kepegawaian', function (Blueprint $table) {
                $table->string('approval_status')->default('pending');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('data_kepegawaian', 'approval_status')) {
            Schema::table('data_kepegawaian', function (Blueprint $table) {
                $table->dropColumn('approval_status');
            });
        }
    }
};