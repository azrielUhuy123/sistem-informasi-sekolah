<?php

namespace Tests\Feature;

use App\Models\DataKepegawaian;
use App\Models\Angkatan;
use App\Models\Guru;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\MataPelajaran;
use App\Models\PengajuanJadwal;
use App\Models\PpdbPendaftar;
use App\Models\PpdbSetting;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use App\Mail\PpdbPendaftaranMail;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BusinessNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_created_pending_user_notifies_active_superadmins_only(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $otherSuperAdmin = $this->makeUser('superadmin');
        $inactiveSuperAdmin = $this->makeUser('superadmin', 'approved', false);
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New Teacher',
            'email' => 'new-teacher@example.test',
            'password' => 'password',
            'role' => 'guru',
        ])->assertRedirect(route('admin.users.index'));

        $pendingUser = User::where('email', 'new-teacher@example.test')->firstOrFail();

        $this->assertSame('pending', $pendingUser->approval_status);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(1, $otherSuperAdmin->notifications()->count());
        $this->assertSame(0, $inactiveSuperAdmin->notifications()->count());
        $this->assertSame(0, $pendingUser->notifications()->count());
        $this->assertNotification($superAdmin, [
            'title' => 'Akun menunggu persetujuan',
            'category' => 'user',
            'priority' => 'action_required',
            'action_url' => route('superadmin.users.pending'),
            'entity_id' => $pendingUser->id,
        ]);
    }

    public function test_tu_created_pending_account_uses_the_same_superadmin_notification(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $tu = $this->makeUser('tu');
        $pegawai = DataKepegawaian::create([
            'nama' => 'Pending Account Teacher',
            'nip' => 'NIP-'.uniqid(),
            'jabatan' => 'Guru',
            'tanggal_lahir' => '1990-01-01',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($tu)
            ->post(route('tu.datakepegawaian.create-account', $pegawai))
            ->assertRedirect();

        $pendingUser = User::where('pegawai_id', $pegawai->id)->firstOrFail();

        $this->assertSame('pending', $pendingUser->approval_status);
        $this->assertFalse((bool) $pendingUser->is_active);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertNotification($superAdmin, [
            'title' => 'Akun menunggu persetujuan',
            'category' => 'user',
            'priority' => 'action_required',
            'action_url' => route('superadmin.users.pending'),
            'entity_id' => $pendingUser->id,
        ]);
    }

    public function test_approval_transition_notifies_the_approved_user(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $otherUser = $this->makeUser('tu');
        $pendingUser = $this->makeUser('guru', 'pending', false);

        $this->actingAs($superAdmin)
            ->patch(route('superadmin.users.approve', $pendingUser))
            ->assertRedirect(route('superadmin.users.pending'));

        $pendingUser->refresh();

        $this->assertSame('approved', $pendingUser->approval_status);
        $this->assertTrue((bool) $pendingUser->is_active);
        $this->assertSame(1, $pendingUser->notifications()->count());
        $this->assertSame(0, $otherUser->notifications()->count());
        $this->assertSame(0, $superAdmin->notifications()->count());
        $this->assertNotification($pendingUser, [
            'title' => 'Akun disetujui',
            'category' => 'user',
            'priority' => 'success',
            'action_url' => route('profile.edit'),
            'entity_id' => $pendingUser->id,
        ]);
    }

    public function test_approval_without_pending_to_approved_transition_does_not_notify(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $alreadyApprovedUser = $this->makeUser('guru', 'approved', false);

        $this->actingAs($superAdmin)
            ->patch(route('superadmin.users.approve', $alreadyApprovedUser))
            ->assertRedirect(route('superadmin.users.pending'));

        $this->assertTrue((bool) $alreadyApprovedUser->fresh()->is_active);
        $this->assertSame(0, $alreadyApprovedUser->notifications()->count());
    }

    public function test_rejected_account_approval_notifies_the_user_once(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $rejectedUser = $this->makeUser('guru', 'rejected', false);

        $this->actingAs($superAdmin)
            ->patch(route('superadmin.users.approve', $rejectedUser))
            ->assertRedirect(route('superadmin.users.pending'));

        $rejectedUser->refresh();

        $this->assertSame('approved', $rejectedUser->approval_status);
        $this->assertTrue((bool) $rejectedUser->is_active);
        $this->assertSame(1, $rejectedUser->notifications()->count());
        $this->assertSame(0, $superAdmin->notifications()->count());
        $this->assertNotification($rejectedUser, [
            'title' => 'Akun disetujui',
            'message' => 'Akun Anda telah disetujui dan aktif.',
            'category' => 'user',
            'priority' => 'success',
            'action_url' => route('profile.edit'),
            'action_label' => 'Lihat profil',
            'entity_type' => User::class,
            'entity_id' => $rejectedUser->id,
        ]);

        $this->actingAs($superAdmin)
            ->patch(route('superadmin.users.approve', $rejectedUser))
            ->assertRedirect(route('superadmin.users.pending'));

        $this->assertSame(1, $rejectedUser->notifications()->count());
    }

    public function test_schedule_submit_notifies_once_for_a_multi_slot_batch(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $otherUser = $this->makeUser('admin');
        $fixture = $this->makeScheduleFixture();
        $firstSlot = $this->makeSchedule($fixture, 'draft', 1, 1);
        $secondSlot = $this->makeSchedule($fixture, 'rejected', 2, 2);

        $this->actingAs($fixture['teacher'])
            ->post(route('guru.jadwal-saya.submit'), ['kelas_id' => $fixture['kelas']->id])
            ->assertRedirect();

        $this->assertSame('submitted', $firstSlot->fresh()->status);
        $this->assertSame('submitted', $secondSlot->fresh()->status);
        $this->assertSame(1, $superAdmin->notifications()->count());
        $this->assertSame(0, $otherUser->notifications()->count());
        $this->assertSame(0, $fixture['teacher']->notifications()->count());
        $this->assertNotification($superAdmin, [
            'title' => 'Pengajuan jadwal menunggu persetujuan',
            'category' => 'jadwal',
            'priority' => 'action_required',
            'action_url' => route('superadmin.monitoring-jadwal.show', $fixture['kelas']),
            'entity_id' => $fixture['kelas']->id,
        ]);

        $this->actingAs($fixture['teacher'])
            ->post(route('guru.jadwal-saya.submit'), ['kelas_id' => $fixture['kelas']->id])
            ->assertRedirect();

        $this->assertSame(1, $superAdmin->notifications()->count());
    }

    public function test_draft_schedule_without_submit_does_not_notify(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $fixture = $this->makeScheduleFixture();
        $this->makeSchedule($fixture, 'draft', 1, 1);

        $this->assertSame('draft', $fixture['kelas']->pengajuanJadwal()->sole()->status);
        $this->assertSame(0, $superAdmin->notifications()->count());
    }

    public function test_schedule_approval_notifies_once_per_teacher_for_a_multi_slot_operation(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $otherTeacher = $this->makeUser('guru');
        $fixture = $this->makeScheduleFixture();
        $firstSlot = $this->makeSchedule($fixture, 'submitted', 1, 1);
        $secondSlot = $this->makeSchedule($fixture, 'submitted', 2, 2);

        $this->actingAs($superAdmin)
            ->post(route('superadmin.monitoring-jadwal.approve', $fixture['kelas']))
            ->assertRedirect();

        $this->assertSame('approved', $firstSlot->fresh()->status);
        $this->assertSame('approved', $secondSlot->fresh()->status);
        $this->assertSame(1, $fixture['teacher']->notifications()->count());
        $this->assertSame(0, $otherTeacher->notifications()->count());
        $this->assertSame(0, $superAdmin->notifications()->count());
        $this->assertNotification($fixture['teacher'], [
            'title' => 'Pengajuan jadwal disetujui',
            'category' => 'jadwal',
            'priority' => 'success',
            'action_url' => route('guru.status-validasi.index'),
            'entity_id' => $fixture['kelas']->id,
        ]);

        $this->actingAs($superAdmin)
            ->post(route('superadmin.monitoring-jadwal.approve', $fixture['kelas']))
            ->assertRedirect();

        $this->assertSame(1, $fixture['teacher']->notifications()->count());
    }

    public function test_schedule_rejection_notifies_teacher_with_the_recorded_reason(): void
    {
        $superAdmin = $this->makeUser('superadmin');
        $fixture = $this->makeScheduleFixture();
        $slot = $this->makeSchedule($fixture, 'submitted', 1, 1);

        $this->actingAs($superAdmin)
            ->post(route('superadmin.monitoring-jadwal.reject', $fixture['kelas']), [
                'alasan' => 'Guru berhalangan pada jam tersebut.',
                'hari' => $fixture['days'][0]->id,
                'jam' => $fixture['periods'][0]->id,
                'catatan_tambahan' => 'Silakan pilih slot lain.',
            ])
            ->assertRedirect();

        $this->assertSame('rejected', $slot->fresh()->status);
        $this->assertSame(1, $fixture['teacher']->notifications()->count());
        $this->assertNotification($fixture['teacher'], [
            'title' => 'Pengajuan jadwal ditolak',
            'category' => 'jadwal',
            'priority' => 'warning',
            'action_url' => route('guru.status-validasi.index'),
            'entity_id' => $slot->id,
        ]);
        $this->assertStringContainsString(
            'Guru berhalangan pada jam tersebut.',
            $fixture['teacher']->notifications()->sole()->data['message'],
        );
        $this->assertStringContainsString(
            'Silakan pilih slot lain.',
            $slot->fresh()->catatan,
        );

        $this->actingAs($superAdmin)
            ->post(route('superadmin.monitoring-jadwal.reject', $fixture['kelas']), [
                'alasan' => 'Guru berhalangan pada jam tersebut.',
                'hari' => $fixture['days'][0]->id,
                'jam' => $fixture['periods'][0]->id,
            ])
            ->assertRedirect();

        $this->assertSame(1, $fixture['teacher']->notifications()->count());
    }

    public function test_public_registration_notifies_every_eligible_tu_and_keeps_the_existing_email(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('aktif');
        PpdbSetting::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-12-31',
            'kuota' => 100,
            'status' => 'dibuka',
        ]);
        $firstTu = $this->makeUser('tu');
        $secondTu = $this->makeUser('tu');
        $admin = $this->makeUser('admin');
        $inactiveTu = $this->makeUser('tu', 'approved', false);
        $unapprovedTu = $this->makeUser('tu', 'pending');
        $email = 'ppdb-registration-'.uniqid().'@example.test';

        Mail::fake();
        Storage::fake('public');

        $this->post(route('ppdb.store'), $this->validRegistrationPayload($email))
            ->assertRedirect();

        $pendaftar = PpdbPendaftar::where('email', $email)->sole();

        $this->assertSame('pending', $pendaftar->status);
        $this->assertSame(1, $firstTu->notifications()->count());
        $this->assertSame(1, $secondTu->notifications()->count());
        $this->assertSame(0, $admin->notifications()->count());
        $this->assertSame(0, $inactiveTu->notifications()->count());
        $this->assertSame(0, $unapprovedTu->notifications()->count());
        $this->assertSame(0, DB::table('notifications')
            ->where('notifiable_type', PpdbPendaftar::class)
            ->count());

        $expected = [
            'title' => 'Pendaftaran PPDB menunggu verifikasi',
            'message' => "Pendaftar {$pendaftar->no_pendaftaran} ({$pendaftar->nama}) menunggu verifikasi.",
            'category' => 'ppdb',
            'priority' => 'action_required',
            'action_url' => route('tu.ppdb.verifikasi.index'),
            'action_label' => 'Verifikasi pendaftar',
            'entity_type' => PpdbPendaftar::class,
            'entity_id' => $pendaftar->id,
        ];
        $this->assertNotification($firstTu, $expected);
        $this->assertNotification($secondTu, $expected);

        Mail::assertSent(PpdbPendaftaranMail::class, function (PpdbPendaftaranMail $mail) use ($pendaftar, $email) {
            return $mail->pendaftar->is($pendaftar)
                && $mail->hasTo($email)
                && $mail->statusUrl !== null;
        });
    }

    public function test_public_registration_is_rejected_if_ppdb_closes_after_form_is_opened(): void
    {
        $tahunAjaran = $this->makeTahunAjaran('aktif');
        $setting = PpdbSetting::create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-12-31',
            'kuota' => 100,
            'status' => 'dibuka',
        ]);
        $tu = $this->makeUser('tu');
        $email = 'ppdb-closed-after-form-'.uniqid().'@example.test';

        Mail::fake();
        Storage::fake('public');

        $this->get(route('ppdb.create'))->assertOk();
        $setting->update(['status' => 'ditutup']);

        $this->post(route('ppdb.store'), $this->validRegistrationPayload($email))
            ->assertForbidden();

        $this->assertSame(0, PpdbPendaftar::count());
        $this->assertSame(0, $tu->notifications()->count());
        $this->assertSame([], Storage::disk('public')->allFiles());
        Mail::assertNothingSent();
    }

    public function test_failed_public_registration_does_not_notify_or_send_email(): void
    {
        $this->makeTahunAjaran('aktif');
        $tu = $this->makeUser('tu');
        Mail::fake();

        $this->post(route('ppdb.store'), [])
            ->assertSessionHasErrors(['nama', 'email', 'kartu_keluarga']);

        $this->assertSame(0, $tu->notifications()->count());
        $this->assertSame(0, PpdbPendaftar::count());
        Mail::assertNothingSent();
    }

    public function test_public_daftar_ulang_transition_notifies_only_eligible_tu_users(): void
    {
        $tahun = $this->makeTahunAjaran('aktif');
        $firstTu = $this->makeUser('tu');
        $secondTu = $this->makeUser('tu');
        $otherUser = $this->makeUser('admin');
        $inactiveTu = $this->makeUser('tu', 'approved', false);
        $unapprovedTu = $this->makeUser('tu', 'pending');
        $pendaftar = $this->makeAcceptedPendaftar($tahun);
        Mail::fake();

        $this->post(route('ppdb.daftar-ulang.simpan'), [
            'no_pendaftaran' => $pendaftar->no_pendaftaran,
            'tanggal_lahir' => '2018-01-01',
        ])->assertRedirect()->assertSessionHas('success', 'Daftar ulang berhasil');

        $pendaftar->refresh();
        $this->assertSame('sudah', $pendaftar->status_daftar_ulang);
        $this->assertSame(1, $firstTu->notifications()->count());
        $this->assertSame(1, $secondTu->notifications()->count());
        $this->assertSame(0, $otherUser->notifications()->count());
        $this->assertSame(0, $inactiveTu->notifications()->count());
        $this->assertSame(0, $unapprovedTu->notifications()->count());

        $expected = [
            'title' => 'Daftar ulang PPDB perlu dikonfirmasi',
            'message' => "Pendaftar {$pendaftar->no_pendaftaran} ({$pendaftar->nama}) telah menyelesaikan daftar ulang.",
            'category' => 'ppdb',
            'priority' => 'action_required',
            'action_url' => route('tu.ppdb.daftar-ulang.index'),
            'action_label' => 'Konfirmasi daftar ulang',
            'entity_type' => PpdbPendaftar::class,
            'entity_id' => $pendaftar->id,
        ];
        $this->assertNotification($firstTu, $expected);
        $this->assertNotification($secondTu, $expected);
        Mail::assertNothingSent();
    }

    public function test_already_completed_daftar_ulang_retry_does_not_create_another_notification(): void
    {
        $tahun = $this->makeTahunAjaran('aktif');
        $tu = $this->makeUser('tu');
        $pendaftar = $this->makeAcceptedPendaftar($tahun, 'sudah');

        $this->post(route('ppdb.daftar-ulang.simpan'), [
            'no_pendaftaran' => $pendaftar->no_pendaftaran,
            'tanggal_lahir' => '2018-01-01',
        ])->assertRedirect()->assertSessionHas('error', 'Daftar ulang sudah pernah dikonfirmasi.');

        $this->assertSame(0, $tu->notifications()->count());
    }

    public function test_ppdb_tu_notification_is_not_visible_through_another_users_relation(): void
    {
        $tahun = $this->makeTahunAjaran('aktif');
        $recipient = $this->makeUser('tu');
        $otherTu = $this->makeUser('tu');
        $pendaftar = $this->makeAcceptedPendaftar($tahun);

        $this->post(route('ppdb.daftar-ulang.simpan'), [
            'no_pendaftaran' => $pendaftar->no_pendaftaran,
            'tanggal_lahir' => '2018-01-01',
        ])->assertRedirect()->assertSessionHas('success');

        $notification = $recipient->notifications()->sole();
        $this->assertNull($otherTu->notifications()->find($notification->getKey()));
        $this->assertSame($recipient->getKey(), $notification->notifiable_id);
    }

    private function makeUser(string $role, string $approvalStatus = 'approved', bool $active = true): User
    {
        return User::create([
            'name' => ucfirst($role).' notification test',
            'email' => $role.'-'.uniqid().'@example.test',
            'username' => $role.'-'.uniqid(),
            'password' => 'password',
            'role' => $role,
            'approval_status' => $approvalStatus,
            'approved_at' => $approvalStatus === 'approved' ? now() : null,
            'is_active' => $active,
        ]);
    }

    private function assertNotification(User $recipient, array $expected): void
    {
        $notification = $recipient->notifications()->sole();

        foreach ($expected as $key => $value) {
            $this->assertSame($value, data_get($notification->data, $key));
        }
    }

    private function makeScheduleFixture(): array
    {
        $tahun = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $angkatan = Angkatan::create(['tahun' => 2024]);
        $kelasMaster = KelasMaster::firstOrCreate(['tingkat' => 'I']);
        $kelasGrup = KelasGrup::create([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $kelasMaster->id,
        ]);
        $kelas = Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => 'I-A',
        ]);
        $pegawai = DataKepegawaian::create(['nama' => 'Schedule Teacher']);
        $guru = Guru::create(['data_kepegawaian_id' => $pegawai->id]);
        $teacher = User::create([
            'pegawai_id' => $pegawai->id,
            'name' => 'Schedule Teacher',
            'email' => 'schedule-teacher-'.uniqid().'@example.test',
            'username' => 'schedule-teacher-'.uniqid(),
            'password' => 'password',
            'role' => 'guru',
            'approval_status' => 'approved',
            'approved_at' => now(),
            'is_active' => true,
        ]);
        $mapel = MataPelajaran::create(['nama_mapel' => 'Matematika']);
        $days = [
            HariSekolah::create(['nama' => 'Senin', 'urutan' => 1]),
            HariSekolah::create(['nama' => 'Selasa', 'urutan' => 2]),
        ];
        $periods = [
            JamPelajaran::create(['jam_ke' => 1, 'mulai' => '08:00', 'selesai' => '08:40']),
            JamPelajaran::create(['jam_ke' => 2, 'mulai' => '08:45', 'selesai' => '09:25']),
        ];

        WaliKelas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $kelas->id,
            'tahun_ajaran_id' => $tahun->id,
        ]);

        return compact('tahun', 'kelas', 'guru', 'teacher', 'mapel', 'days', 'periods');
    }

    private function makeSchedule(array $fixture, string $status, int $dayIndex, int $periodIndex): PengajuanJadwal
    {
        return PengajuanJadwal::create([
            'guru_id' => $fixture['guru']->id,
            'kelas_id' => $fixture['kelas']->id,
            'mapel_id' => $fixture['mapel']->id,
            'hari_id' => $fixture['days'][$dayIndex - 1]->id,
            'jam_pelajaran_id' => $fixture['periods'][$periodIndex - 1]->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => $status,
        ]);
    }

    private function makeTahunAjaran(string $status): TahunAjaran
    {
        return TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => $status,
        ]);
    }

    private function makeAcceptedPendaftar(TahunAjaran $tahun, string $daftarUlangStatus = 'belum'): PpdbPendaftar
    {
        return PpdbPendaftar::create([
            'no_pendaftaran' => 'PPDB-TEST-'.bin2hex(random_bytes(6)),
            'tanggal_pendaftaran' => now()->toDateString(),
            'status' => 'diterima',
            'status_daftar_ulang' => $daftarUlangStatus,
            'tahun_ajaran_id' => $tahun->id,
            'nama' => 'PPDB Daftar Ulang Test',
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
        ]);
    }

    private function validRegistrationPayload(string $email): array
    {
        return [
            'nama' => 'PPDB Public Registration Test',
            'jenis_kelamin' => 'L',
            'nik' => (string) random_int(1000000000000000, 9999999999999999),
            'nomor_kk' => (string) random_int(1000000000000000, 9999999999999999),
            'tempat_lahir' => 'Test City',
            'tanggal_lahir' => '2018-01-01',
            'agama' => 'Islam',
            'kewarganegaraan' => 'Indonesia',
            'provinsi' => 'Test Province',
            'kabupaten' => 'Test Regency',
            'kecamatan' => 'Test District',
            'desa' => 'Test Village',
            'rt' => '1',
            'rw' => '2',
            'alamat' => 'Test address',
            'jenis_pendidikan_asal' => 'Belum Pernah Sekolah',
            'nama_sekolah_asal' => null,
            'nisn_sekolah_asal' => null,
            'nama_ayah' => 'Nama Ayah',
            'tempat_lahir_ayah' => 'Test City',
            'tanggal_lahir_ayah' => '1980-01-01',
            'agama_ayah' => 'Islam',
            'pendidikan_ayah' => 'SMA/SMK',
            'pekerjaan_ayah' => 'Guru',
            'nama_ibu' => 'Nama Ibu',
            'tempat_lahir_ibu' => 'Test City',
            'tanggal_lahir_ibu' => '1982-01-01',
            'agama_ibu' => 'Islam',
            'pendidikan_ibu' => 'SMA/SMK',
            'pekerjaan_ibu' => 'Pegawai Swasta',
            'nomor_hp' => '081234567890',
            'email' => $email,
            'email_confirmation' => $email,
            'tinggi_badan' => '100.0',
            'berat_badan' => '20.0',
            'anak_ke' => '1',
            'jumlah_saudara' => '0',
            'kartu_keluarga' => UploadedFile::fake()->create('kk.pdf', 10, 'application/pdf'),
            'ktp' => UploadedFile::fake()->create('ktp.pdf', 10, 'application/pdf'),
            'foto' => UploadedFile::fake()->image('foto.jpg'),
            'declaration' => 'yes',
        ];
    }
}
