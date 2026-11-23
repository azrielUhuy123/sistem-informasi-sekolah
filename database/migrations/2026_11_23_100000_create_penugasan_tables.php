<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kepegawaian_id')->constrained('data_kepegawaian')->cascadeOnDelete();
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
        });

        Schema::create('penugasan_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_item_id')->constrained('penugasan_items')->cascadeOnDelete();
            $table->string('menu_key');
            $table->timestamps();

            $table->unique(['penugasan_item_id', 'menu_key']);
        });

        $tuEmployeeIds = DB::table('users')
            ->where('role', 'tu')
            ->whereNotNull('pegawai_id')
            ->pluck('pegawai_id')
            ->filter()
            ->unique();

        DB::table('users')->where('role', 'tu')->update(['role' => 'guru']);

        if (Schema::hasTable('data_kepegawaian')) {
            DB::table('data_kepegawaian')
                ->whereRaw("LOWER(TRIM(jabatan)) = ?", ['tu'])
                ->orWhereRaw("LOWER(TRIM(jabatan)) = ?", ['tata usaha'])
                ->update(['jabatan' => 'Guru']);
        }

        if (Schema::hasTable('guru') && $tuEmployeeIds->isNotEmpty()) {
            foreach ($tuEmployeeIds as $employeeId) {
                $penugasanId = DB::table('penugasans')->insertGetId([
                    'data_kepegawaian_id' => $employeeId,
                    'status' => 'active',
                    'published_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->insertItem($penugasanId, 'operator', [
                    'tu.academic',
                    'tu.documents',
                ]);
                $this->insertItem($penugasanId, 'ppdb', [
                    'tu.ppdb',
                ]);
            }
        }

        $this->syncLegacyAssignments();
    }

    private function insertItem(int $penugasanId, string $type, array $menus): int
    {
        $itemId = DB::table('penugasan_items')->insertGetId([
            'penugasan_id' => $penugasanId,
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (array_unique($menus) as $menuKey) {
            DB::table('penugasan_menus')->insert([
                'penugasan_item_id' => $itemId,
                'menu_key' => $menuKey,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $itemId;
    }

    private function syncLegacyAssignments(): void
    {
        $tahunAjaranId = DB::table('tahun_ajaran')->where('aktif', true)->value('id');
        if (! $tahunAjaranId) {
            $tahunAjaranId = DB::table('tahun_ajaran')->orderBy('id')->value('id');
        }

        $waliRows = DB::table('wali_kelas')
            ->when($tahunAjaranId, fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaranId))
            ->get();
        foreach ($waliRows as $row) {
            $employeeId = DB::table('guru')->where('id', $row->guru_id)->value('data_kepegawaian_id');
            if (! $employeeId) continue;
            $penugasanId = $this->activeHeader($employeeId);
            $itemId = $this->insertItem($penugasanId, 'wali', ['guru.academic']);
            DB::table('penugasan_details')->insert([
                'penugasan_item_id' => $itemId,
                'kelas_id' => $row->kelas_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $pengampuRows = DB::table('pengampu_mapel')
            ->when($tahunAjaranId, fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaranId))
            ->get();
        foreach ($pengampuRows as $row) {
            $employeeId = DB::table('guru')->where('id', $row->guru_id)->value('data_kepegawaian_id');
            if (! $employeeId) continue;
            $penugasanId = $this->activeHeader($employeeId);
            $item = DB::table('penugasan_items')
                ->where('penugasan_id', $penugasanId)
                ->where('type', 'guru')
                ->first();
            $itemId = $item ? $item->id : $this->insertItem($penugasanId, 'guru', ['guru.academic']);
            DB::table('penugasan_details')->insert([
                'penugasan_item_id' => $itemId,
                'kelas_id' => $row->kelas_id,
                'mata_pelajaran_id' => $row->mata_pelajaran_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $pembinaRows = DB::table('pembina_ekskul')
            ->when($tahunAjaranId, fn ($query) => $query->where('tahun_ajaran_id', $tahunAjaranId))
            ->get();
        foreach ($pembinaRows as $row) {
            $employeeId = DB::table('guru')->where('id', $row->guru_id)->value('data_kepegawaian_id');
            if (! $employeeId) continue;
            $penugasanId = $this->activeHeader($employeeId);
            $item = DB::table('penugasan_items')
                ->where('penugasan_id', $penugasanId)
                ->where('type', 'ekskul')
                ->first();
            $itemId = $item ? $item->id : $this->insertItem($penugasanId, 'ekskul', ['guru.extracurricular']);
            DB::table('penugasan_details')->insert([
                'penugasan_item_id' => $itemId,
                'ekskul_id' => $row->ekskul_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function activeHeader(int $employeeId): int
    {
        $existing = DB::table('penugasans')
            ->where('data_kepegawaian_id', $employeeId)
            ->where('status', 'active')
            ->value('id');

        if ($existing) return (int) $existing;

        return (int) DB::table('penugasans')->insertGetId([
            'data_kepegawaian_id' => $employeeId,
            'status' => 'active',
            'published_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_menus');
        Schema::dropIfExists('penugasan_details');
        Schema::dropIfExists('penugasan_items');
        Schema::dropIfExists('penugasans');
    }
};
