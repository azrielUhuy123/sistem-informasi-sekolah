<?php

namespace Tests\Feature;

use App\Models\PpdbPendaftar;
use App\Models\Angkatan;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Mail\PpdbDaftarUlangCredentialsMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PpdbDaftarUlangWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_waiting_public_registration_shows_waiting_state_without_tu_confirmation_button(): void
    {
        $pendaftar = $this->makePendaftar('belum');

        $this->actingAs($this->makeTu())
            ->get(route('tu.ppdb.daftar-ulang.index'))
            ->assertOk()
            ->assertSee('Menunggu Daftar Ulang')
            ->assertDontSee('/tu/ppdb/daftar-ulang/' . $pendaftar->id . '/konfirmasi', false);
    }

    public function test_publicly_registered_pendaftar_without_siswa_shows_tu_confirmation_button(): void
    {
        $pendaftar = $this->makePendaftar('sudah');

        $this->actingAs($this->makeTu())
            ->get(route('tu.ppdb.daftar-ulang.index'))
            ->assertOk()
            ->assertSee('Sudah Daftar Ulang')
            ->assertSee('/tu/ppdb/daftar-ulang/' . $pendaftar->id . '/konfirmasi', false);
    }

    public function test_completed_pendaftar_shows_no_confirmation_button(): void
    {
        $pendaftar = $this->makePendaftar('sudah');
        $siswa = Siswa::create([
            'nama' => $pendaftar->nama,
            'nis' => '2617.1.001',
            'jenis_kelamin' => 'L',
        ]);
        $pendaftar->update(['siswa_id' => $siswa->id]);

        $this->actingAs($this->makeTu())
            ->get(route('tu.ppdb.daftar-ulang.index'))
            ->assertOk()
            ->assertSee('Sudah Dikonfirmasi')
            ->assertDontSee('/tu/ppdb/daftar-ulang/' . $pendaftar->id . '/konfirmasi', false);
    }

    public function test_tu_confirmation_rejects_pendaftar_before_public_daftar_ulang_without_creating_accounts_or_students(): void
    {
        $pendaftar = $this->makePendaftar('belum');
        $userCount = User::count();
        $siswaCount = Siswa::count();

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('error', 'Daftar ulang belum dilakukan oleh orang tua atau wali.');

        $this->assertSame('belum', $pendaftar->fresh()->status_daftar_ulang);
        $this->assertNull($pendaftar->fresh()->siswa_id);
        $this->assertSame($userCount + 1, User::count());
        $this->assertSame($siswaCount, Siswa::count());
    }

    public function test_tu_confirmation_rejects_a_pendaftar_who_already_has_a_siswa(): void
    {
        $pendaftar = $this->makePendaftar('sudah');
        $siswa = Siswa::create([
            'nama' => $pendaftar->nama,
            'nis' => '2617.1.002',
            'jenis_kelamin' => 'L',
        ]);
        $pendaftar->update(['siswa_id' => $siswa->id]);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success', 'Akun siswa untuk daftar ulang ini sudah pernah dibuat.');

        $this->assertSame($siswa->id, $pendaftar->fresh()->siswa_id);
    }

    public function test_tu_confirmation_rejects_a_pendaftar_not_yet_accepted(): void
    {
        $pendaftar = $this->makePendaftar('sudah', 'verifikasi');

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('error', 'Pendaftar belum diterima.');

        $this->assertNull($pendaftar->fresh()->siswa_id);
    }

    public function test_public_daftar_ulang_requires_matching_registration_number_and_birth_date(): void
    {
        $pendaftar = $this->makePendaftar('belum');
        $tu = $this->makeTu();

        $this->post(route('ppdb.daftar-ulang.simpan'), [
            'no_pendaftaran' => $pendaftar->no_pendaftaran,
            'tanggal_lahir' => '2018-01-01',
        ])->assertRedirect()->assertSessionHas('success', 'Daftar ulang berhasil');

        $pendaftar->refresh();
        $this->assertSame('sudah', $pendaftar->status_daftar_ulang);
        $originalDate = $pendaftar->tanggal_daftar_ulang;
        $this->assertSame(1, $tu->notifications()->count());

        $this->post(route('ppdb.daftar-ulang.simpan'), [
            'no_pendaftaran' => $pendaftar->no_pendaftaran,
            'tanggal_lahir' => '2018-01-01',
        ])->assertRedirect()->assertSessionHas('error', 'Daftar ulang sudah pernah dikonfirmasi.');

        $pendaftar->refresh();
        $this->assertSame($originalDate, $pendaftar->tanggal_daftar_ulang);
        $this->assertSame(1, $tu->notifications()->count());
    }

    public function test_public_daftar_ulang_rejects_wrong_birth_date_and_unknown_number_generically(): void
    {
        $pendaftar = $this->makePendaftar('belum');
        $tu = $this->makeTu();
        $error = 'Nomor pendaftaran atau tanggal lahir tidak sesuai, atau pendaftar belum memenuhi syarat daftar ulang.';

        foreach ([
            [
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'tanggal_lahir' => '2018-01-02',
            ],
            [
                'no_pendaftaran' => 'PPDB-NOT-FOUND',
                'tanggal_lahir' => '2018-01-01',
            ],
        ] as $payload) {
            $this->post(route('ppdb.daftar-ulang.simpan'), $payload)
                ->assertRedirect()
                ->assertSessionHas('error', $error);

            $this->assertSame('belum', $pendaftar->fresh()->status_daftar_ulang);
            $this->assertNull($pendaftar->fresh()->tanggal_daftar_ulang);
            $this->assertSame(0, $tu->notifications()->count());
        }
    }

    public function test_public_daftar_ulang_rejects_pending_verifikasi_and_rejected_applicants(): void
    {
        $tu = $this->makeTu();
        $error = 'Nomor pendaftaran atau tanggal lahir tidak sesuai, atau pendaftar belum memenuhi syarat daftar ulang.';

        foreach (['pending', 'verifikasi', 'ditolak'] as $status) {
            $pendaftar = $this->makePendaftar('belum', $status);

            $this->post(route('ppdb.daftar-ulang.simpan'), [
                'no_pendaftaran' => $pendaftar->no_pendaftaran,
                'tanggal_lahir' => '2018-01-01',
            ])->assertRedirect()->assertSessionHas('error', $error);

            $this->assertSame($status, $pendaftar->fresh()->status);
            $this->assertSame('belum', $pendaftar->fresh()->status_daftar_ulang);
            $this->assertNull($pendaftar->fresh()->tanggal_daftar_ulang);
        }

        $this->assertSame(0, $tu->notifications()->count());
    }

    public function test_public_bukti_masks_name_hides_nik_and_shows_actual_acceptance_status(): void
    {
        $pendaftar = $this->makePendaftar('belum', 'diterima');

        $this->get(route('ppdb.bukti', $pendaftar->no_pendaftaran))
            ->assertOk()
            ->assertSee($pendaftar->no_pendaftaran)
            ->assertSee('Diterima')
            ->assertSee('P**B')
            ->assertDontSee($pendaftar->nama)
            ->assertDontSee($pendaftar->nik)
            ->assertDontSee($pendaftar->tanggal_lahir)
            ->assertDontSee($pendaftar->email)
            ->assertDontSee($pendaftar->nomor_kk)
            ->assertDontSee($pendaftar->alamat)
            ->assertDontSee('MENUNGGU VERIFIKASI');
    }

    public function test_confirmation_after_public_registration_creates_user_and_siswa_without_placement(): void
    {
        Mail::fake();
        $pendaftar = $this->makePendaftar('sudah');
        $tu = $this->makeTu();
        $userCount = User::count();
        $siswaCount = Siswa::count();

        $this->actingAs($tu)
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success', 'Daftar ulang berhasil dikonfirmasi.');

        $pendaftar->refresh();
        $siswa = Siswa::findOrFail($pendaftar->siswa_id);
        $user = User::findOrFail($siswa->user_id);

        $this->assertSame($pendaftar->nama, $user->name);
        $this->assertSame($pendaftar->email, $user->email);
        $this->assertSame($siswa->nis, $user->username);
        $this->assertSame('siswa', $user->role);
        $this->assertTrue((bool) $user->is_active);
        $this->assertSame('approved', $user->approval_status);
        $this->assertSame($user->id, $siswa->user_id);
        $this->assertSame($user->siswa_id, $siswa->id);
        $this->assertSame($siswa->id, $pendaftar->siswa_id);
        $this->assertSame($userCount + 1, User::count());
        $this->assertSame($siswaCount + 1, Siswa::count());
        $this->assertSame('sudah', $pendaftar->status_daftar_ulang);
        $this->assertSame('2026-09-30', $pendaftar->tanggal_daftar_ulang);
        $this->assertSame(2026, (int) Angkatan::findOrFail($siswa->angkatan_id)->tahun);
        $this->assertSame(0, PenempatanSiswa::where('siswa_id', $siswa->id)->count());
        $this->assertTrue(Hash::check('01012018', $user->password));

        Mail::assertSent(PpdbDaftarUlangCredentialsMail::class, function (PpdbDaftarUlangCredentialsMail $mail) use ($pendaftar, $siswa) {
            $this->assertTrue($mail->hasTo($pendaftar->email));
            $this->assertSame('Daftar Ulang Berhasil - Informasi Akun Siswa', $mail->envelope()->subject);

            $html = $mail->render();

            foreach ([$pendaftar->nama, $siswa->nis, '01012018', 'Segera ganti password setelah login'] as $content) {
                $this->assertStringContainsString($content, $html);
            }

            foreach (['reset-password', 'reset_token', 'activation', 'setup-password', 'href='] as $forbiddenContent) {
                $this->assertStringNotContainsString($forbiddenContent, strtolower($html));
            }

            return true;
        });
        Mail::assertSent(PpdbDaftarUlangCredentialsMail::class, 1);

        $this->post(route('logout'));

        $this->post(route('login'), [
            'username' => $siswa->nis,
            'password' => '01012018',
        ])->assertRedirect(route('siswa.dashboard.index'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_repeated_confirmation_is_idempotent_and_keeps_the_original_user_and_siswa_links(): void
    {
        Mail::fake();
        $pendaftar = $this->makePendaftar('sudah');
        $tu = $this->makeTu();

        $this->actingAs($tu)
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success', 'Daftar ulang berhasil dikonfirmasi.');

        Mail::assertSent(PpdbDaftarUlangCredentialsMail::class, 1);

        $pendaftar->refresh();
        $firstSiswaId = $pendaftar->siswa_id;
        $firstSiswa = Siswa::findOrFail($firstSiswaId);
        $firstUserId = $firstSiswa->user_id;
        $userCount = User::count();
        $siswaCount = Siswa::count();

        $this->actingAs($tu)
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success', 'Akun siswa untuk daftar ulang ini sudah pernah dibuat.');

        $pendaftar->refresh();

        $this->assertSame($userCount, User::count());
        $this->assertSame($siswaCount, Siswa::count());
        $this->assertSame($firstSiswaId, $pendaftar->siswa_id);
        $this->assertSame($firstUserId, Siswa::findOrFail($pendaftar->siswa_id)->user_id);
        $this->assertSame($firstSiswaId, User::findOrFail($firstUserId)->siswa_id);
        Mail::assertSent(PpdbDaftarUlangCredentialsMail::class, 1);
    }

    public function test_nis_uses_pendaftar_year_when_active_year_matches(): void
    {
        $pendaftar = $this->makePendaftar('sudah');

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftar->refresh();
        $siswa = Siswa::findOrFail($pendaftar->siswa_id);

        $this->assertStringStartsWith('2627.1.', $siswa->nis);
        $this->assertSame(2026, (int) Angkatan::findOrFail($siswa->angkatan_id)->tahun);
    }

    public function test_nis_uses_pendaftar_year_even_when_active_year_is_later(): void
    {
        TahunAjaran::where('status', 'aktif')->update(['status' => 'selesai']);
        $pendaftarTahun = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'selesai',
        ]);
        TahunAjaran::create([
            'tahun' => '2027/2028',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        $pendaftar = PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-NIS-YEAR-' . bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => 'diterima',
            'status_daftar_ulang' => 'sudah',
            'tanggal_daftar_ulang' => '2026-09-30',
            'tahun_ajaran_id' => $pendaftarTahun->id,
            'nama' => 'PPDB nis year mismatch participant',
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
            'email' => 'ppdb-nis-year-' . bin2hex(random_bytes(4)) . '@example.test',
        ]);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success');

        $pendaftar->refresh();
        $siswa = Siswa::findOrFail($pendaftar->siswa_id);

        $this->assertStringStartsWith('2627.1.', $siswa->nis);
        $this->assertStringNotContainsString('2728.1.', $siswa->nis);
    }

    public function test_nis_sequence_continues_for_same_pendaftar_year(): void
    {
        $tahunAjaran = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        $first = PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-NIS-SEQ-1-' . bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => 'diterima',
            'status_daftar_ulang' => 'sudah',
            'tanggal_daftar_ulang' => '2026-09-30',
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'PPDB nis sequence first',
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
            'email' => 'ppdb-nis-seq-1-' . bin2hex(random_bytes(4)) . '@example.test',
        ]);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $first))
            ->assertRedirect()
            ->assertSessionHas('success');

        $second = PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-NIS-SEQ-2-' . bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => 'diterima',
            'status_daftar_ulang' => 'sudah',
            'tanggal_daftar_ulang' => '2026-09-30',
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'PPDB nis sequence second',
            'jenis_kelamin' => 'P',
            'nomor_kk' => '1234567890123457',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2019-02-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'Indonesia',
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'alamat' => 'Alamat peserta 2',
            'desa' => 'Desa 2',
            'kecamatan' => 'Kecamatan 2',
            'kabupaten' => 'Kabupaten 2',
            'provinsi' => 'Provinsi 2',
            'email' => 'ppdb-nis-seq-2-' . bin2hex(random_bytes(4)) . '@example.test',
        ]);

        $this->actingAs($this->makeTu())
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $second))
            ->assertRedirect()
            ->assertSessionHas('success');

        $firstSiswa = Siswa::findOrFail($first->fresh()->siswa_id);
        $secondSiswa = Siswa::findOrFail($second->fresh()->siswa_id);

        $this->assertSame(strlen($firstSiswa->nis), strlen($secondSiswa->nis));
        $this->assertStringStartsWith('2627.1.', $firstSiswa->nis);
        $this->assertStringStartsWith('2627.1.', $secondSiswa->nis);
        $this->assertNotSame($firstSiswa->nis, $secondSiswa->nis);
    }

    public function test_transaction_rolls_back_user_and_angkatan_if_siswa_creation_fails(): void
    {
        Mail::fake();
        $pendaftar = $this->makePendaftar('sudah');
        $tu = $this->makeTu();
        $userCount = User::count();
        $siswaCount = Siswa::count();
        $angkatanCount = Angkatan::count();
        $siswaCreatingEvent = 'eloquent.creating: ' . Siswa::class;

        Event::listen($siswaCreatingEvent, function () {
            throw new \RuntimeException('Simulated Siswa creation failure.');
        });

        $this->withoutExceptionHandling();
        $exception = null;

        try {
            $this->actingAs($tu)
                ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar));
        } catch (\RuntimeException $caught) {
            $exception = $caught;
        } finally {
            Event::forget($siswaCreatingEvent);
        }

        $this->assertSame('Simulated Siswa creation failure.', $exception?->getMessage());
        $this->assertSame($userCount, User::count());
        $this->assertSame($siswaCount, Siswa::count());
        $this->assertSame($angkatanCount, Angkatan::count());
        $this->assertNull($pendaftar->fresh()->siswa_id);
        $this->assertSame('sudah', $pendaftar->fresh()->status_daftar_ulang);
        Mail::assertNothingSent();
    }

    public function test_mail_delivery_failure_does_not_rollback_created_student_account(): void
    {
        $pendaftar = $this->makePendaftar('sudah');
        $tu = $this->makeTu();

        Mail::shouldReceive('to')
            ->once()
            ->with($pendaftar->email)
            ->andThrow(new \RuntimeException('Simulated mail transport failure.'));
        Log::shouldReceive('error')->once();

        $this->actingAs($tu)
            ->post(route('tu.ppdb.daftar-ulang.konfirmasi', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('success', 'Daftar ulang berhasil dikonfirmasi.')
            ->assertSessionHas('warning', 'Akun siswa berhasil dibuat, tetapi email informasi akun gagal dikirim.');

        $pendaftar->refresh();
        $siswa = Siswa::findOrFail($pendaftar->siswa_id);
        $user = User::findOrFail($siswa->user_id);

        $this->assertSame($siswa->id, $user->siswa_id);
        $this->assertTrue(Hash::check('01012018', $user->password));
    }

    private function makePendaftar(string $statusDaftarUlang, string $status = 'diterima'): PpdbPendaftar
    {
        $tahunAjaran = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        return PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-WORKFLOW-' . bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => $status,
            'status_daftar_ulang' => $statusDaftarUlang,
            'tanggal_daftar_ulang' => $statusDaftarUlang === 'sudah' ? '2026-09-30' : null,
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'PPDB workflow test participant',
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
            'email' => 'ppdb-workflow-' . bin2hex(random_bytes(4)) . '@example.test',
        ]);
    }

    private function makeTu(): User
    {
        return User::forceCreate([
            'name' => 'PPDB workflow TU operator',
            'email' => 'ppdb-workflow-' . bin2hex(random_bytes(4)) . '@example.test',
            'username' => 'ppdb-workflow-' . bin2hex(random_bytes(4)),
            'password' => 'test-password',
            'role' => 'tu',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }
}