<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $columnsToDrop = array_values(array_filter(
            [
                'nama',
                'nip',
                'jenis_kelamin',
                'email',
                'telepon',
                'alamat',
            ],
            fn (string $column): bool => Schema::hasColumn('guru', $column)
        ));

        if ($columnsToDrop === []) {
            return;
        }

        Schema::table('guru', function (Blueprint $table) use ($columnsToDrop) {
            $table->dropColumn($columnsToDrop);
        });
    }

    public function down(): void
    {
        throw new RuntimeException(
            'This migration is irreversible because dropped Guru identity values cannot be restored safely.'
        );
    }
};
