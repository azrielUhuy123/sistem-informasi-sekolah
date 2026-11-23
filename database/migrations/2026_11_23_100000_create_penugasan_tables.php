<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $requiredTables = ['users', 'data_kepegawaian', 'kelas', 'mata_pelajaran', 'ekskuls'];
        $missingTables = array_values(array_filter(
            $requiredTables,
            fn (string $table) => ! Schema::hasTable($table)
        ));
        if ($missingTables) {
            throw new \RuntimeException('Tabel master yang dibutuhkan belum tersedia: '.implode(', ', $missingTables));
        }

        $newTables = [
            'penugasans',
            'penugasan_items',
            'penugasan_details',
            'penugasan_menus',
            'penugasan_role_migration_snapshots',
        ];
        $existingTables = array_values(array_filter($newTables, fn (string $table) => Schema::hasTable($table)));
        if ($existingTables) {
            throw new \RuntimeException('Skema penugasan sudah ada sebagian; periksa kondisi database sebelum melanjutkan.');
        }

        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kepegawaian_id')->constrained('data_kepegawaian')->restrictOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['data_kepegawaian_id', 'status']);
        });

        Schema::create('penugasan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_id')->constrained('penugasans')->cascadeOnDelete();
            $table->string('type');
            $table->timestamps();
            $table->unique(['penugasan_id', 'type']);
        });

        Schema::create('penugasan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_item_id')->constrained('penugasan_items')->cascadeOnDelete();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('mata_pelajaran')->nullOnDelete();
            $table->foreignId('ekskul_id')->nullable()->constrained('ekskuls')->nullOnDelete();
            $table->timestamps();
            $table->index(['penugasan_item_id', 'kelas_id', 'mata_pelajaran_id', 'ekskul_id'], 'penugasan_details_lookup_idx');
        });

        Schema::create('penugasan_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_item_id')->constrained('penugasan_items')->cascadeOnDelete();
            $table->string('menu_key');
            $table->timestamps();
            $table->unique(['penugasan_item_id', 'menu_key']);
            $table->index('menu_key');
        });

        Schema::create('penugasan_role_migration_snapshots', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary();
            $table->string('original_role');
        });

        DB::transaction(function (): void {
            $legacyUsers = DB::table('users')->where('role', 'tu')->get(['id', 'role']);
            foreach ($legacyUsers as $legacyUser) {
                DB::table('penugasan_role_migration_snapshots')->insert([
                    'user_id' => $legacyUser->id,
                    'original_role' => $legacyUser->role,
                ]);
            }

            DB::table('users')->where('role', 'tu')->update(['role' => 'guru']);

            $this->syncLegacyAssignments();
        });
    }

    private function syncLegacyAssignments(): void
    {
        if (! $this->hasColumns('tahun_ajaran', ['id'])) {
            return;
        }

        $yearQuery = DB::table('tahun_ajaran');
        if (Schema::hasColumn('tahun_ajaran', 'status')) {
            $yearQuery->where('status', 'aktif');
        } elseif (Schema::hasColumn('tahun_ajaran', 'aktif')) {
            $yearQuery->where('aktif', true);
        } else {
            return;
        }
        $activeYearIds = $yearQuery->orderBy('id')->pluck('id');
        if ($activeYearIds->count() !== 1) {
            return;
        }
        $activeYearId = $activeYearIds->first();

        if (! $this->hasColumns('guru', ['id', 'data_kepegawaian_id'])) {
            return;
        }

        if ($this->hasColumns('wali_kelas', ['guru_id', 'kelas_id', 'tahun_ajaran_id'])) {
            $rows = DB::table('wali_kelas')->where('tahun_ajaran_id', $activeYearId)->get();
            foreach ($rows->groupBy('guru_id') as $guruRows) {
                $classIds = $guruRows->pluck('kelas_id')->unique();
                if ($classIds->count() !== 1 || $classIds->contains(null)) {
                    continue;
                }

                $row = $guruRows->first();
                $employeeId = DB::table('guru')->where('id', $row->guru_id)->value('data_kepegawaian_id');
                if (! $employeeId) continue;

                $this->draftItem((int) $employeeId, 'wali', [
                    'kelas_id' => $row->kelas_id,
                    'mata_pelajaran_id' => null,
                    'ekskul_id' => null,
                ]);
            }
        }

        if ($this->hasColumns('pengampu_mapel', ['guru_id', 'kelas_id', 'mata_pelajaran_id', 'tahun_ajaran_id'])) {
            $rows = DB::table('pengampu_mapel')->where('tahun_ajaran_id', $activeYearId)->get();
            foreach ($rows as $row) {
                $employeeId = DB::table('guru')->where('id', $row->guru_id)->value('data_kepegawaian_id');
                if (! $employeeId) continue;

                $this->draftItem((int) $employeeId, 'guru', [
                    'kelas_id' => $row->kelas_id,
                    'mata_pelajaran_id' => $row->mata_pelajaran_id,
                    'ekskul_id' => null,
                ]);
            }
        }

        if ($this->hasColumns('pembina_ekskul', ['guru_id', 'ekskul_id', 'tahun_ajaran_id'])) {
            $rows = DB::table('pembina_ekskul')->where('tahun_ajaran_id', $activeYearId)->get();
            foreach ($rows as $row) {
                $employeeId = DB::table('guru')->where('id', $row->guru_id)->value('data_kepegawaian_id');
                if (! $employeeId) continue;

                $this->draftItem((int) $employeeId, 'ekskul', [
                    'kelas_id' => null,
                    'mata_pelajaran_id' => null,
                    'ekskul_id' => $row->ekskul_id,
                ]);
            }
        }
    }

    private function hasColumns(string $table, array $columns): bool
    {
        if (! Schema::hasTable($table)) {
            return false;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return false;
            }
        }

        return true;
    }

    private function draftItem(int $employeeId, string $type, array $details): int
    {
        $query = DB::table('penugasan_items')
            ->join('penugasans', 'penugasans.id', '=', 'penugasan_items.penugasan_id')
            ->join('penugasan_details', 'penugasan_details.penugasan_item_id', '=', 'penugasan_items.id')
            ->where('penugasans.data_kepegawaian_id', $employeeId)
            ->where('penugasans.status', 'draft')
            ->where('penugasan_items.type', $type);

        foreach ($details as $column => $value) {
            $query = $value === null
                ? $query->whereNull('penugasan_details.'.$column)
                : $query->where('penugasan_details.'.$column, $value);
        }

        $existingItemId = $query->value('penugasan_items.id');
        if ($existingItemId) {
            return (int) $existingItemId;
        }

        $penugasanId = (int) DB::table('penugasans')->insertGetId([
            'data_kepegawaian_id' => $employeeId,
            'status' => 'draft',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $itemId = $this->item($penugasanId, $type, []);
        $this->detail($itemId, $details);

        return $itemId;
    }

    private function item(int $penugasanId, string $type, array $menuKeys): int
    {
        $itemId = DB::table('penugasan_items')
            ->where('penugasan_id', $penugasanId)
            ->where('type', $type)
            ->value('id');

        if (! $itemId) {
            $itemId = DB::table('penugasan_items')->insertGetId([
                'penugasan_id' => $penugasanId,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($menuKeys as $menuKey) {
            DB::table('penugasan_menus')->updateOrInsert(
                ['penugasan_item_id' => $itemId, 'menu_key' => $menuKey],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        return (int) $itemId;
    }

    private function detail(int $itemId, array $attributes): void
    {
        $query = DB::table('penugasan_details')->where('penugasan_item_id', $itemId);
        foreach ($attributes as $column => $value) {
            $value === null ? $query->whereNull($column) : $query->where($column, $value);
        }

        if (! $query->exists()) {
            DB::table('penugasan_details')->insert($attributes + [
                'penugasan_item_id' => $itemId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('penugasans') && DB::table('penugasans')->exists()) {
            throw new \RuntimeException('Rollback dibatalkan agar riwayat penugasan tidak terhapus. Ekspor dan rencanakan migrasi data terlebih dahulu.');
        }

        DB::transaction(function (): void {
            if (Schema::hasTable('penugasan_role_migration_snapshots')) {
                foreach (DB::table('penugasan_role_migration_snapshots')->get() as $snapshot) {
                    DB::table('users')
                        ->where('id', $snapshot->user_id)
                        ->where('role', 'guru')
                        ->update(['role' => $snapshot->original_role]);
                }
            }

            Schema::dropIfExists('penugasan_role_migration_snapshots');
            Schema::dropIfExists('penugasan_menus');
            Schema::dropIfExists('penugasan_details');
            Schema::dropIfExists('penugasan_items');
            Schema::dropIfExists('penugasans');
        });
    }
};
