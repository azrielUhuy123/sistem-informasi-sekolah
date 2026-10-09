<?php

namespace Tests\Feature;

use App\Models\Angkatan;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasGrup;
use App\Models\KelasMaster;
use App\Models\KeputusanKenaikan;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AnnualRolloverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_grade_one_placement_moves_to_grade_two_in_the_same_cohort(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $this->assertSame('I', $fixture['sourceClass']->kelasGrup->kelasMaster->tingkat);
        $this->assertSame($fixture['sourcePeriod']->id, TahunAjaran::where('status', 'aktif')->where('semester', 'Genap')->value('id'));
        $this->assertSame(1, PenempatanSiswa::where('tahun_ajaran_id', $fixture['sourcePeriod']->id)->whereNull('tanggal_keluar')->count());
        $this->assertSame('I', PenempatanSiswa::with('kelas.kelasGrup.kelasMaster')
            ->where('id', $fixture['sourcePlacement']->id)
            ->firstOrFail()->kelas->kelasGrup->kelasMaster->tingkat);

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $this->assertRollover($fixture, 'IIA');
    }

    public function test_grade_two_placement_moves_to_grade_three(): void
    {
        $fixture = $this->makeRolloverFixture('II');

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $this->assertRollover($fixture, 'IIIA');
    }

    public function test_grade_five_placement_moves_to_grade_six(): void
    {
        $fixture = $this->makeRolloverFixture('V');

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $this->assertRollover($fixture, 'VIA');
    }

    public function test_grade_six_closes_source_without_creating_a_grade_seven_placement(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $this->createDecision($fixture, 'naik', 'approved');

        $this->post('/tu/tahun/tutup')
            ->assertSessionHas('success', fn (string $message) => str_contains($message, 'ditutup sebagai LULUS: 1'));

        $this->assertSame(now()->toDateString(), $fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame(0, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->count());
        $this->assertSame('selesai', $fixture['sourcePeriod']->fresh()->status);
        $this->assertSame('aktif', $fixture['targetPeriod']->fresh()->status);
    }

    public function test_grade_six_approved_naik_closes_placement_without_creating_a_target(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $this->createDecision($fixture, 'naik', 'approved');

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $this->assertSame(now()->toDateString(), $fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame(0, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->count());
        $this->assertSame($fixture['angkatan']->id, $fixture['siswa']->fresh()->angkatan_id);
    }

    public function test_grade_six_approved_tinggal_repeats_same_cohort_and_rombel(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $this->createDecision($fixture, 'tinggal', 'approved');

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $targetPlacement = PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->whereNull('tanggal_keluar')
            ->firstOrFail();

        $this->assertSame($fixture['sourceClass']->id, $targetPlacement->kelas_id);
        $this->assertSame('VI', $targetPlacement->kelas->kelasGrup->kelasMaster->tingkat);
        $this->assertSame($fixture['angkatan']->id, $targetPlacement->kelas->kelasGrup->angkatan_id);
        $this->assertSame($fixture['angkatan']->id, $fixture['siswa']->fresh()->angkatan_id);
        $this->assertSame(now()->toDateString(), $fixture['sourcePlacement']->fresh()->tanggal_keluar);
    }

    public function test_superadmin_vi_approval_keeps_source_placement_open_for_annual_rollover(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $user = $this->makeStudentAccount($fixture['siswa']);
        $decision = $this->createDecision($fixture, 'naik', 'pending');

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->from(route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']))
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertRedirect(route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']));

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame($fixture['angkatan']->id, $fixture['siswa']->fresh()->angkatan_id);
        $this->assertNull($fixture['siswa']->fresh()->deleted_at);
        $this->assertSame(1, (int) $user->fresh()->is_active);
    }

    public function test_monitoring_index_and_detail_routes_render_the_approval_forms(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $this->createDecision($fixture, 'naik', 'pending');
        $admin = $this->makeSuperAdmin();

        $this->actingAs($admin)
            ->withMiddleware()
            ->get(route('superadmin.monitoring-kenaikan.index'))
            ->assertOk()
            ->assertSee('Monitoring Kenaikan Kelas');

        $this->actingAs($admin)
            ->withMiddleware()
            ->get(route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']))
            ->assertOk()
            ->assertSee('Persetujuan Akhir')
            ->assertSee(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), false)
            ->assertSee(route('superadmin.monitoring-kenaikan.reject', $fixture['sourceClass']), false);
    }

    public function test_superadmin_vi_tinggal_approval_over_http_keeps_placement_open(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $user = $this->makeStudentAccount($fixture['siswa']);
        $decision = $this->createDecision($fixture, 'tinggal', 'pending');
        $showUrl = route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']);

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->from($showUrl)
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertRedirect($showUrl);

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame($fixture['angkatan']->id, $fixture['siswa']->fresh()->angkatan_id);
        $this->assertNull($fixture['siswa']->fresh()->deleted_at);
        $this->assertSame(1, (int) $user->fresh()->is_active);
    }

    public function test_superadmin_approval_for_grade_one_still_moves_the_placement(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $decision = $this->createDecision($fixture, 'naik', 'pending');
        $targetClass = Kelas::where('nama_kelas', 'IIA')
            ->whereHas('kelasGrup', fn ($query) => $query->where('angkatan_id', $fixture['angkatan']->id))
            ->firstOrFail();

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertRedirect();

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertNotNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertDatabaseHas('penempatan_siswa', [
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $targetClass->id,
            'tahun_ajaran_id' => $fixture['sourcePeriod']->id,
            'tanggal_keluar' => null,
        ]);
    }

    public function test_superadmin_grade_one_tinggal_approval_requires_and_preserves_matching_placement(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $decision = $this->createDecision($fixture, 'tinggal', 'pending');

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertRedirect();

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame(1, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
    }

    public function test_superadmin_grade_one_tinggal_without_placement_is_rejected(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $decision = $this->createDecision($fixture, 'tinggal', 'pending');
        $fixture['sourcePlacement']->delete();

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertSessionHasErrors('siswa');

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertSame(0, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
    }

    public function test_superadmin_grade_one_tinggal_with_wrong_class_placement_is_rejected(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $decision = $this->createDecision($fixture, 'tinggal', 'pending');
        $otherClass = $this->createClass($fixture['angkatan'], 'I', 'IB');
        $fixture['sourcePlacement']->update(['kelas_id' => $otherClass->id]);

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertSessionHasErrors('siswa');

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame($otherClass->id, $fixture['sourcePlacement']->fresh()->kelas_id);
    }

    public function test_invalid_decision_aborts_entire_approval_batch_before_any_mutation(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $validDecision = $this->createDecision($fixture, 'naik', 'pending');
        $invalidStudent = Siswa::create([
            'nama' => 'Missing approval placement test',
            'nis' => (string) random_int(1000000, 9999999),
            'jenis_kelamin' => 'P',
            'angkatan_id' => $fixture['angkatan']->id,
        ]);
        $invalidDecision = KeputusanKenaikan::create([
            'siswa_id' => $invalidStudent->id,
            'kelas_id' => $fixture['sourceClass']->id,
            'guru_id' => Guru::create([])->id,
            'tahun_ajaran_id' => $fixture['sourcePeriod']->id,
            'status' => 'tinggal',
            'approval_status' => 'pending',
        ]);

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertSessionHasErrors('siswa');

        $this->assertSame('pending', $validDecision->fresh()->approval_status);
        $this->assertSame('pending', $invalidDecision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame(1, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('kelas_id', $fixture['sourceClass']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
        $this->assertSame(0, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->whereHas('kelas', fn ($query) => $query->where('nama_kelas', 'IIA'))
            ->count());
    }

    public function test_valid_multi_student_approval_batch_is_approved_as_a_whole(): void
    {
        $fixture = $this->makeRolloverFixture('I', studentCount: 2);
        $sourcePlacements = PenempatanSiswa::where('kelas_id', $fixture['sourceClass']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->get();
        $decisions = $sourcePlacements->map(function (PenempatanSiswa $placement) use ($fixture) {
            return KeputusanKenaikan::create([
                'siswa_id' => $placement->siswa_id,
                'kelas_id' => $fixture['sourceClass']->id,
                'guru_id' => Guru::create([])->id,
                'tahun_ajaran_id' => $fixture['sourcePeriod']->id,
                'status' => 'naik',
                'approval_status' => 'pending',
            ]);
        });

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertRedirect();

        $this->assertSame(2, $decisions->filter(fn (KeputusanKenaikan $decision) =>
            $decision->fresh()->approval_status === 'approved'
        )->count());
        $this->assertSame(0, PenempatanSiswa::where('kelas_id', $fixture['sourceClass']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
        $this->assertSame(2, PenempatanSiswa::where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->whereHas('kelas', fn ($query) => $query->where('nama_kelas', 'IIA'))
            ->count());
    }

    public function test_superadmin_vi_tinggal_approval_keeps_source_placement_open(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $decision = $this->createDecision($fixture, 'tinggal', 'pending');

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->from(route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']))
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertRedirect(route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']));

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
    }

    public function test_superadmin_vi_reject_keeps_source_placement_open_and_rejected_is_not_final(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $decision = $this->createDecision($fixture, 'naik', 'pending');

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->from(route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']))
            ->patch(route('superadmin.monitoring-kenaikan.reject', $fixture['sourceClass']), [
                'catatan_kepsek' => 'Tidak disetujui',
            ])
            ->assertRedirect(route('superadmin.monitoring-kenaikan.show', $fixture['sourceClass']));

        $this->assertSame('rejected', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->withoutMiddleware();
        $this->assertRolloverRejected($fixture);
    }

    public function test_superadmin_approval_from_wrong_year_is_not_found(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $wrongPeriod = TahunAjaran::create([
            'tahun' => '2088/2089',
            'semester' => 'Genap',
            'status' => 'selesai',
        ]);
        $wrongPeriodFixture = $fixture;
        $wrongPeriodFixture['sourcePeriod'] = $wrongPeriod;
        $decision = $this->createDecision($wrongPeriodFixture, 'naik', 'pending');

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertNotFound();

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
    }

    public function test_superadmin_approval_is_idempotent_when_repeated_over_actual_route(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $decision = $this->createDecision($fixture, 'naik', 'pending');
        $admin = $this->makeSuperAdmin();
        $url = route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']);

        $this->actingAs($admin)->withMiddleware()->patch($url, ['confirmation' => 'NAIK KELAS'])
            ->assertRedirect();
        $this->actingAs($admin)->withMiddleware()->patch($url, ['confirmation' => 'NAIK KELAS'])
            ->assertRedirect();

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertSame(1, KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('kelas_id', $fixture['sourceClass']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->count());
        $this->assertSame(1, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
    }

    public function test_approval_does_not_consume_decision_from_another_academic_period(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $wrongPeriod = TahunAjaran::create([
            'tahun' => '2088/2089',
            'semester' => 'Genap',
            'status' => 'selesai',
        ]);
        $decisionFixture = $fixture;
        $decisionFixture['sourcePeriod'] = $wrongPeriod;
        $decision = $this->createDecision($decisionFixture, 'naik', 'pending');

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertNotFound();

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
    }

    public function test_approval_rejects_student_cohort_mismatch_and_wrong_class_scope(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $wrongCohortStudent = Siswa::create([
            'nama' => 'Mismatched approval test',
            'nis' => (string) random_int(1000000, 9999999),
            'jenis_kelamin' => 'L',
            'angkatan_id' => $fixture['otherAngkatan']->id,
        ]);
        $decision = KeputusanKenaikan::create([
            'siswa_id' => $wrongCohortStudent->id,
            'kelas_id' => $fixture['sourceClass']->id,
            'guru_id' => Guru::create([])->id,
            'tahun_ajaran_id' => $fixture['sourcePeriod']->id,
            'status' => 'naik',
            'approval_status' => 'pending',
        ]);

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertSessionHasErrors('siswa');

        $this->assertSame('pending', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);

        $this->withoutMiddleware();
        $this->from('/tu/tahun')
            ->post('/tu/tahun/tutup')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHasErrors('siswa');

        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);

        $otherClass = $this->createClass($fixture['angkatan'], 'VI', 'VIB');
        $wrongClassDecision = KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $otherClass->id,
            'guru_id' => Guru::create([])->id,
            'tahun_ajaran_id' => $fixture['sourcePeriod']->id,
            'status' => 'naik',
            'approval_status' => 'pending',
        ]);

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $otherClass), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertSessionHasErrors('siswa');

        $this->assertSame('pending', $wrongClassDecision->fresh()->approval_status);
        $this->assertSame('pending', $decision->fresh()->approval_status);
    }

    public function test_approval_route_can_be_repeated_without_duplicate_decision_or_placement(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $decision = $this->createDecision($fixture, 'naik', 'pending');
        $admin = $this->makeSuperAdmin();
        $approveUrl = route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']);

        $this->actingAs($admin)->withMiddleware()
            ->patch($approveUrl, ['confirmation' => 'NAIK KELAS'])
            ->assertRedirect();
        $this->actingAs($admin)->withMiddleware()
            ->patch($approveUrl, ['confirmation' => 'NAIK KELAS'])
            ->assertRedirect();

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertSame(1, KeputusanKenaikan::where('siswa_id', $fixture['siswa']->id)
            ->where('kelas_id', $fixture['sourceClass']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->count());
        $this->assertSame(1, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
    }

    public function test_route_approved_vi_decision_is_consumed_by_annual_rollover(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $decision = $this->createDecision($fixture, 'tinggal', 'pending');

        $this->actingAs($this->makeSuperAdmin())
            ->withMiddleware()
            ->patch(route('superadmin.monitoring-kenaikan.approve', $fixture['sourceClass']), [
                'confirmation' => 'NAIK KELAS',
            ])
            ->assertRedirect();

        $this->assertSame('approved', $decision->fresh()->approval_status);
        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);

    $this->withoutMiddleware();
        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $targetPlacement = PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->whereNull('tanggal_keluar')
            ->firstOrFail();
        $this->assertSame($fixture['sourceClass']->id, $targetPlacement->kelas_id);
        $this->assertSame(now()->toDateString(), $fixture['sourcePlacement']->fresh()->tanggal_keluar);
    }

    public function test_grade_six_without_a_decision_is_not_rolled_over(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);

        $this->assertRolloverRejected($fixture);
    }

    public function test_grade_six_pending_decision_is_not_rolled_over(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $this->createDecision($fixture, 'naik', 'pending');

        $this->assertRolloverRejected($fixture);
    }

    public function test_grade_six_rejected_decision_is_not_rolled_over(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $this->createDecision($fixture, 'naik', 'rejected');

        $this->assertRolloverRejected($fixture);
    }

    public function test_grade_six_pending_student_aborts_the_entire_batch(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false, studentCount: 2);
        $placements = PenempatanSiswa::where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->orderBy('id')
            ->get();
        $firstStudent = Siswa::findOrFail($placements[0]->siswa_id);
        $firstFixture = $fixture;
        $firstFixture['siswa'] = $firstStudent;
        $firstFixture['sourceClass'] = Kelas::findOrFail($placements[0]->kelas_id);
        $this->createDecision($firstFixture, 'naik', 'approved');

        $this->assertRolloverRejected($fixture);

        $this->assertSame(2, PenempatanSiswa::where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
    }

    public function test_grade_six_tinggal_rejects_wrong_cohort_target_placement(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $this->createDecision($fixture, 'tinggal', 'approved');
        $wrongCohortClass = $this->createClass(
            $fixture['otherAngkatan'],
            'VI',
            'VIA'
        );
        PenempatanSiswa::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $wrongCohortClass->id,
            'tahun_ajaran_id' => $fixture['targetPeriod']->id,
            'tanggal_masuk' => now()->toDateString(),
            'tanggal_keluar' => null,
        ]);

        $this->assertRolloverRejected($fixture);
    }

    public function test_grade_six_tinggal_rejects_invalid_source_rombel_before_mutation(): void
    {
        $fixture = $this->makeRolloverFixture('VI', createTargetClass: false);
        $fixture['sourceClass']->update(['nama_kelas' => 'VI1']);
        $this->createDecision($fixture, 'tinggal', 'approved');

        $this->from('/tu/tahun')
            ->post('/tu/tahun/tutup')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHasErrors('kelas');

        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame('aktif', $fixture['sourcePeriod']->fresh()->status);
        $this->assertSame('draft', $fixture['targetPeriod']->fresh()->status);
    }

    public function test_target_class_from_another_cohort_is_not_used(): void
    {
        $fixture = $this->makeRolloverFixture('I', createTargetClass: false);
        $this->createClass($fixture['otherAngkatan'], 'II', 'IIA');

        $this->from('/tu/tahun')
            ->post('/tu/tahun/tutup')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHasErrors('kelas');

        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame('aktif', $fixture['sourcePeriod']->fresh()->status);
        $this->assertSame('draft', $fixture['targetPeriod']->fresh()->status);
        $this->assertSame(0, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->count());
    }

    public function test_missing_target_class_keeps_source_placement_open(): void
    {
        $fixture = $this->makeRolloverFixture('I', createTargetClass: false);

        $this->from('/tu/tahun')
            ->post('/tu/tahun/tutup')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHasErrors('kelas');

        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame('aktif', $fixture['sourcePeriod']->fresh()->status);
        $this->assertSame('draft', $fixture['targetPeriod']->fresh()->status);
    }

    public function test_repeating_annual_rollover_does_not_duplicate_target_placement(): void
    {
        $fixture = $this->makeRolloverFixture('I');

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');
        $this->post('/tu/tahun/tutup')->assertSessionHas('error');

        $this->assertSame(1, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
    }

    public function test_existing_matching_target_placement_is_preserved_without_a_duplicate(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $targetClass = Kelas::where('nama_kelas', 'IIA')
            ->whereHas('kelasGrup', fn ($query) => $query->where('angkatan_id', $fixture['angkatan']->id))
            ->firstOrFail();
        $existing = PenempatanSiswa::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $targetClass->id,
            'tahun_ajaran_id' => $fixture['targetPeriod']->id,
            'tanggal_masuk' => '2091-08-01',
            'tanggal_keluar' => null,
        ]);

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $this->assertSame(1, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
        $this->assertSame('2091-08-01', $existing->fresh()->tanggal_masuk);
        $this->assertSame($targetClass->id, $existing->fresh()->kelas_id);
        $this->assertSame(now()->toDateString(), $fixture['sourcePlacement']->fresh()->tanggal_keluar);
    }

    public function test_activating_the_target_after_source_was_already_closed_uses_same_rollover(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $fixture['sourcePeriod']->update(['status' => 'selesai']);

        $this->post('/tu/tahun/' . $fixture['targetPeriod']->id . '/aktifkan')
            ->assertSessionHas('success');

        $this->assertRollover($fixture, 'IIA');
    }

    public function test_activation_uses_exact_previous_year_genap_not_latest_finished_period(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $fixture['sourcePeriod']->update(['status' => 'selesai']);
        TahunAjaran::create([
            'tahun' => '2110/2111',
            'semester' => 'Ganjil',
            'status' => 'selesai',
        ]);

        $this->post('/tu/tahun/' . $fixture['targetPeriod']->id . '/aktifkan')
            ->assertSessionHas('success');

        $this->assertRollover($fixture, 'IIA');
    }

    public function test_admin_activation_cannot_bypass_annual_placement_rollover(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $fixture['sourcePeriod']->update(['status' => 'selesai']);

        $this->from('/admin/tahun-ajaran')
            ->patch('/admin/tahun-ajaran/' . $fixture['targetPeriod']->id . '/activate')
            ->assertRedirect('/admin/tahun-ajaran')
            ->assertSessionHas('error');

        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame('draft', $fixture['targetPeriod']->fresh()->status);
        $this->assertSame(0, PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->count());
    }

    public function test_rollover_uses_the_active_genap_period_not_the_latest_finished_ganjil(): void
    {
        $fixture = $this->makeRolloverFixture('I');
        $finishedGanjil = TahunAjaran::create([
            'tahun' => '2089/2090',
            'semester' => 'Ganjil',
            'status' => 'selesai',
        ]);

        $this->post('/tu/tahun/tutup')->assertSessionHas('success');

        $this->assertSame('selesai', $finishedGanjil->fresh()->status);
        $this->assertRollover($fixture, 'IIA');
    }

    public function test_a_ganjil_period_cannot_be_closed_as_an_annual_source(): void
    {
        TahunAjaran::where('status', 'aktif')->update(['status' => 'selesai']);
        $ganjil = TahunAjaran::create([
            'tahun' => '2088/2089',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);

        $this->from('/tu/tahun')
            ->post('/tu/tahun/tutup')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHas('error');

        $this->assertSame('aktif', $ganjil->fresh()->status);
    }

    public function test_target_period_must_be_present_and_draft(): void
    {
        $fixture = $this->makeRolloverFixture('I', createTargetPeriod: false);

        $this->from('/tu/tahun')
            ->post('/tu/tahun/tutup')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHas('error');

        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame('aktif', $fixture['sourcePeriod']->fresh()->status);
    }

    public function test_batch_failure_rolls_back_all_placements_and_period_statuses(): void
    {
        $fixture = $this->makeRolloverFixture('I', studentCount: 2);
        $targetPeriodId = $fixture['targetPeriod']->id;
        $targetInserts = 0;
        $insertPrefix = 'insert into ' . DB::connection()->getQueryGrammar()->wrapTable('penempatan_siswa') . ' ';

        DB::listen(function (QueryExecuted $query) use ($targetPeriodId, &$targetInserts, $insertPrefix): void {
            if (
                str_starts_with(strtolower($query->sql), strtolower($insertPrefix)) &&
                in_array($targetPeriodId, $query->bindings, true)
            ) {
                $targetInserts++;

                if ($targetInserts === 2) {
                    throw new \RuntimeException('Simulated annual rollover insert failure.');
                }
            }
        });

        $this->withoutExceptionHandling();

        try {
            $this->post('/tu/tahun/tutup');
            $this->fail('Expected the simulated annual rollover insert failure.');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated annual rollover insert failure.', $exception->getMessage());
        }

        $this->assertSame('aktif', $fixture['sourcePeriod']->fresh()->status);
        $this->assertSame('draft', $fixture['targetPeriod']->fresh()->status);
        $this->assertSame(2, PenempatanSiswa::where('tahun_ajaran_id', $fixture['sourcePeriod']->id)
            ->whereNull('tanggal_keluar')
            ->count());
        $this->assertSame(0, PenempatanSiswa::where('tahun_ajaran_id', $fixture['targetPeriod']->id)->count());
    }

    private function assertRollover(array $fixture, string $targetClassName): void
    {
        $targetPlacement = PenempatanSiswa::where('siswa_id', $fixture['siswa']->id)
            ->where('tahun_ajaran_id', $fixture['targetPeriod']->id)
            ->whereNull('tanggal_keluar')
            ->firstOrFail();

        $targetClass = Kelas::findOrFail($targetPlacement->kelas_id);

        $this->assertSame($targetClassName, $targetClass->nama_kelas);
        $this->assertSame($fixture['angkatan']->id, $targetClass->kelasGrup->angkatan_id);
        $this->assertSame($fixture['angkatan']->id, $fixture['siswa']->fresh()->angkatan_id);
        $this->assertSame(now()->toDateString(), $fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame('selesai', $fixture['sourcePeriod']->fresh()->status);
        $this->assertSame('aktif', $fixture['targetPeriod']->fresh()->status);
    }

    private function assertRolloverRejected(array $fixture): void
    {
        $this->withoutMiddleware();
        $this->from('/tu/tahun')
            ->post('/tu/tahun/tutup')
            ->assertRedirect('/tu/tahun')
            ->assertSessionHasErrors();

        $this->assertNull($fixture['sourcePlacement']->fresh()->tanggal_keluar);
        $this->assertSame('aktif', $fixture['sourcePeriod']->fresh()->status);
        $this->assertSame('draft', $fixture['targetPeriod']->fresh()->status);
    }

    private function createDecision(array $fixture, string $status, string $approvalStatus): KeputusanKenaikan
    {
        $guru = Guru::create([]);

        return KeputusanKenaikan::create([
            'siswa_id' => $fixture['siswa']->id,
            'kelas_id' => $fixture['sourceClass']->id,
            'guru_id' => $guru->id,
            'tahun_ajaran_id' => $fixture['sourcePeriod']->id,
            'status' => $status,
            'approval_status' => $approvalStatus,
            'approved_at' => $approvalStatus === 'approved' ? now() : null,
        ]);
    }

    private function makeSuperAdmin(): User
    {
        return User::factory()->create([
            'username' => 'superadmin-' . random_int(100000, 999999),
            'password' => 'test-password',
            'role' => 'superadmin',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);
    }

    private function makeStudentAccount(Siswa $siswa): User
    {
        $user = User::factory()->create([
            'siswa_id' => $siswa->id,
            'username' => 'student-' . random_int(100000, 999999),
            'password' => 'test-password',
            'role' => 'siswa',
            'is_active' => true,
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        $siswa->update(['user_id' => $user->id]);

        return $user;
    }

    private function makeRolloverFixture(
        string $sourceGrade,
        bool $createTargetClass = true,
        bool $createTargetPeriod = true,
        int $studentCount = 1
    ): array {
        TahunAjaran::where('status', 'aktif')
            ->update(['status' => 'selesai']);

        $angkatanYear = random_int(2090, 2100);
        $sourceYear = $angkatanYear . '/' . ($angkatanYear + 1);
        $targetYear = ($angkatanYear + 1) . '/' . ($angkatanYear + 2);

        $sourcePeriod = TahunAjaran::create([
            'tahun' => $sourceYear,
            'semester' => 'Genap',
            'status' => 'aktif',
        ]);
        $targetPeriod = $createTargetPeriod
            ? TahunAjaran::create([
                'tahun' => $targetYear,
                'semester' => 'Ganjil',
                'status' => 'draft',
            ])
            : null;

        $angkatan = Angkatan::create(['tahun' => $angkatanYear]);
        $otherAngkatan = Angkatan::create(['tahun' => $angkatanYear + 1]);
        $kelasMasterAsal = KelasMaster::firstOrCreate(['tingkat' => $sourceGrade]);
        $suffix = 'A';
        $kelasAsal = $this->createClass($angkatan, $sourceGrade, $sourceGrade . $suffix, $kelasMasterAsal);
        $siswa = null;
        $sourcePlacement = null;

        for ($index = 0; $index < $studentCount; $index++) {
            $siswa = Siswa::create([
                'nama' => 'Annual rollover test ' . $index,
                'nis' => (string) random_int(1000000, 9999999),
                'jenis_kelamin' => 'L',
                'angkatan_id' => $angkatan->id,
            ]);
            $sourcePlacement = PenempatanSiswa::create([
                'siswa_id' => $siswa->id,
                'kelas_id' => $kelasAsal->id,
                'tahun_ajaran_id' => $sourcePeriod->id,
                'tanggal_masuk' => $angkatanYear . '-08-01',
                'tanggal_keluar' => null,
            ]);
        }

        $targetGrade = [
            'I' => 'II',
            'II' => 'III',
            'III' => 'IV',
            'IV' => 'V',
            'V' => 'VI',
            'VI' => 'LULUS',
        ][$sourceGrade];

        if ($createTargetClass && $targetGrade !== 'LULUS') {
            $kelasMasterTujuan = KelasMaster::firstOrCreate(['tingkat' => $targetGrade]);
            $this->createClass($angkatan, $targetGrade, $targetGrade . $suffix, $kelasMasterTujuan);
        }

        return [
            'angkatan' => $angkatan,
            'otherAngkatan' => $otherAngkatan,
            'sourceClass' => $kelasAsal,
            'sourcePeriod' => $sourcePeriod,
            'sourcePlacement' => $sourcePlacement,
            'siswa' => $siswa,
            'targetPeriod' => $targetPeriod,
        ];
    }

    private function createClass(Angkatan $angkatan, string $grade, string $name, ?KelasMaster $master = null): Kelas
    {
        $master ??= KelasMaster::firstOrCreate(['tingkat' => $grade]);
        $group = KelasGrup::firstOrCreate([
            'angkatan_id' => $angkatan->id,
            'kelas_master_id' => $master->id,
        ]);

        return Kelas::create([
            'kelas_grup_id' => $group->id,
            'nama_kelas' => $name,
        ]);
    }
}