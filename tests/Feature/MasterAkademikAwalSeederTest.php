<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\PenempatanSiswa;
use App\Models\PpdbPendaftar;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Database\Seeders\MasterAkademikAwalSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterAkademikAwalSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_academic_seeder_is_idempotent_and_creates_only_requested_master_data(): void
    {
        $this->seed(MasterAkademikAwalSeeder::class);
        $this->seed(MasterAkademikAwalSeeder::class);

        $this->assertSame(1, TahunAjaran::where('tahun', '2026/2027')
            ->where('semester', 'Ganjil')
            ->where('status', 'aktif')
            ->count());
        $this->assertSame(1, Angkatan::where('tahun', 2026)->count());
        $this->assertEqualsCanonicalizing(
            ['I', 'II', 'III', 'IV', 'V', 'VI'],
            KelasMaster::orderBy('tingkat')->pluck('tingkat')->all()
        );

        $angkatan = Angkatan::where('tahun', 2026)->firstOrFail();
        $this->assertSame(6, KelasGrup::where('angkatan_id', $angkatan->id)->count());

        foreach (['I', 'II', 'III', 'IV', 'V', 'VI'] as $tingkat) {
            $kelasMaster = KelasMaster::where('tingkat', $tingkat)->firstOrFail();

            $this->assertDatabaseHas('kelas_grup', [
                'angkatan_id' => $angkatan->id,
                'kelas_master_id' => $kelasMaster->id,
            ]);
        }

        $this->assertSame(0, Kelas::withTrashed()->count());
        $this->assertSame(0, Siswa::withTrashed()->count());
        $this->assertSame(0, PenempatanSiswa::count());
        $this->assertSame(0, PpdbPendaftar::count());
    }
}