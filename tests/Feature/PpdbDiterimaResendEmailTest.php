<?php

namespace Tests\Feature;

use App\Mail\PpdbPendaftaranMail;
use App\Models\PpdbPendaftar;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PpdbDiterimaResendEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_accepted_registrant_can_resend_existing_mailable_to_updated_email(): void
    {
        Mail::fake();
        $pendaftar = $this->makePendaftar('diterima', 'old@example.test');
        $newEmail = 'updated@example.test';

        $this->actingAs($this->makeTu())
            ->postJson(route('tu.ppdb.diterima.resend-email', $pendaftar), [
                'email' => $newEmail,
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Email pemberitahuan berhasil dikirim.',
            ]);

        $this->assertSame('diterima', $pendaftar->fresh()->status);
        $this->assertSame($newEmail, $pendaftar->fresh()->email);
        $this->assertNotSame('old@example.test', $pendaftar->fresh()->email);

        Mail::assertSent(PpdbPendaftaranMail::class, function (PpdbPendaftaranMail $mail) use ($newEmail, $pendaftar) {
            return $mail->hasTo($newEmail)
                && $mail->pendaftar->is($pendaftar)
                && $mail->pendaftar->status === 'diterima';
        });
    }

    public function test_invalid_email_is_rejected_without_update_or_mail(): void
    {
        Mail::fake();
        $pendaftar = $this->makePendaftar('diterima', 'old@example.test');

        $this->actingAs($this->makeTu())
            ->postJson(route('tu.ppdb.diterima.resend-email', $pendaftar), [
                'email' => 'not-an-email',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertSame('old@example.test', $pendaftar->fresh()->email);
        $this->assertSame('diterima', $pendaftar->fresh()->status);
        Mail::assertNothingSent();
    }

    public function test_empty_email_is_rejected_without_update_or_mail(): void
    {
        Mail::fake();
        $pendaftar = $this->makePendaftar('diterima', 'old@example.test');

        $this->actingAs($this->makeTu())
            ->postJson(route('tu.ppdb.diterima.resend-email', $pendaftar), [
                'email' => '',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');

        $this->assertSame('old@example.test', $pendaftar->fresh()->email);
        Mail::assertNothingSent();
    }

    public function test_registrant_not_accepted_cannot_update_email_or_receive_mail(): void
    {
        Mail::fake();
        $pendaftar = $this->makePendaftar('pending', 'old@example.test');

        $this->actingAs($this->makeTu())
            ->postJson(route('tu.ppdb.diterima.resend-email', $pendaftar), [
                'email' => 'updated@example.test',
            ])
            ->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Email hanya dapat dikirim ulang untuk pendaftar berstatus diterima.',
            ]);

        $this->assertSame('pending', $pendaftar->fresh()->status);
        $this->assertSame('old@example.test', $pendaftar->fresh()->email);
        Mail::assertNothingSent();
    }

    public function test_failed_send_keeps_updated_email_and_accepted_status(): void
    {
        $pendaftar = $this->makePendaftar('diterima', 'old@example.test');
        $newEmail = 'updated@example.test';
        Mail::shouldReceive('to')
            ->once()
            ->with($newEmail)
            ->andThrow(new \RuntimeException('Simulated transport failure'));
        Log::shouldReceive('error')->once();

        $this->actingAs($this->makeTu())
            ->postJson(route('tu.ppdb.diterima.resend-email', $pendaftar), [
                'email' => $newEmail,
            ])
            ->assertStatus(502)
            ->assertJson([
                'success' => false,
                'message' => 'Alamat email berhasil diperbarui, tetapi email pemberitahuan gagal dikirim.',
            ]);

        $this->assertSame('diterima', $pendaftar->fresh()->status);
        $this->assertSame($newEmail, $pendaftar->fresh()->email);
    }

    public function test_resend_endpoint_requires_authentication_and_tu_role(): void
    {
        $pendaftar = $this->makePendaftar('diterima', 'accepted@example.test');
        $url = route('tu.ppdb.diterima.resend-email', $pendaftar);

        $this->postJson($url, ['email' => 'updated@example.test'])
            ->assertUnauthorized();

        $this->actingAs($this->makeUser('guru'))
            ->postJson($url, ['email' => 'updated@example.test'])
            ->assertRedirect('/guru/dashboard');
    }

    private function makePendaftar(string $status, string $email): PpdbPendaftar
    {
        $tahunAjaran = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        return PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-RESEND-' . bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => $status,
            'status_daftar_ulang' => 'belum',
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'Peserta Resend PPDB',
            'jenis_kelamin' => 'L',
            'nomor_kk' => '1234567890123456',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2018-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'Indonesia',
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'alamat' => 'Alamat peserta',
            'desa' => 'Desa',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'kartu_keluarga' => 'ppdb/kk.pdf',
            'ktp' => 'ppdb/ktp.pdf',
            'foto' => 'ppdb/foto.jpg',
            'email' => $email,
        ]);
    }

    private function makeTu(): User
    {
        return $this->makeUser('tu');
    }

    private function makeUser(string $role): User
    {
        return User::forceCreate([
            'name' => 'PPDB resend test operator',
            'email' => 'ppdb-resend-' . bin2hex(random_bytes(4)) . '@example.test',
            'username' => 'ppdb-resend-' . bin2hex(random_bytes(4)),
            'password' => 'test-password',
            'role' => $role,
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}