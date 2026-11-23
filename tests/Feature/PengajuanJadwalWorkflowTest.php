<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\Guru;
use App\Models\HariSekolah;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\MataPelajaran;
use App\Models\PengajuanJadwal;
use App\Models\PengampuMapel;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PengajuanJadwalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_views_only_own_submission_details_and_assigned_class_grid(): void
    {
        $fixture = $this->makeFixture();
        $own = $this->makeSchedule(
            $fixture,
            $fixture['guruA'],
            $fixture['kelasA'],
            $fixture['mapelA'],
            1,
            1
        );
        $other = $this->makeSchedule(
            $fixture,
            $fixture['guruB'],
            $fixture['kelasA'],
            $fixture['mapelB'],
            1,
            2,
            'submitted'
        );

        $this->actingAs($fixture['teacherA'])
            ->get(route('guru.pengajuan-jadwal.index'))
            ->assertOk()
            ->assertSee($fixture['mapelA']->nama_mapel)
            ->assertDontSee($fixture['mapelB']->nama_mapel)
            ->assertViewHas('items', fn ($items) => $items->modelKeys() === [$own->id]);

        $this->actingAs($fixture['teacherB'])
            ->get(route('guru.pengajuan-jadwal.index'))
            ->assertOk()
            ->assertSee($fixture['mapelB']->nama_mapel)
            ->assertDontSee($fixture['mapelA']->nama_mapel)
            ->assertViewHas('items', fn ($items) => $items->modelKeys() === [$other->id]);

        $this->actingAs($fixture['teacherA'])
            ->get(route('guru.jadwal-saya.index', [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
            ]))
            ->assertOk()
            ->assertSee('Slot terisi')
            ->assertDontSee($fixture['mapelB']->nama_mapel)
            ->assertDontSee('Submitted');

        $this->actingAs($fixture['teacherA'])
            ->get(route('guru.jadwal-saya.index', [
                'kelas_id' => $fixture['kelasB']->id,
            ]))
            ->assertForbidden();

        $this->assertDatabaseHas('pengajuan_jadwal', [
            'id' => $other->id,
            'guru_id' => $fixture['guruB']->id,
        ]);
    }

    public function test_create_options_and_store_are_limited_to_the_logged_in_teacher_assignments(): void
    {
        $fixture = $this->makeFixture();

        $this->actingAs($fixture['teacherA'])
            ->get(route('guru.pengajuan-jadwal.create'))
            ->assertOk()
            ->assertViewHas('kelas', fn ($items) =>
                $items->pluck('id')->all() === [$fixture['kelasA']->id]
            )
            ->assertViewHas('mapel', fn ($items) =>
                $items->pluck('id')->all() === [$fixture['mapelA']->id]
            );

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $fixture['periods'][0]->id,
                'guru_id' => $fixture['guruB']->id,
            ])
            ->assertRedirect(route('guru.pengajuan-jadwal.index'));

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => 999999,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $fixture['periods'][1]->id,
            ])
            ->assertSessionHasErrors('kelas_id');

        $this->assertDatabaseHas('pengajuan_jadwal', [
            'guru_id' => $fixture['guruA']->id,
            'kelas_id' => $fixture['kelasA']->id,
            'mapel_id' => $fixture['mapelA']->id,
            'status' => 'draft',
        ]);

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => $fixture['kelasB']->id,
                'mapel_id' => $fixture['mapelB']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $fixture['periods'][1]->id,
                'guru_id' => $fixture['guruB']->id,
            ])
            ->assertRedirect(route('guru.pengajuan-jadwal.create'))
            ->assertSessionHas('error', 'Anda tidak memiliki akses ke kelas ini.');

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelB']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $fixture['periods'][1]->id,
            ])
            ->assertRedirect(route('guru.pengajuan-jadwal.create'))
            ->assertSessionHas('error', 'Anda tidak memiliki assignment mapel ini.');

        $this->assertSame(1, PengajuanJadwal::count());
    }

    public function test_validation_rejects_conflicts_and_preserves_subject_quota_on_both_entry_points(): void
    {
        $fixture = $this->makeFixture(mapelAQuota: 1);
        $this->makeSchedule(
            $fixture,
            $fixture['guruA'],
            $fixture['kelasA'],
            $fixture['mapelA'],
            1,
            1
        );

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $fixture['periods'][0]->id,
            ])
            ->assertSessionHas('error', 'Slot sudah terisi');

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $fixture['periods'][1]->id,
            ])
            ->assertSessionHas('error', 'JP mapel sudah penuh');

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.jadwal-saya.index'))
            ->post(route('guru.jadwal-saya.pilih-slot'), [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][1]->id,
                'jam_id' => $fixture['periods'][0]->id,
            ])
            ->assertSessionHas('error', 'JP mapel sudah penuh');

        $this->assertSame(1, PengajuanJadwal::count());
    }

    public function test_teacher_cannot_create_a_conflicting_cross_class_slot_or_exceed_class_quota(): void
    {
        $fixture = $this->makeFixture(mapelAQuota: 40);
        PengampuMapel::create([
            'guru_id' => $fixture['guruA']->id,
            'kelas_id' => $fixture['kelasB']->id,
            'mata_pelajaran_id' => $fixture['mapelA']->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
        ]);
        $this->makeSchedule(
            $fixture,
            $fixture['guruA'],
            $fixture['kelasB'],
            $fixture['mapelA'],
            1,
            1
        );

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $fixture['periods'][0]->id,
            ])
            ->assertSessionHas('error', 'Guru sudah memiliki jadwal pada slot ini.');

        for ($index = 0; $index < 29; $index++) {
            $period = JamPelajaran::create([
                'jam_ke' => $index + 4,
                'mulai' => sprintf('08:%02d:00', $index),
                'selesai' => sprintf('08:%02d:00', $index + 1),
            ]);

            PengajuanJadwal::create([
                'guru_id' => $fixture['guruA']->id,
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][0]->id,
                'jam_pelajaran_id' => $period->id,
                'tahun_ajaran_id' => $fixture['tahun']->id,
                'status' => 'draft',
            ]);
        }

        $nextPeriod = JamPelajaran::create([
            'jam_ke' => 40,
            'mulai' => '09:00:00',
            'selesai' => '09:40:00',
        ]);

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.pengajuan-jadwal.create'))
            ->post(route('guru.pengajuan-jadwal.store'), [
                'kelas_id' => $fixture['kelasA']->id,
                'mapel_id' => $fixture['mapelA']->id,
                'hari_id' => $fixture['days'][1]->id,
                'jam_pelajaran_id' => $nextPeriod->id,
            ])
            ->assertSessionHas('error', 'Total JP kelas sudah penuh');

        $this->assertSame(30, PengajuanJadwal::count());
    }

    public function test_teacher_cannot_delete_another_teachers_slot_and_unsupported_resource_actions_remain_absent(): void
    {
        $fixture = $this->makeFixture();
        $other = $this->makeSchedule(
            $fixture,
            $fixture['guruB'],
            $fixture['kelasA'],
            $fixture['mapelB'],
            1,
            1
        );
        $ownSubmitted = $this->makeSchedule(
            $fixture,
            $fixture['guruA'],
            $fixture['kelasA'],
            $fixture['mapelA'],
            1,
            2,
            'submitted'
        );

        $this->actingAs($fixture['teacherA'])
            ->delete(route('guru.jadwal-saya.hapus-slot', $other->id))
            ->assertNotFound();

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.jadwal-saya.index'))
            ->delete(route('guru.jadwal-saya.hapus-slot', $ownSubmitted->id))
            ->assertSessionHas('error', 'Jadwal sudah disubmit');

        $this->assertDatabaseHas('pengajuan_jadwal', ['id' => $other->id]);
        $this->assertDatabaseHas('pengajuan_jadwal', ['id' => $ownSubmitted->id]);

        foreach (['show', 'edit', 'update', 'destroy'] as $action) {
            $this->assertFalse(Route::has('guru.pengajuan-jadwal.'.$action));
        }
    }

    public function test_invalid_legacy_draft_cannot_bypass_validation_when_submitted(): void
    {
        $fixture = $this->makeFixture();
        $legacyDraft = $this->makeSchedule(
            $fixture,
            $fixture['guruA'],
            $fixture['kelasA'],
            $fixture['mapelB'],
            1,
            1
        );

        $this->actingAs($fixture['teacherA'])
            ->from(route('guru.jadwal-saya.index'))
            ->post(route('guru.jadwal-saya.submit'), [
                'kelas_id' => $fixture['kelasA']->id,
            ])
            ->assertSessionHas('error', 'Pengajuan tidak lagi sesuai dengan assignment mapel.');

        $this->assertSame('draft', $legacyDraft->fresh()->status);
    }

    public function test_kepsek_can_monitor_approve_and_publish_submissions_from_multiple_teachers(): void
    {
        $fixture = $this->makeFixture();
        $this->makeSchedule(
            $fixture,
            $fixture['guruA'],
            $fixture['kelasA'],
            $fixture['mapelA'],
            1,
            1,
            'submitted'
        );
        $secondTeacherSchedule = $this->makeSchedule(
            $fixture,
            $fixture['guruB'],
            $fixture['kelasA'],
            $fixture['mapelB'],
            1,
            2,
            'submitted'
        );
        $kepsek = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($fixture['teacherA'])
            ->get(route('superadmin.monitoring-jadwal.show', $fixture['kelasA']))
            ->assertRedirect();

        $this->actingAs($kepsek)
            ->get(route('superadmin.monitoring-jadwal.show', $fixture['kelasA']))
            ->assertOk()
            ->assertSee($fixture['teacherA']->pegawai->nama)
            ->assertSee($fixture['teacherB']->pegawai->nama);

        $this->actingAs($kepsek)
            ->post(route('superadmin.monitoring-jadwal.approve', $fixture['kelasA']))
            ->assertRedirect();

        $this->assertSame('approved', $secondTeacherSchedule->fresh()->status);

        $this->actingAs($kepsek)
            ->patch(route('superadmin.monitoring-jadwal.publish', $fixture['kelasA']))
            ->assertRedirect();

        $this->assertDatabaseMissing('pengajuan_jadwal', [
            'kelas_id' => $fixture['kelasA']->id,
            'status' => 'approved',
        ]);
        $this->assertSame(2, PengajuanJadwal::where('kelas_id', $fixture['kelasA']->id)
            ->where('status', 'published')
            ->count());
    }

    public function test_schedule_routes_still_require_the_individual_assignment_menu(): void
    {
        $teacher = $this->createAssignedTeacher('guru', []);

        $this->actingAs($teacher)
            ->get(route('guru.pengajuan-jadwal.index'))
            ->assertForbidden();

        $this->actingAs($teacher)
            ->get(route('guru.jadwal-saya.index'))
            ->assertForbidden();
    }

    private function makeFixture(int $mapelAQuota = 4): array
    {
        $tahun = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $angkatan = Angkatan::create(['tahun' => 2024]);
        $kelasMaster = KelasMaster::create(['tingkat' => '1']);
        $kelasGrup = KelasGrup::create([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $kelasMaster->id,
        ]);
        $kelasA = Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => 'I-A',
        ]);
        $kelasB = Kelas::create([
            'kelas_grup_id' => $kelasGrup->id,
            'nama_kelas' => 'I-B',
        ]);

        $teacherA = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $teacherB = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $guruA = $teacherA->guru;
        $guruB = $teacherB->guru;

        WaliKelas::create([
            'guru_id' => $guruA->id,
            'kelas_id' => $kelasA->id,
            'tahun_ajaran_id' => $tahun->id,
        ]);
        WaliKelas::create([
            'guru_id' => $guruB->id,
            'kelas_id' => $kelasB->id,
            'tahun_ajaran_id' => $tahun->id,
        ]);

        $mapelA = MataPelajaran::create([
            'nama_mapel' => 'Matematika',
            'jp_per_minggu' => $mapelAQuota,
        ]);
        $mapelB = MataPelajaran::create([
            'nama_mapel' => 'Bahasa Inggris',
            'jp_per_minggu' => 4,
        ]);
        PengampuMapel::create([
            'guru_id' => $guruB->id,
            'kelas_id' => $kelasA->id,
            'mata_pelajaran_id' => $mapelB->id,
            'tahun_ajaran_id' => $tahun->id,
        ]);

        $days = [
            HariSekolah::create(['nama' => 'Senin', 'urutan' => 1]),
            HariSekolah::create(['nama' => 'Selasa', 'urutan' => 2]),
        ];
        $periods = [
            JamPelajaran::create(['jam_ke' => 1, 'mulai' => '08:00', 'selesai' => '08:40']),
            JamPelajaran::create(['jam_ke' => 2, 'mulai' => '08:45', 'selesai' => '09:25']),
            JamPelajaran::create(['jam_ke' => 3, 'mulai' => '09:30', 'selesai' => '10:10']),
        ];

        return compact(
            'tahun',
            'kelasA',
            'kelasB',
            'guruA',
            'guruB',
            'teacherA',
            'teacherB',
            'mapelA',
            'mapelB',
            'days',
            'periods'
        );
    }

    private function makeSchedule(
        array $fixture,
        Guru $guru,
        Kelas $kelas,
        MataPelajaran $mapel,
        int $dayIndex,
        int $periodIndex,
        string $status = 'draft'
    ): PengajuanJadwal {
        return PengajuanJadwal::create([
            'guru_id' => $guru->id,
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'hari_id' => $fixture['days'][$dayIndex - 1]->id,
            'jam_pelajaran_id' => $fixture['periods'][$periodIndex - 1]->id,
            'tahun_ajaran_id' => $fixture['tahun']->id,
            'status' => $status,
        ]);
    }
}
