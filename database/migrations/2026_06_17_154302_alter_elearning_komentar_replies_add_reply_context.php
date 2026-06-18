<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'elearning_komentar_replies',
            function (Blueprint $table) {

                $table->string('reply_to_name')
                    ->nullable()
                    ->after('user_id');

                $table->text('reply_preview')
                    ->nullable()
                    ->after('reply_to_name');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'elearning_komentar_replies',
            function (Blueprint $table) {

                $table->dropColumn([
                    'reply_to_name',
                    'reply_preview'
                ]);
            }
        );
    }
};