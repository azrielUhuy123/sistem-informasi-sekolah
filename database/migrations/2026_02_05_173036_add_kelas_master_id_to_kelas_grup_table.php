<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const FOREIGN_KEY = 'kelas_grup_kelas_master_id_foreign';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('kelas_grup', 'kelas_master_id')) {
            Schema::table('kelas_grup', function (Blueprint $table) {
                $table->foreignId('kelas_master_id')
                    ->nullable()
                    ->after('angkatan_id')
                    ->constrained('kelas_master');
            });

            return;
        }

        $hasForeignKey = collect(Schema::getForeignKeys('kelas_grup'))
            ->contains(fn (array $foreignKey): bool =>
                $foreignKey['columns'] === ['kelas_master_id'] &&
                $foreignKey['foreign_table'] === 'kelas_master'
            );

        if ($hasForeignKey) {
            return;
        }

        Schema::table('kelas_grup', function (Blueprint $table) {
            $table->foreign('kelas_master_id', self::FOREIGN_KEY)
                ->references('id')
                ->on('kelas_master');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $foreignKey = collect(Schema::getForeignKeys('kelas_grup'))
            ->first(fn (array $foreignKey): bool =>
                $foreignKey['columns'] === ['kelas_master_id'] &&
                $foreignKey['foreign_table'] === 'kelas_master'
            );

        if (! $foreignKey) {
            return;
        }

        Schema::table('kelas_grup', function (Blueprint $table) {
            $table->dropForeign(self::FOREIGN_KEY);
        });
    }
};
