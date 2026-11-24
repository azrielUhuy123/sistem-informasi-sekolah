<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasDuplicates = DB::table('data_kepegawaian')
            ->select('nip')
            ->whereNotNull('nip')
            ->groupBy('nip')
            ->havingRaw('COUNT(*) > 1')
            ->limit(1)
            ->exists();

        if ($hasDuplicates) {
            throw new RuntimeException(
                'Cannot enforce unique employee NIP: duplicate values must be resolved through the approved data-reconciliation process.'
            );
        }

        Schema::table('data_kepegawaian', function (Blueprint $table) {
            $table->unique('nip', 'data_kepegawaian_nip_unique');
        });
    }

    public function down(): void
    {
        Schema::table('data_kepegawaian', function (Blueprint $table) {
            $table->dropUnique('data_kepegawaian_nip_unique');
        });
    }
};
