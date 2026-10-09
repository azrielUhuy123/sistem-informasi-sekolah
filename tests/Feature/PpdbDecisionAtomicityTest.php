<?php

namespace Tests\Feature;

use App\Mail\PpdbPendaftaranMail;
use App\Models\PpdbPendaftar;
use App\Models\PpdbSetting;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PpdbDecisionAtomicityTest extends TestCase
{
    use RefreshDatabase;

    public function test_accept_from_pending_prevents_reject_and_repeated_accept(): void
    {
        Mail::fake();
        $tahunAjaran = $this->makeTahunAjaran();
        $this->makeSetting($tahunAjaran);
        $pendaftar = $this->makePendaftar($tahunAjaran, 'pending');
        $tu = $this->makeTu();

        $this->actingAs($tu)
            ->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertSessionHas('success', 'Pendaftar berhasil diterima.');

        $this->assertSame('diterima', $pendaftar->fresh()->status);
        Mail::assertSent(PpdbPendaftaranMail::class, function (PpdbPendaftaranMail $mail) use ($pendaftar) {
            return $mail->hasTo($pendaftar->email)
                && $mail->pendaftar->status === 'diterima';
        });

        $this->post(route('tu.ppdb.pendaftar.tolak', $pendaftar))
            ->assertSessionHas('error', 'Pendaftar ini sudah memiliki keputusan.');
        $this->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertSessionHas('error', 'Pendaftar ini sudah memiliki keputusan.');

        $this->assertSame('diterima', $pendaftar->fresh()->status);
        Mail::assertSent(PpdbPendaftaranMail::class, 1);
    }

    public function test_accept_from_verifikasi_is_allowed(): void
    {
        Mail::fake();
        $tahunAjaran = $this->makeTahunAjaran();
        $this->makeSetting($tahunAjaran);
        $pendaftar = $this->makePendaftar($tahunAjaran, 'verifikasi');

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertSessionHas('success', 'Pendaftar berhasil diterima.');

        $this->assertSame('diterima', $pendaftar->fresh()->status);
        Mail::assertSent(PpdbPendaftaranMail::class, 1);
    }

    public function test_reject_from_pending_prevents_accept_and_repeated_reject(): void
    {
        Mail::fake();
        $tahunAjaran = $this->makeTahunAjaran();
        $this->makeSetting($tahunAjaran);
        $pendaftar = $this->makePendaftar($tahunAjaran, 'pending');
        $tu = $this->makeTu();

        $this->actingAs($tu)
            ->post(route('tu.ppdb.pendaftar.tolak', $pendaftar))
            ->assertSessionHas('success', 'Pendaftar berhasil ditolak.');

        $this->assertSame('ditolak', $pendaftar->fresh()->status);
        Mail::assertSent(PpdbPendaftaranMail::class, function (PpdbPendaftaranMail $mail) use ($pendaftar) {
            return $mail->hasTo($pendaftar->email)
                && $mail->pendaftar->status === 'ditolak';
        });

        $this->post(route('tu.ppdb.pendaftar.terima', $pendaftar))
            ->assertSessionHas('error', 'Pendaftar ini sudah memiliki keputusan.');
        $this->post(route('tu.ppdb.pendaftar.tolak', $pendaftar))
            ->assertSessionHas('error', 'Pendaftar ini sudah memiliki keputusan.');

        $this->assertSame('ditolak', $pendaftar->fresh()->status);
        Mail::assertSent(PpdbPendaftaranMail::class, 1);
    }

    public function test_reject_from_verifikasi_is_allowed(): void
    {
        Mail::fake();
        $tahunAjaran = $this->makeTahunAjaran();
        $pendaftar = $this->makePendaftar($tahunAjaran, 'verifikasi');

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.pendaftar.tolak', $pendaftar))
            ->assertSessionHas('success', 'Pendaftar berhasil ditolak.');

        $this->assertSame('ditolak', $pendaftar->fresh()->status);
        Mail::assertSent(PpdbPendaftaranMail::class, 1);
    }

    private function makeTahunAjaran(): TahunAjaran
    {
        return TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
    }

    private function makeSetting(TahunAjaran $tahunAjaran): PpdbSetting
    {
        return PpdbSetting::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-12-31',
            'kuota' => 10,
            'status' => 'dibuka',
        ]);
    }

    private function makePendaftar(TahunAjaran $tahunAjaran, string $status): PpdbPendaftar
    {
        return PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-DECISION-'.bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => $status,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'PPDB decision test applicant',
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
            'kartu_keluarga' => 'ppdb/kk.pdf',
            'ktp' => 'ppdb/ktp.pdf',
            'foto' => 'ppdb/foto.jpg',
            'email' => 'decision-'.bin2hex(random_bytes(5)).'@example.test',
        ]);
    }

    private function makeTu(): User
    {
        return User::forceCreate([
            'name' => 'PPDB decision TU',
            'email' => 'decision-tu-'.bin2hex(random_bytes(4)).'@example.test',
            'username' => 'decision-tu-'.bin2hex(random_bytes(4)),
            'password' => 'test-password',
            'role' => 'tu',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}