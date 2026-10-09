<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\PpdbPendaftar;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PpdbDaftarUlangCohortTest extends TestCase
{
    use RefreshDatabase;

    public function test_daftar_ulang_uses_pendaftar_period_when_active_period_is_later(): void
    {
        $this->clearActiveAcademicYear();
        $admissionPeriod = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'selesai',
        ]);
        TahunAjaran::create([
            'tahun' => '2027/2028',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $pendaftar = $this->makeAcceptedPendaftar($admissionPeriod);
        $angkatan2027CountBefore = Angkatan::where('tahun', 2027)->count();

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftar->refresh();
        $siswa = Siswa::findOrFail($pendaftar->siswa_id);

        $this->assertSame($admissionPeriod->id, $pendaftar->tahun_ajaran_id);
        $this->assertSame('sudah', $pendaftar->status_daftar_ulang);
        $this->assertSame(2026, (int) Angkatan::findOrFail($siswa->angkatan_id)->tahun);
        $this->assertSame($angkatan2027CountBefore, Angkatan::where('tahun', 2027)->count());
    }

    public function test_daftar_ulang_uses_2026_angkatan_when_admission_and_active_period_match(): void
    {
        $this->clearActiveAcademicYear();
        $admissionPeriod = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $pendaftar = $this->makeAcceptedPendaftar($admissionPeriod);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftar->refresh();
        $siswa = Siswa::findOrFail($pendaftar->siswa_id);

        $this->assertSame($admissionPeriod->id, $pendaftar->tahun_ajaran_id);
        $this->assertSame(2026, (int) Angkatan::findOrFail($siswa->angkatan_id)->tahun);
    }

    private function clearActiveAcademicYear(): void
    {
        TahunAjaran::where('status', 'aktif')->update(['status' => 'selesai']);
    }

    private function makeTu(): User
    {
        return User::forceCreate([
            'name' => 'PPDB daftar ulang test operator',
            'email' => 'ppdb-daful-' . bin2hex(random_bytes(4)) . '@example.test',
            'username' => 'ppdb-daful-tu-' . random_int(100000, 999999),
            'password' => 'test-password',
            'role' => 'tu',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    private function makeAcceptedPendaftar(TahunAjaran $tahunAjaran): PpdbPendaftar
    {
        return PpdbPendaftar::create([
            'no_pendaftaran' => 'TEST-' . random_int(100000, 999999),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => 'diterima',
            'status_daftar_ulang' => 'sudah',
            'tanggal_daftar_ulang' => now()->toDateString(),
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'PPDB daful cohort test',
            'jenis_kelamin' => 'L',
            'nomor_kk' => '1234567890123456',
            'tempat_lahir' => 'Test City',
            'tanggal_lahir' => '2018-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'Indonesia',
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'alamat' => 'Test address',
            'desa' => 'Test village',
            'kecamatan' => 'Test district',
            'kabupaten' => 'Test regency',
            'provinsi' => 'Test province',
            'email' => 'ppdb-daful-pendaftar-' . bin2hex(random_bytes(4)) . '@example.test',
        ]);
    }
}