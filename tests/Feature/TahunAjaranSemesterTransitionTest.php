<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TahunAjaranSemesterTransitionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_ganjil_placement_is_copied_to_genap_without_closing_source(): void
    {
        $fixture = $this->makeTransitionFixture();

        $this->from('/tu/tahun')
            ->post('/tu/tahun/lanjut-semester')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHas('success');

        $this->assertSame('selesai', $fixture['ganjil']->fresh()->status);
        $this->assertSame('aktif', $fixture['genap']->fresh()->status);
        $this->assertNull($fixture['placement']->fresh()->tanggal_keluar);

        $this->assertDatabaseHas('penempatan_siswa', [
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'tahun_ajaran_id' => $fixture['genap']->id,
            'tanggal_masuk' => now()->toDateString(),
            'tanggal_keluar' => null,
        ]);
    }

    public function test_repeating_transition_does_not_create_a_second_genap_placement(): void
    {
        $fixture = $this->makeTransitionFixture();

        $this->from('/tu/tahun')->post('/tu/tahun/lanjut-semester');
        $this->from('/tu/tahun')
            ->post('/tu/tahun/lanjut-semester')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHas('error');

        $this->assertSame(
            1,
            PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
                ->where('tahun_ajaran_id', $fixture['genap']->id)
                ->count()
        );
    }

    public function test_activating_genap_after_completed_ganjil_also_copies_placements(): void
    {
        $fixture = $this->makeTransitionFixture();
        $fixture['ganjil']->update(['status' => 'selesai']);

        $this->from('/tu/tahun')
            ->post('/tu/tahun/' . $fixture['genap']->id . '/aktifkan')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHas('success');

        $this->assertSame('selesai', $fixture['ganjil']->fresh()->status);
        $this->assertSame('aktif', $fixture['genap']->fresh()->status);
        $this->assertDatabaseHas('penempatan_siswa', [
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'tahun_ajaran_id' => $fixture['genap']->id,
            'tanggal_keluar' => null,
        ]);
    }

    public function test_cohort_mismatch_is_skipped_and_reported(): void
    {
        $fixture = $this->makeTransitionFixture(cohortMismatch: true);

        $this->from('/tu/tahun')
            ->post('/tu/tahun/lanjut-semester')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHas('success', fn (string $message) => str_contains($message, 'mismatch Angkatan: 1'));

        $this->assertNull($fixture['placement']->fresh()->tanggal_keluar);
        $this->assertSame(
            0,
            PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
                ->where('tahun_ajaran_id', $fixture['genap']->id)
                ->count()
        );
        $this->assertSame('aktif', $fixture['genap']->fresh()->status);
    }

    public function test_existing_genap_placement_is_left_unchanged(): void
    {
        $fixture = $this->makeTransitionFixture();
        $existingGenap = PenempatanSiswa::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['kelas']->id,
            'tahun_ajaran_id' => $fixture['genap']->id,
            'tanggal_masuk' => '2026-08-01',
            'tanggal_keluar' => '2026-08-15',
        ]);

        $this->from('/tu/tahun')->post('/tu/tahun/lanjut-semester');

        $this->assertSame(
            1,
            PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
                ->where('tahun_ajaran_id', $fixture['genap']->id)
                ->count()
        );
        $this->assertSame('2026-08-01', $existingGenap->fresh()->tanggal_masuk);
        $this->assertSame('2026-08-15', $existingGenap->fresh()->tanggal_keluar);
    }

    public function test_failed_second_genap_insert_rolls_back_the_whole_transition(): void
    {
        $fixture = $this->makeTransitionFixture(studentCount: 2);
        $targetYearId = $fixture['genap']->id;
        $targetInserts = 0;
        $insertPrefix = 'insert into ' . DB::connection()->getQueryGrammar()->wrapTable('penempatan_siswa') . ' ';

        DB::listen(function (QueryExecuted $query) use ($targetYearId, &$targetInserts, $insertPrefix): void {
            if (
                str_starts_with(strtolower($query->sql), strtolower($insertPrefix)) &&
                in_array($targetYearId, $query->bindings, true)
            ) {
                $targetInserts++;

                if ($targetInserts === 2) {
                    throw new \RuntimeException('Simulated Genap placement insert failure.');
                }
            }
        });

        $this->withoutExceptionHandling();

        try {
            $this->from('/tu/tahun')->post('/tu/tahun/lanjut-semester');
            $this->fail('Expected the simulated placement insert failure.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated Genap placement insert failure.', $exception->getMessage());
        }

        $this->assertSame('aktif', $fixture['ganjil']->fresh()->status);
        $this->assertSame('draft', $fixture['genap']->fresh()->status);
        $this->assertSame(
            0,
            PenempatanSiswa::where('tahun_ajaran_id', $fixture['genap']->id)->count()
        );
        $this->assertSame(
            2,
            PenempatanSiswa::where('tahun_ajaran_id', $fixture['ganjil']->id)
                ->whereNull('tanggal_keluar')
                ->count()
        );
    }

    private function makeTransitionFixture(
        bool $cohortMismatch = false,
        int $studentCount = 1
    ): array {
        TahunAjaran::where('status', 'aktif')
            ->where('semester', 'Ganjil')
            ->update(['status' => 'selesai']);

        $tahunAwal = random_int(2080, 2100);
        $tahunLabel = $tahunAwal . '/' . ($tahunAwal + 1);

        $ganjil = TahunAjaran::create([
            'tahun' => $tahunLabel,
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $genap = TahunAjaran::create([
            'tahun' => $tahunLabel,
            'semester' => 'Genap',
            'status' => 'draft',
        ]);

        $angkatan = Angkatan::create(['tahun' => $tahunAwal]);
        $angkatanSiswa = $cohortMismatch
            ? Angkatan::create(['tahun' => $tahunAwal + 1])
            : $angkatan;
        $kelasMaster = KelasMaster::firstOrCreate(['tingkat' => 'I']);
        $kelasGrup = KelasGrup::create([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $kelasMaster->id,
        ]);
        $kelas = Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => 'IA',
        ]);

        $siswa = null;
        $placement = null;

        for ($index = 0; $index < $studentCount; $index++) {
            $siswa = Siswa::create([
                'nama' => 'Semester transition test ' . $index,
                'nis' => (string) random_int(1000000, 9999999),
                'jenis_kelamin' => 'L',
                'angkatan_id' => $angkatanSiswa->id,
            ]);

            $placement = PenempatanSiswa::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelas->id,
                'tahun_ajaran_id' => $ganjil->id,
                'tanggal_masuk' => now()->toDateString(),
                'tanggal_keluar' => null,
            ]);
        }

        return compact('ganjil', 'genap', 'angkatan', 'kelas', 'siswa', 'placement');
    }
}