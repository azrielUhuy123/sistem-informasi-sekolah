<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // KelasGrup still requires kelas_master_id; the grouping key is Angkatan + KelasMaster.
    }

    public function down(): void
    {
        // This migration no longer changes the schema.
    }
};
