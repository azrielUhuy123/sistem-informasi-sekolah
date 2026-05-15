<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pengajuan_jadwal
            MODIFY status ENUM(
                'draft',
                'submitted',
                'approved',
                'published',
                'rejected'
            )
            DEFAULT 'draft'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE pengajuan_jadwal
            MODIFY status ENUM(
                'draft',
                'submitted',
                'approved',
                'rejected'
            )
            DEFAULT 'draft'
        ");
    }
};