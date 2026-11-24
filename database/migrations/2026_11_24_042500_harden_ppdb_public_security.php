<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            if (! Schema::hasColumn('ppdb_pendaftar', 'daftar_ulang_token_hash')) {
                $table->string('daftar_ulang_token_hash', 64)->nullable();
            }
            if (! Schema::hasColumn('ppdb_pendaftar', 'daftar_ulang_token_expires_at')) {
                $table->timestamp('daftar_ulang_token_expires_at')->nullable();
            }
            if (! Schema::hasColumn('ppdb_pendaftar', 'daftar_ulang_token_used_at')) {
                $table->timestamp('daftar_ulang_token_used_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_pendaftar', function (Blueprint $table) {
            $table->dropColumn([
                'daftar_ulang_token_hash',
                'daftar_ulang_token_expires_at',
                'daftar_ulang_token_used_at',
            ]);
        });
    }
};
