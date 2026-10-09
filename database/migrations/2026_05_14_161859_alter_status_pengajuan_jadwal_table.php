<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan_jadwal', function (Blueprint $table) {
            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'published',
                'rejected'
            ])
                ->default('draft')
                ->nullable(false)
                ->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('pengajuan_jadwal', 'status') &&
            DB::table('pengajuan_jadwal')->where('status', 'published')->exists()) {
            throw new RuntimeException(
                'Cannot roll back pengajuan_jadwal.status while published rows exist.'
            );
        }

        if (! Schema::hasColumn('pengajuan_jadwal', 'status')) {
            return;
        }

        Schema::table('pengajuan_jadwal', function (Blueprint $table) {
            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'rejected'
            ])
                ->default('draft')
                ->nullable(false)
                ->change();
        });
    }
};