<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            // Do not infer a lifecycle state from the legacy boolean aktif column.
            $table->enum('status', ['draft', 'aktif', 'selesai'])
                ->nullable()
                ->default(null);
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('tahun_ajaran', 'status')) {
            return;
        }

        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};