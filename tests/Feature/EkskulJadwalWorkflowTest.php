<?php

namespace Tests\Feature;

use App\Models\DataKepegawaian;
use App\Models\Ekskul;
use App\Models\Guru;
use App\Models\HariSekolah;
use App\Models\PembinaEkskul;
use App\Models\PengajuanJadwalEkskul;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EkskulJadwalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_create_a_draft_and_submit_it_through_edit(): void
    {
        $fixture = $this->makeFixture();
        $inactiveSuperAdmin = $this->makeNotificationUser('superadmin', false);
        $admin = $this->makeNotificationUser('admin');

        $this->actingAs($fixture['guruUser'])
            ->post(route('guru.ekskul.jadwal.store'), [
                'hari_id' => $fixture['hari']->id,
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:00',
            ])
            ->assertRedirect();

        $jadwal = PengajuanJadwalEkskul::sole();
        $this->assertSame('draft', $jadwal->status);
        $this->assertSame($fixture['activeYear']->id, $jadwal->tahun_ajaran_id);
        $this->assertSame(0, $fixture['superAdmin']->notifications()->count());

        $this->actingAs($fixture['guruUser'])
            ->from(route('guru.ekskul.jadwal.edit', $jadwal))
            ->put(route('guru.ekskul.jadwal.update', $jadwal), [
                'hari_id' => $fixture['hari']->id,
                'jam_mulai' => '09:00',
                'jam_selesai' => '10:00',
            ])
            ->assertRedirect(route('guru.ekskul.validasi.index'))
            ->assertSessionHas('success');

        $this->assertSame('submitted', $jadwal->fresh()->status);
        $this->assertSame(1, $fixture['superAdmin']->notifications()->count());
        $this->assertSame(1, $fixture['secondSuperAdmin']->notifications()->count());
        $this->assertSame(0, $inactiveSuperAdmin->notifications()->count());
        $this->assertSame(0, $admin->notifications()->count());
        $this->assertSame(0, $fixture['guruUser']->notifications()->count());
        $this->assertExtracurricularNotification($fixture['superAdmin'], $fixture['ekskul']);
        $this->assertExtracurricularNotification($fixture['secondSuperAdmin'], $fixture['ekskul']);

        $this->actingAs($fixture['guruUser'])
            ->put(route('guru.ekskul.jadwal.update', $jadwal), [
                'hari_id' => $fixture['hari']->id,
                'jam_mulai' => '10:00',
                'jam_selesai' => '11:00',
            ])
            ->assertRedirect(route('guru.ekskul.validasi.index'))
            ->assertSessionHas('success');

        $this->assertSame(1, $fixture['superAdmin']->notifications()->count());
        $this->assertSame(1, $fixture['secondSuperAdmin']->notifications()->count());
    }

    public function test_invalid_submission_does_not_notify_superadmins(): void
    {
        $fixture = $this->makeFixture();
        $jadwal = $this->makeSchedule($fixture, 'draft');

        $this->actingAs($fixture['guruUser'])
            ->from(route('guru.ekskul.jadwal.edit', $jadwal))
            ->put(route('guru.ekskul.jadwal.update', $jadwal), [
                'hari_id' => $fixture['hari']->id,
                'jam_mulai' => '',
                'jam_selesai' => '10:00',
            ])
            ->assertRedirect(route('guru.ekskul.jadwal.edit', $jadwal))
            ->assertSessionHasErrors(['jam_mulai']);

        $this->assertSame('draft', $jadwal->fresh()->status);
        $this->assertSame(0, $fixture['superAdmin']->notifications()->count());
    }

    public function test_guru_can_resubmit_a_rejected_schedule_and_clears_the_rejection_note(): void
    {
        $fixture = $this->makeFixture();
        $jadwal = $this->makeSchedule($fixture, 'rejected', 1, 'Please correct the time.');

        $this->actingAs($fixture['guruUser'])
            ->put(route('guru.ekskul.jadwal.update', $jadwal), [
                'hari_id' => $fixture['hari']->id,
                'jam_mulai' => '10:00',
                'jam_selesai' => '11:00',
            ])
            ->assertRedirect(route('guru.ekskul.validasi.index'))
            ->assertSessionHas('success');

        $this->assertSame('submitted', $jadwal->fresh()->status);
        $this->assertNull($jadwal->fresh()->catatan);
        $this->assertSame(1, $fixture['superAdmin']->notifications()->count());
        $this->assertExtracurricularNotification($fixture['superAdmin'], $fixture['ekskul']);
    }

    public function test_submitted_schedule_remains_submitted_when_edited(): void
    {
        $fixture = $this->makeFixture();
        $jadwal = $this->makeSchedule($fixture, 'submitted', 1, 'Existing note');

        $this->actingAs($fixture['guruUser'])
            ->put(route('guru.ekskul.jadwal.update', $jadwal), [
                'hari_id' => $fixture['hari']->id,
                'jam_mulai' => '15:00',
                'jam_selesai' => '16:00',
            ])
            ->assertRedirect(route('guru.ekskul.validasi.index'))
            ->assertSessionHas('success');

        $this->assertSame('submitted', $jadwal->fresh()->status);
        $this->assertSame('15:00', substr($jadwal->fresh()->jam_mulai, 0, 5));
        $this->assertNull($jadwal->fresh()->catatan);
        $this->assertSame(0, $fixture['superAdmin']->notifications()->count());
    }

    public function test_approved_and_published_schedules_cannot_be_forced_back_to_submitted(): void
    {
        $fixture = $this->makeFixture();

        foreach (['approved', 'published'] as $status) {
            $jadwal = $this->makeSchedule($fixture, $status, 1, 'Existing note');

            $this->actingAs($fixture['guruUser'])
                ->from(route('guru.ekskul.jadwal.edit', $jadwal))
                ->put(route('guru.ekskul.jadwal.update', $jadwal), [
                    'hari_id' => $fixture['hari']->id,
                    'jam_mulai' => '15:00',
                    'jam_selesai' => '16:00',
                ])
                ->assertRedirect(route('guru.ekskul.jadwal.edit', $jadwal))
                ->assertSessionHas('error');

            $this->assertSame($status, $jadwal->fresh()->status);
            $this->assertSame('Existing note', $jadwal->fresh()->catatan);
            $this->assertSame('08:00:00', $jadwal->fresh()->jam_mulai);
            $this->assertSame(0, $fixture['superAdmin']->notifications()->count());
        }
    }

    public function test_guru_cannot_edit_another_gurus_schedule(): void
    {
        $fixture = $this->makeFixture();
        $otherGuru = $this->makeGuruUser('other-guru');
        $jadwal = $this->makeSchedule($fixture, 'draft');

        $this->actingAs($otherGuru['user'])
            ->put(route('guru.ekskul.jadwal.update', $jadwal), [
                'hari_id' => $fixture['hari']->id,
                'jam_mulai' => '09:00',
                'jam_selesai' => '10:00',
            ])
            ->assertForbidden();

        $this->assertSame('draft', $jadwal->fresh()->status);
        $this->assertSame(0, $fixture['superAdmin']->notifications()->count());
    }

    public function test_superadmin_approval_only_updates_submitted_active_year_schedules(): void
    {
        $fixture = $this->makeFixture();
        $activeSubmitted = $this->makeSchedule($fixture, 'submitted', 1, 'Old note');
        $activeSubmittedSecond = $this->makeSchedule($fixture, 'submitted', 6, 'Another note');
        $activeDraft = $this->makeSchedule($fixture, 'draft', 2);
        $activeRejected = $this->makeSchedule($fixture, 'rejected', 3, 'Keep rejection');
        $activeApproved = $this->makeSchedule($fixture, 'approved', 4);
        $activePublished = $this->makeSchedule($fixture, 'published', 5);
        $historicalSubmitted = $this->makeSchedule($fixture, 'submitted', 1, 'Historical note', $fixture['previousYear']);
        $historicalDraft = $this->makeSchedule($fixture, 'draft', 2, null, $fixture['previousYear']);
        $historicalApproved = $this->makeSchedule($fixture, 'approved', 3, null, $fixture['previousYear']);
        $historicalPublished = $this->makeSchedule($fixture, 'published', 4, null, $fixture['previousYear']);

        $this->actingAs($fixture['superAdmin'])
            ->post(route('superadmin.monitoring-ekskul.approve', $fixture['ekskul']))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('approved', $activeSubmitted->fresh()->status);
        $this->assertNull($activeSubmitted->fresh()->catatan);
        $this->assertSame('approved', $activeSubmittedSecond->fresh()->status);
        $this->assertNull($activeSubmittedSecond->fresh()->catatan);
        $this->assertSame('draft', $activeDraft->fresh()->status);
        $this->assertSame('rejected', $activeRejected->fresh()->status);
        $this->assertSame('Keep rejection', $activeRejected->fresh()->catatan);
        $this->assertSame('approved', $activeApproved->fresh()->status);
        $this->assertSame('published', $activePublished->fresh()->status);
        $this->assertSame('submitted', $historicalSubmitted->fresh()->status);
        $this->assertSame('Historical note', $historicalSubmitted->fresh()->catatan);
        $this->assertSame('draft', $historicalDraft->fresh()->status);
        $this->assertSame('approved', $historicalApproved->fresh()->status);
        $this->assertSame('published', $historicalPublished->fresh()->status);
    }

    public function test_superadmin_rejection_only_updates_submitted_active_year_schedules(): void
    {
        $fixture = $this->makeFixture();
        $activeSubmitted = $this->makeSchedule($fixture, 'submitted', 1);
        $activeSubmittedSecond = $this->makeSchedule($fixture, 'submitted', 6);
        $activeDraft = $this->makeSchedule($fixture, 'draft', 2);
        $activeApproved = $this->makeSchedule($fixture, 'approved', 3, 'Approved note');
        $activePublished = $this->makeSchedule($fixture, 'published', 4);
        $historicalSubmitted = $this->makeSchedule($fixture, 'submitted', 1, 'Historical note', $fixture['previousYear']);
        $historicalApproved = $this->makeSchedule($fixture, 'approved', 2, null, $fixture['previousYear']);
        $historicalPublished = $this->makeSchedule($fixture, 'published', 3, null, $fixture['previousYear']);

        $this->actingAs($fixture['superAdmin'])
            ->post(route('superadmin.monitoring-ekskul.reject', $fixture['ekskul']), [
                'catatan' => 'Please revise the schedule.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('rejected', $activeSubmitted->fresh()->status);
        $this->assertSame('Please revise the schedule.', $activeSubmitted->fresh()->catatan);
        $this->assertSame('rejected', $activeSubmittedSecond->fresh()->status);
        $this->assertSame('Please revise the schedule.', $activeSubmittedSecond->fresh()->catatan);
        $this->assertSame('draft', $activeDraft->fresh()->status);
        $this->assertSame('approved', $activeApproved->fresh()->status);
        $this->assertSame('Approved note', $activeApproved->fresh()->catatan);
        $this->assertSame('published', $activePublished->fresh()->status);
        $this->assertSame('submitted', $historicalSubmitted->fresh()->status);
        $this->assertSame('Historical note', $historicalSubmitted->fresh()->catatan);
        $this->assertSame('approved', $historicalApproved->fresh()->status);
        $this->assertSame('published', $historicalPublished->fresh()->status);
    }

    public function test_superadmin_can_approve_and_reject_but_guru_cannot_use_monitoring_actions(): void
    {
        $fixture = $this->makeFixture();
        $submitted = $this->makeSchedule($fixture, 'submitted');

        $this->actingAs($fixture['guruUser'])
            ->post(route('superadmin.monitoring-ekskul.approve', $fixture['ekskul']))
            ->assertRedirect('/guru/dashboard');

        $this->assertSame('submitted', $submitted->fresh()->status);

        $this->actingAs($fixture['superAdmin'])
            ->post(route('superadmin.monitoring-ekskul.approve', $fixture['ekskul']))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertSame('approved', $submitted->fresh()->status);

        $this->actingAs($fixture['superAdmin'])
            ->post(route('superadmin.monitoring-ekskul.reject', $fixture['ekskul']), [
                'catatan' => 'Rejected after approval attempt.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertSame('approved', $submitted->fresh()->status);
    }

    public function test_publish_only_publishes_approved_active_year_schedules(): void
    {
        $fixture = $this->makeFixture();
        $activeApproved = $this->makeSchedule($fixture, 'approved', 1);
        $activeSubmitted = $this->makeSchedule($fixture, 'submitted', 2);
        $activeDraft = $this->makeSchedule($fixture, 'draft', 3);
        $historicalApproved = $this->makeSchedule($fixture, 'approved', 1, null, $fixture['previousYear']);
        $historicalPublished = $this->makeSchedule($fixture, 'published', 2, null, $fixture['previousYear']);

        $this->actingAs($fixture['superAdmin'])
            ->post(route('superadmin.monitoring-ekskul.publish', $fixture['ekskul']))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('published', $activeApproved->fresh()->status);
        $this->assertSame('submitted', $activeSubmitted->fresh()->status);
        $this->assertSame('draft', $activeDraft->fresh()->status);
        $this->assertSame('approved', $historicalApproved->fresh()->status);
        $this->assertSame('published', $historicalPublished->fresh()->status);
    }

    public function test_approval_and_publish_still_stop_on_active_year_conflicts(): void
    {
        $fixture = $this->makeFixture();
        $submitted = $this->makeSchedule($fixture, 'submitted', 1);
        $approved = $this->makeSchedule($fixture, 'approved', 2);
        $publishedOne = $this->makeSchedule($fixture, 'published', 1);
        $publishedTwo = $this->makeSchedule($fixture, 'published', 2);

        $this->actingAs($fixture['superAdmin'])
            ->post(route('superadmin.monitoring-ekskul.approve', $fixture['ekskul']))
            ->assertRedirect()
            ->assertSessionHas('error', 'Masih terdapat jadwal yang bentrok.');

        $this->assertSame('submitted', $submitted->fresh()->status);

        $this->actingAs($fixture['superAdmin'])
            ->post(route('superadmin.monitoring-ekskul.publish', $fixture['ekskul']))
            ->assertRedirect()
            ->assertSessionHas('error', 'Masih terdapat jadwal yang bentrok.');

        $this->assertSame('approved', $approved->fresh()->status);
        $this->assertSame('published', $publishedOne->fresh()->status);
        $this->assertSame('published', $publishedTwo->fresh()->status);
    }

    private function makeFixture(): array
    {
        $activeYear = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $previousYear = TahunAjaran::create([
            'tahun' => '2025/2026',
            'semester' => 'Ganjil',
            'status' => 'selesai',
        ]);
        $hari = HariSekolah::create(['nama' => 'Senin', 'urutan' => 1]);
        $ekskul = Ekskul::create(['nama' => 'Basket']);
        $guru = $this->makeGuruUser('guru');

        PembinaEkskul::create([
            'guru_id' => $guru['record']->id,
            'ekskul_id' => $ekskul->id,
            'tahun_ajaran_id' => $activeYear->id,
        ]);

        $superAdmin = User::create([
            'name' => 'SuperAdmin test',
            'email' => 'superadmin-'.uniqid().'@example.test',
            'username' => 'superadmin-'.uniqid(),
            'password' => 'test-password',
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
        $secondSuperAdmin = $this->makeNotificationUser('superadmin');

        return compact(
            'activeYear',
            'previousYear',
            'hari',
            'ekskul',
            'guru',
            'superAdmin',
            'secondSuperAdmin'
        )
            + ['guruUser' => $guru['user']];
    }

    private function makeNotificationUser(string $role, bool $active = true): User
    {
        $unique = uniqid();

        return User::create([
            'name' => ucfirst($role).' notification test',
            'email' => $role.'-'.$unique.'@example.test',
            'username' => $role.'-'.$unique,
            'password' => 'test-password',
            'role' => $role,
            'approval_status' => 'approved',
            'is_active' => $active,
        ]);
    }

    private function assertExtracurricularNotification(User $recipient, Ekskul $ekskul): void
    {
        $notification = $recipient->notifications()->sole();
        $expected = [
            'title' => 'Pengajuan jadwal ekstrakurikuler menunggu pemeriksaan',
            'message' => "Pengajuan jadwal ekstrakurikuler {$ekskul->nama} menunggu pemeriksaan SuperAdmin.",
            'category' => 'jadwal',
            'priority' => 'action_required',
            'action_url' => route('superadmin.monitoring-ekskul.show', $ekskul),
            'action_label' => 'Lihat Pengajuan',
            'entity_type' => Ekskul::class,
            'entity_id' => $ekskul->getKey(),
        ];
        $payload = json_encode($notification->data);

        $this->assertSame($expected, $notification->data);
        $this->assertStringNotContainsString('password', strtolower($payload));
        $this->assertStringNotContainsString('credential', strtolower($payload));
        $this->assertStringNotContainsString('nik', strtolower($payload));
        $this->assertStringNotContainsString('siswa', strtolower($payload));
    }

    private function makeGuruUser(string $label): array
    {
        $unique = uniqid();
        $pegawai = DataKepegawaian::create([
            'nama' => $label,
            'nip' => 'NIP-'.$unique,
        ]);
        $record = Guru::create(['data_kepegawaian_id' => $pegawai->id]);
        $user = User::create([
            'name' => $label,
            'email' => $label.'-'.$unique.'@example.test',
            'username' => $label.'-'.$unique,
            'password' => 'test-password',
            'role' => 'guru',
            'approval_status' => 'approved',
            'is_active' => true,
            'pegawai_id' => $pegawai->id,
        ]);

        return compact('record', 'user');
    }

    private function makeSchedule(
        array $fixture,
        string $status,
        int $slot = 1,
        ?string $catatan = null,
        ?TahunAjaran $tahun = null
    ): PengajuanJadwalEkskul {
        $startHour = 7 + $slot;

        return PengajuanJadwalEkskul::create([
            'ekskul_id' => $fixture['ekskul']->id,
            'guru_id' => $fixture['guru']['record']->id,
            'hari_id' => $fixture['hari']->id,
            'tahun_ajaran_id' => ($tahun ?? $fixture['activeYear'])->id,
            'jam_mulai' => sprintf('%02d:00:00', $startHour),
            'jam_selesai' => sprintf('%02d:30:00', $startHour),
            'status' => $status,
            'catatan' => $catatan,
        ]);
    }
}
