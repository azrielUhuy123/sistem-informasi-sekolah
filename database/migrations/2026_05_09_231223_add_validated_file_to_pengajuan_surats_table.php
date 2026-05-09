<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {

            $table->string('validated_file')
                ->nullable()
                ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('pengajuan_surats', function (Blueprint $table) {

            $table->dropColumn('validated_file');

        });
    }
};
