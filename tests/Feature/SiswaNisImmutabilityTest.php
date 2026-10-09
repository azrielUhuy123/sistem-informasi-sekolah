<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaNisImmutabilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_manual_creation_accepts_nis_and_rejects_a_duplicate(): void
    {
        $kelas = $this->createClass();
        $payload = $this->studentPayload($kelas->id, '7001001');

        $this->post(route('tu.siswa.store'), $payload)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'nis' => '7001001',
            'nama' => 'Siswa Uji',
        ]);

        $this->post(route('tu.siswa.store'), $payload)
            ->assertSessionHasErrors('nis');

        $this->assertSame(1, Siswa::where('nis', '7001001')->count());
    }

    public function test_update_without_nis_changes_other_fields_and_keeps_nis(): void
    {
        $siswa = $this->createStudent('7001002');

        $this->put(route('tu.siswa.update', $siswa), [
            'nama' => 'Nama Diperbarui',
            'nisn' => null,
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat Baru',
        ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'nis' => '7001002',
            'nama' => 'Nama Diperbarui',
            'alamat' => 'Alamat Baru',
        ]);
    }

    public function test_update_rejects_a_submitted_nis_and_preserves_the_existing_value(): void
    {
        $siswa = $this->createStudent('7001003');

        $this->put(route('tu.siswa.update', $siswa), [
            'nama' => 'Nama Tidak Boleh Disimpan',
            'nis' => '7001999',
            'nisn' => null,
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat Baru',
        ])
            ->assertSessionHasErrors('nis');

        $this->assertDatabaseHas('siswa', [
            'id' => $siswa->id,
            'nis' => '7001003',
            'nama' => 'Siswa Uji',
        ]);
        $this->assertDatabaseMissing('siswa', [
            'id' => $siswa->id,
            'nis' => '7001999',
        ]);
    }

    public function test_edit_form_displays_nis_without_an_editable_field(): void
    {
        $siswa = $this->createStudent('7001004');
        $this->actingAs(User::factory()->create());

        $this->get(route('tu.siswa.edit', $siswa))
            ->assertOk()
            ->assertSee('7001004')
            ->assertDontSee('name="nis"', false);
    }

    private function createClass(): Kelas
    {
        TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $angkatan = Angkatan::create(['tahun' => 2026]);
        $kelasMaster = KelasMaster::create(['tingkat' => 'I']);
        $kelasGrup = KelasGrup::create([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $kelasMaster->id,
        ]);

        return Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => 'IA',
        ]);
    }

    private function createStudent(string $nis): Siswa
    {
        return Siswa::create([
            'nama' => 'Siswa Uji',
            'nis' => $nis,
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat Lama',
        ]);
    }

    private function studentPayload(int $kelasId, string $nis): array
    {
        return [
            'kelas_id' => $kelasId,
            'nama' => 'Siswa Uji',
            'nis' => $nis,
            'nisn' => null,
            'jenis_kelamin' => 'L',
            'alamat' => 'Alamat Lama',
        ];
    }
}