<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelasRombelCrudTest extends TestCase
{
    use RefreshDatabase;

    private Angkatan $angkatan;

    private KelasMaster $kelasMaster;

    private KelasGrup $kelasGrup;

    private TahunAjaran $tahunAjaran;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tahunAjaran = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $this->angkatan = Angkatan::create(['tahun' => 2026]);
        $this->kelasMaster = KelasMaster::create(['tingkat' => 'I']);
        $this->kelasGrup = KelasGrup::create([
            'angkatan_id' => $this->angkatan->id,
            'kelas_master_id' => $this->kelasMaster->id,
        ]);

        $this->actingAs(User::forceCreate([
            'name' => 'Operator TU Rombel',
            'email' => 'tu-rombel-' . bin2hex(random_bytes(4)) . '@example.test',
            'username' => 'tu-rombel-' . bin2hex(random_bytes(4)),
            'password' => 'test-password',
            'role' => 'tu',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]));
    }

    public function test_tu_can_create_a_rombel_inside_a_kelas_grup(): void
    {
        $this->post(route('tu.kelas.rombel.store', $this->kelasGrup->id), [
            'nama_kelas' => 'I-A',
        ])
            ->assertRedirect(route('tu.kelas.rombel.index', $this->kelasGrup->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('kelas', [
            'kelas_grup_id' => $this->kelasGrup->id,
            'nama_kelas' => 'IA',
        ]);
    }

    public function test_duplicate_rombel_name_in_the_same_group_is_rejected(): void
    {
        $this->createRombel($this->kelasGrup, 'IA');

        $this->post(route('tu.kelas.rombel.store', $this->kelasGrup->id), [
            'nama_kelas' => 'I-A',
        ])->assertSessionHasErrors('nama_kelas');

        $this->assertSame(1, Kelas::where('kelas_grup_id', $this->kelasGrup->id)->count());
    }

    public function test_same_rombel_name_is_allowed_in_a_different_group(): void
    {
        $otherGroup = $this->createGroupForYear(2027);

        $this->post(route('tu.kelas.rombel.store', $this->kelasGrup->id), [
            'nama_kelas' => 'I-A',
        ])->assertSessionHas('success');

        $this->post(route('tu.kelas.rombel.store', $otherGroup->id), [
            'nama_kelas' => 'I-A',
        ])->assertSessionHas('success');

        $this->assertSame(1, Kelas::where('kelas_grup_id', $this->kelasGrup->id)->where('nama_kelas', 'IA')->count());
        $this->assertSame(1, Kelas::where('kelas_grup_id', $otherGroup->id)->where('nama_kelas', 'IA')->count());
    }

    public function test_rombel_list_is_scoped_to_its_angkatan_and_kelas_grup(): void
    {
        $rombel = $this->createRombel($this->kelasGrup, 'IA');
        $otherGroup = $this->createGroupForYear(2027);
        $this->createRombel($otherGroup, 'IA');

        $this->get(route('tu.kelas.rombel.index', $this->kelasGrup->id))
            ->assertOk()
            ->assertSee('Angkatan 2026')
            ->assertSee('Kelas I')
            ->assertSee('I-A')
            ->assertViewHas('rombels', fn ($rombels) =>
                $rombels->count() === 1 && $rombels->first()->is($rombel)
            );
    }

    public function test_unused_rombel_can_be_renamed(): void
    {
        $rombel = $this->createRombel($this->kelasGrup, 'IA');

        $this->put(route('tu.kelas.rombel.update', $rombel->id), [
            'nama_kelas' => 'I-B',
        ])
            ->assertRedirect(route('tu.kelas.rombel.index', $this->kelasGrup->id))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('kelas', [
            'id' => $rombel->id,
            'kelas_grup_id' => $this->kelasGrup->id,
            'nama_kelas' => 'IB',
        ]);
    }

    public function test_rombel_cannot_be_moved_to_another_kelas_grup(): void
    {
        $rombel = $this->createRombel($this->kelasGrup, 'IA');
        $otherGroup = $this->createGroupForYear(2027);

        $this->put(route('tu.kelas.rombel.update', $rombel->id), [
            'nama_kelas' => 'I-B',
            'kelas_grup_id' => $otherGroup->id,
        ])->assertSessionHasErrors('kelas_grup_id');

        $this->assertDatabaseHas('kelas', [
            'id' => $rombel->id,
            'kelas_grup_id' => $this->kelasGrup->id,
            'nama_kelas' => 'IA',
        ]);
    }

    public function test_unused_rombel_can_be_deleted(): void
    {
        $rombel = $this->createRombel($this->kelasGrup, 'IA');

        $this->delete(route('tu.kelas.rombel.destroy', $rombel->id))
            ->assertRedirect(route('tu.kelas.rombel.index', $this->kelasGrup->id))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('kelas', ['id' => $rombel->id]);
    }

    public function test_rombel_with_placement_cannot_be_deleted(): void
    {
        $rombel = $this->createRombel($this->kelasGrup, 'IA');
        $this->createPlacement($rombel);

        $this->delete(route('tu.kelas.rombel.destroy', $rombel->id))
            ->assertRedirect(route('tu.kelas.rombel.index', $this->kelasGrup->id))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('kelas', [
            'id' => $rombel->id,
            'deleted_at' => null,
        ]);
        $this->assertSame(1, PenempatanSiswa::where('kelas_id', $rombel->id)->count());
    }

    public function test_rombel_with_placement_cannot_be_renamed(): void
    {
        $rombel = $this->createRombel($this->kelasGrup, 'IA');
        $this->createPlacement($rombel);

        $this->put(route('tu.kelas.rombel.update', $rombel->id), [
            'nama_kelas' => 'I-B',
        ])->assertSessionHasErrors('nama_kelas');

        $this->assertDatabaseHas('kelas', [
            'id' => $rombel->id,
            'kelas_grup_id' => $this->kelasGrup->id,
            'nama_kelas' => 'IA',
        ]);
    }

    private function createRombel(KelasGrup $kelasGrup, string $namaKelas): Kelas
    {
        return Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => $namaKelas,
        ]);
    }

    private function createGroupForYear(int $year): KelasGrup
    {
        $angkatan = Angkatan::create(['tahun' => $year]);

        return KelasGrup::create([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $this->kelasMaster->id,
        ]);
    }

    private function createPlacement(Kelas $rombel): PenempatanSiswa
    {
        $siswa = Siswa::create([
            'nama' => 'Siswa Uji Rombel',
            'nis' => 'nis-' . bin2hex(random_bytes(4)),
            'jenis_kelamin' => 'L',
            'angkatan_id' => $this->angkatan->id,
        ]);

        return PenempatanSiswa::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $rombel->id,
            'tahun_ajaran_id' => $this->tahunAjaran->id,
            'tanggal_masuk' => '2026-07-01',
        ]);
    }
}