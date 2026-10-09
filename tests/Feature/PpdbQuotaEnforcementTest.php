<?php

namespace Tests\Feature;

use App\Models\PpdbPendaftar;
use App\Models\PpdbSetting;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class PpdbQuotaEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_pendaftar_can_be_accepted_when_their_year_has_quota_remaining(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $this->makeSetting($tahunAjaran, 2);
        $this->makePendaftar($tahunAjaran, 'diterima');
        $pendaftar = $this->makePendaftar($tahunAjaran);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success', 'Pendaftar berhasil diterima.');

        $this->assertSame('diterima', $pendaftar->fresh()->status);
    }

    public function test_pendaftar_is_not_accepted_when_their_year_quota_is_full(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $this->makeSetting($tahunAjaran, 2);
        $this->makePendaftar($tahunAjaran, 'diterima');
        $this->makePendaftar($tahunAjaran, 'diterima');
        $pendaftar = $this->makePendaftar($tahunAjaran);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('error', 'Kuota PPDB untuk tahun ajaran ini telah penuh.');

        $this->assertSame('pending', $pendaftar->fresh()->status);
        $this->assertSame(2, PpdbPendaftar::where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('status', 'diterima')
            ->count());
    }

    public function test_quota_is_isolated_by_the_registrants_academic_year(): void
    {
        $tahunA = $this->makeTahunAjaran('2025/2026', 'selesai');
        $tahunB = $this->makeTahunAjaran('2026/2027', 'aktif');
        $this->makeSetting($tahunA, 2);
        $this->makeSetting($tahunB, 3);
        $this->makePendaftar($tahunA, 'diterima');
        $this->makePendaftar($tahunA, 'diterima');
        $pendaftarTahunB = $this->makePendaftar($tahunB);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pendaftar.terima', $pendaftarTahunB))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('diterima', $pendaftarTahunB->fresh()->status);
    }

    public function test_incomplete_documents_still_block_acceptance_when_quota_is_available(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $this->makeSetting($tahunAjaran, 2);
        $pendaftar = $this->makePendaftar($tahunAjaran, 'pending', false);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('error', 'Kartu keluarga, KTP orang tua, dan foto wajib tersedia sebelum pendaftar diterima.');

        $this->assertSame('pending', $pendaftar->fresh()->status);
    }

    public function test_acceptance_is_blocked_when_the_year_has_no_ppdb_setting(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $pendaftar = $this->makePendaftar($tahunAjaran);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('error', 'Konfigurasi PPDB untuk Tahun Ajaran pendaftar tidak ditemukan.');

        $this->assertSame('pending', $pendaftar->fresh()->status);
    }

    public function test_dashboard_uses_the_setting_and_accepted_count_for_the_active_year(): void
    {
        $tahunA = $this->makeTahunAjaran('2026/2027', 'aktif');
        $tahunB = $this->makeTahunAjaran('2027/2028', 'draft');
        $this->makeSetting($tahunA, 2);
        $this->makeSetting($tahunB, 5);
        $this->makePendaftar($tahunA, 'diterima');
        $this->makePendaftar($tahunB, 'diterima');
        $this->makePendaftar($tahunB, 'diterima');

        $this->actingAs($this->makeTu())
            ->get(route('tu.ppdb.dashboard'))
            ->assertOk()
            ->assertViewHas('kuota', 2)
            ->assertViewHas('diterima', 1)
            ->assertViewHas('sisaKuota', 1)
            ->assertViewHas('persentase', 50);
    }

    public function test_settings_are_saved_for_the_selected_academic_year(): void
    {
        $tahunA = $this->makeTahunAjaran('2025/2026', 'selesai');
        $tahunB = $this->makeTahunAjaran('2026/2027', 'aktif');
        $this->makeSetting($tahunA, 2);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pengaturan.store'), [
                'tahun_ajaran_id' => $tahunB->id,
                'tanggal_mulai' => '2026-01-01',
                'tanggal_selesai' => '2026-06-30',
                'kuota' => 3,
                'status' => 'dibuka',
            ])
            ->assertRedirect(route('tu.ppdb.pengaturan.index', ['tahun_ajaran_id' => $tahunB->id]))
            ->assertSessionHas('success');

        $this->assertSame(2, PpdbSetting::where('tahun_ajaran_id', $tahunA->id)->value('kuota'));
        $this->assertSame(3, PpdbSetting::where('tahun_ajaran_id', $tahunB->id)->value('kuota'));
    }

    public function test_one_setting_can_be_created_for_an_academic_year(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');

        $setting = $this->makeSetting($tahunAjaran, 2);

        $this->assertSame($tahunAjaran->id, $setting->tahun_ajaran_id);
        $this->assertSame(1, PpdbSetting::where('tahun_ajaran_id', $tahunAjaran->id)->count());
    }

    public function test_saving_a_setting_again_for_the_same_year_updates_the_existing_row(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $setting = $this->makeSetting($tahunAjaran, 2);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pengaturan.store'), [
                'tahun_ajaran_id' => $tahunAjaran->id,
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-07-31',
                'kuota' => 4,
                'status' => 'dibuka',
            ])
            ->assertRedirect(route('tu.ppdb.pengaturan.index', ['tahun_ajaran_id' => $tahunAjaran->id]))
            ->assertSessionHas('success');

        $this->assertSame(1, PpdbSetting::where('tahun_ajaran_id', $tahunAjaran->id)->count());
        $this->assertSame($setting->id, PpdbSetting::where('tahun_ajaran_id', $tahunAjaran->id)->value('id'));
        $this->assertSame(4, PpdbSetting::where('tahun_ajaran_id', $tahunAjaran->id)->value('kuota'));
    }

    public function test_different_academic_years_can_have_separate_settings(): void
    {
        $tahunA = $this->makeTahunAjaran('2025/2026', 'selesai');
        $tahunB = $this->makeTahunAjaran('2026/2027', 'aktif');

        $this->makeSetting($tahunA, 2);
        $this->makeSetting($tahunB, 3);

        $this->assertSame(2, PpdbSetting::where('tahun_ajaran_id', $tahunA->id)->value('kuota'));
        $this->assertSame(3, PpdbSetting::where('tahun_ajaran_id', $tahunB->id)->value('kuota'));
        $this->assertSame(2, PpdbSetting::count());
    }

    public function test_database_rejects_a_duplicate_setting_for_the_same_academic_year(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('2026/2027', 'aktif');
        $this->makeSetting($tahunAjaran, 2);

        $this->expectException(QueryException::class);

        $this->makeSetting($tahunAjaran, 4);
    }

    private function makeTahunAjaran(string $tahun, string $status): TahunAjaran
    {
        return TahunAjaran::create([
            'tahun' => $tahun,
            'semester' => 'Ganjil',
            'status' => $status,
        ]);
    }

    private function makeSetting(TahunAjaran $tahunAjaran, int $kuota): PpdbSetting
    {
        return PpdbSetting::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-06-30',
            'kuota' => $kuota,
            'status' => 'dibuka',
        ]);
    }

    private function makePendaftar(
        TahunAjaran $tahunAjaran,
        string $status = 'pending',
        bool $hasRequiredDocuments = true
    ): PpdbPendaftar {
        return PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-QUOTA-' . bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => $status,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'PPDB quota test',
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
            'kartu_keluarga' => $hasRequiredDocuments ? 'ppdb/kk.pdf' : null,
            'ktp' => $hasRequiredDocuments ? 'ppdb/ktp.pdf' : null,
            'foto' => $hasRequiredDocuments ? 'ppdb/foto.jpg' : null,
        ]);
    }

    private function makeTu(): User
    {
        return User::forceCreate([
            'name' => 'PPDB quota test operator',
            'email' => 'ppdb-quota-' . bin2hex(random_bytes(4)) . '@example.test',
            'username' => 'ppdb-quota-tu-' . bin2hex(random_bytes(4)),
            'password' => 'test-password',
            'role' => 'tu',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}