<?php

namespace Tests\Feature;

use App\Models\DataKepegawaian;
use App\Models\Ekskul;
use App\Models\Guru;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PembinaEkskul;
use App\Models\Penugasan;
use App\Models\PenugasanItem;
use App\Models\PenugasanMenu;
use App\Models\PengampuMapel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserFeaturePermission;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use ReflectionMethod;
use Tests\TestCase;

class ManajemenPenugasanTest extends TestCase
{
    use RefreshDatabase;

    private function kepsek(): User
    {
        return User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
    }

    private function employee(string $name = 'Guru Penugasan'): array
    {
        $employee = DataKepegawaian::create(['nama' => $name]);
        $guru = Guru::create(['data_kepegawaian_id' => $employee->id]);
        $user = User::factory()->create([
            'name' => $name,
            'role' => 'guru',
            'pegawai_id' => $employee->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        return [$employee, $guru, $user];
    }

    private function activeAssignment(DataKepegawaian $employee, string $type, array $menus): Penugasan
    {
        $assignment = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'active',
            'published_at' => now(),
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $assignment->id,
            'type' => $type,
        ]);

        foreach ($menus as $menu) {
            PenugasanMenu::create([
                'penugasan_item_id' => $item->id,
                'menu_key' => $menu,
            ]);
        }

        return $assignment;
    }

    public function test_teacher_can_switch_between_assignment_specific_sidebars(): void
    {
        $teacher = $this->createAssignedTeacher('operator', ['operator.dashboard', 'operator.employees']);
        $this->assignTeacherMenus($teacher, 'ppdb', ['ppdb.dashboard', 'ppdb.applicants']);
        $this->assignTeacherMenus($teacher, 'wali', ['wali.dashboard', 'wali.classes']);
        $this->assignTeacherMenus($teacher, 'guru', ['guru.dashboard', 'guru.schedule']);
        $this->assignTeacherMenus($teacher, 'ekskul', ['ekskul.dashboard', 'ekskul.members']);
        $year = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $class = Kelas::create(['nama_kelas' => 'VII-A']);
        WaliKelas::create([
            'guru_id' => $teacher->guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
        ]);

        $this->actingAs($teacher)
            ->get(route('guru.switch-assignment', 'operator'))
            ->assertRedirect(route('tu.dashboard'))
            ->assertSessionHas('assignment_mode', 'operator');

        $this->get(route('tu.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Operator')
            ->assertSee('Verifikasi Perubahan')
            ->assertSee('Wali Kelas')
            ->assertSee('Guru Bidang')
            ->assertSee('Pembina Ekskul')
            ->assertSee('PPDB')
            ->assertDontSee('Dashboard Wali Kelas')
            ->assertDontSee('Dashboard Guru Bidang')
            ->assertDontSee('Data Pendaftar');

        $this->get(route('guru.switch-assignment', 'wali'))
            ->assertRedirect(route('guru.dashboard-walikelas'))
            ->assertSessionHas('assignment_mode', 'wali');

        $this->get(route('guru.dashboard-walikelas'))
            ->assertOk()
            ->assertSee('Dashboard Wali Kelas')
            ->assertSee('Kelas Saya')
            ->assertDontSee('Dashboard Operator')
            ->assertDontSee('Dashboard Guru Bidang')
            ->assertDontSee('Data Pendaftar');

        $this->get(route('guru.switch-assignment', 'ppdb'))
            ->assertRedirect(route('tu.ppdb.dashboard'))
            ->assertSessionHas('assignment_mode', 'ppdb');

        $this->get(route('tu.ppdb.dashboard'))
            ->assertOk()
            ->assertSee('Data Pendaftar')
            ->assertDontSee('Dashboard Operator')
            ->assertDontSee('Dashboard Wali Kelas');

        $this->get(route('guru.switch-assignment', 'guru'))
            ->assertRedirect(route('guru.dashboard-gurubidang'))
            ->assertSessionHas('assignment_mode', 'guru');

        $this->get(route('guru.dashboard-gurubidang'))
            ->assertOk()
            ->assertSee('Dashboard Guru Bidang')
            ->assertSee('Jadwal Saya')
            ->assertDontSee('Dashboard Wali Kelas')
            ->assertDontSee('Data Pendaftar');

        $this->get(route('guru.switch-assignment', 'ekskul'))
            ->assertRedirect(route('guru.ekskul.dashboard'))
            ->assertSessionHas('assignment_mode', 'ekskul');

        $this->get(route('guru.ekskul.dashboard'))
            ->assertOk()
            ->assertSee('Daftar Anggota')
            ->assertDontSee('Dashboard Operator')
            ->assertDontSee('Dashboard Guru Bidang')
            ->assertDontSee('Dashboard Wali Kelas');
    }

    public function test_teacher_cannot_switch_to_an_assignment_they_do_not_have(): void
    {
        $teacher = $this->createAssignedTeacher('operator', ['operator.dashboard']);

        $this->actingAs($teacher)
            ->get(route('guru.switch-assignment', 'ppdb'))
            ->assertNotFound();

        $this->actingAs($teacher)
            ->get(route('guru.switch-assignment', 'operator'))
            ->assertRedirect(route('tu.dashboard'))
            ->assertSessionHas('assignment_mode', 'operator');

        $this->get(route('tu.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Operator')
            ->assertDontSee('Data Pendaftar')
            ->assertDontSee('Kelas Saya')
            ->assertDontSee('Jadwal Saya')
            ->assertDontSee('Daftar Anggota');
    }

    public function test_teacher_only_gets_individual_active_menu_rights_and_protected_ajax_routes(): void
    {
        [$employee, , $teacher] = $this->employee();
        $assignment = $this->activeAssignment($employee, 'operator', ['operator.students']);

        $this->assertTrue($teacher->hasFeature('operator.students'));
        $this->assertFalse($teacher->hasFeature('operator.teachers'));
        $this->assertFalse($teacher->hasFeature('tu.academic'));

        $this->actingAs($teacher)
            ->get(route('tu.guru.search'))
            ->assertForbidden();

        Siswa::create([
            'nama' => 'Siswa AJAX',
            'nis' => '11000001',
            'jenis_kelamin' => 'L',
        ]);
        $this->actingAs($teacher)
            ->get(route('tu.siswa.search', ['q' => 'Siswa AJAX']))
            ->assertOk()
            ->assertJsonFragment(['nis' => '11000001']);

        $this->assertDatabaseHas('penugasans', ['id' => $assignment->id, 'status' => 'active']);
    }

    public function test_teacher_search_ajax_returns_only_matching_guru_for_teacher_menu_grant(): void
    {
        [$employee] = $this->employee('Guru Dicari');
        $this->activeAssignment($employee, 'operator', ['operator.teachers']);

        $this->actingAs(User::where('pegawai_id', $employee->id)->firstOrFail())
            ->get(route('tu.guru.search', ['q' => 'Guru Dicari']))
            ->assertOk()
            ->assertJsonFragment([
                'nama' => 'Guru Dicari',
                'nip' => null,
            ]);
    }

    public function test_legacy_employee_detail_route_uses_existing_show_action_and_menu_access(): void
    {
        [$employee, , $teacher] = $this->employee('Guru Detail');
        $detailUrl = route('tu.datakepegawaian.detail', $employee->id);

        $this->actingAs($teacher)->get($detailUrl)->assertForbidden();

        $this->activeAssignment($employee, 'operator', ['operator.employees']);
        $this->actingAs($teacher)
            ->get($detailUrl)
            ->assertOk()
            ->assertViewIs('tu.datakepegawaian.detail');
    }

    public function test_teacher_extracurricular_index_compatibility_route_uses_dashboard_action(): void
    {
        [$employee, , $teacher] = $this->employee();
        $this->activeAssignment($employee, 'ekskul', ['ekskul.dashboard']);

        $this->actingAs($teacher)
            ->get(route('guru.ekskul.index'))
            ->assertOk()
            ->assertViewIs('guru.ekskul.dashboard');
    }

    public function test_angkatan_toggle_compatibility_route_calls_existing_toggle_action(): void
    {
        [$employee, , $teacher] = $this->employee();
        $this->activeAssignment($employee, 'operator', ['operator.students']);
        $angkatan = Angkatan::create(['tahun' => 2026, 'aktif' => true]);

        $this->actingAs($teacher)
            ->post(route('tu.angkatan.toggle', $angkatan))
            ->assertRedirect();

        $this->assertFalse((bool) $angkatan->fresh()->aktif);
    }

    public function test_active_assignment_menu_permissions_are_merged_and_revoke_only_removes_its_own_rights(): void
    {
        $kepsek = $this->kepsek();
        [$employee, , $teacher] = $this->employee();
        $operator = $this->activeAssignment($employee, 'operator', ['operator.students']);
        $ppdb = $this->activeAssignment($employee, 'ppdb', ['ppdb.dashboard']);

        $this->assertTrue($teacher->hasFeature('operator.students'));
        $this->assertTrue($teacher->hasFeature('ppdb.dashboard'));

        $this->actingAs($kepsek)
            ->patchJson(route('superadmin.manajemen-penugasan.revoke', $operator))
            ->assertOk();

        $this->assertFalse($teacher->fresh()->hasFeature('operator.students'));
        $this->assertTrue($teacher->fresh()->hasFeature('ppdb.dashboard'));
        $this->assertDatabaseHas('penugasans', ['id' => $ppdb->id, 'status' => 'active']);
    }

    public function test_operator_and_wali_access_are_combined_and_partial_revoke_preserves_wali_access(): void
    {
        $kepsek = $this->kepsek();
        [$employee, $guru, $teacher] = $this->employee();
        $year = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $class = Kelas::create(['nama_kelas' => 'VII-A']);
        $operator = $this->activeAssignment($employee, 'operator', ['operator.students']);
        $wali = $this->activeAssignment($employee, 'wali', ['wali.classes']);
        WaliKelas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
        ]);

        $this->assertTrue($teacher->hasFeature('operator.students'));
        $this->assertTrue($teacher->hasFeature('wali.classes'));
        $this->actingAs($teacher)->get(route('tu.siswa.index'))->assertOk();
        $this->actingAs($teacher)->get(route('guru.kelas-saya'))->assertOk();

        $this->actingAs($kepsek)
            ->patchJson(route('superadmin.manajemen-penugasan.revoke', $operator))
            ->assertOk();

        $teacher->refresh();
        $this->assertFalse($teacher->hasFeature('operator.students'));
        $this->assertTrue($teacher->hasFeature('wali.classes'));
        $this->actingAs($teacher)->get(route('tu.siswa.index'))->assertForbidden();
        $this->actingAs($teacher)->get(route('guru.kelas-saya'))->assertOk();
        $this->assertDatabaseHas('penugasans', ['id' => $wali->id, 'status' => 'active']);
    }

    public function test_duplicate_active_grants_remain_until_the_last_assignment_is_revoked(): void
    {
        $kepsek = $this->kepsek();
        [$employee, , $teacher] = $this->employee();
        $first = $this->activeAssignment($employee, 'operator', ['operator.students']);
        $second = $this->activeAssignment($employee, 'operator', ['operator.students']);

        $this->assertTrue($teacher->hasFeature('operator.students'));
        $this->actingAs($teacher)->get(route('tu.siswa.index'))->assertOk();

        $this->actingAs($kepsek)
            ->patchJson(route('superadmin.manajemen-penugasan.revoke', $first))
            ->assertOk();

        $this->assertTrue($teacher->fresh()->hasFeature('operator.students'));
        $this->actingAs($teacher)->get(route('tu.siswa.index'))->assertOk();

        $this->actingAs($kepsek)
            ->patchJson(route('superadmin.manajemen-penugasan.revoke', $second))
            ->assertOk();

        $this->assertFalse($teacher->fresh()->hasFeature('operator.students'));
        $this->actingAs($teacher)->get(route('tu.siswa.index'))->assertForbidden();
    }

    public function test_draft_menu_grants_stay_denied_through_edits_until_publication(): void
    {
        $kepsek = $this->kepsek();
        [$employee, , $teacher] = $this->employee();
        $draftResponse = $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.store'), [
            'employee' => $employee->id,
            'assignmentKeys' => ['operator'],
            'menus' => ['operator' => ['operator.students']],
        ]);
        $draftResponse->assertCreated();
        $draft = Penugasan::findOrFail($draftResponse->json('record.id'));

        $this->assertFalse($teacher->fresh()->hasFeature('operator.students'));
        $this->actingAs($teacher)->get(route('tu.siswa.index'))->assertForbidden();

        $this->actingAs($kepsek)->putJson(route('superadmin.manajemen-penugasan.update', $draft), [
            'employee' => $employee->id,
            'assignmentKeys' => ['operator'],
            'menus' => ['operator' => ['operator.teachers']],
        ])->assertOk();

        $this->assertFalse($teacher->fresh()->hasFeature('operator.students'));
        $this->assertFalse($teacher->fresh()->hasFeature('operator.teachers'));
        $this->actingAs($teacher)->get(route('tu.guru.index'))->assertForbidden();

        $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.publish'), [
            'ids' => [$draft->id],
        ])->assertOk();

        $this->assertTrue($teacher->fresh()->hasFeature('operator.teachers'));
        $this->assertFalse($teacher->fresh()->hasFeature('operator.students'));
        $this->actingAs($teacher)->get(route('tu.guru.index'))->assertOk();
        $this->actingAs($teacher)->get(route('tu.siswa.index'))->assertForbidden();
    }

    public function test_assignments_are_isolated_between_teachers_and_revoking_one_does_not_affect_another(): void
    {
        $kepsek = $this->kepsek();
        [$employeeA, , $teacherA] = $this->employee('Guru A');
        [$employeeB, , $teacherB] = $this->employee('Guru B');
        $assignmentA = $this->activeAssignment($employeeA, 'operator', ['operator.students']);
        $this->activeAssignment($employeeB, 'operator', ['operator.teachers']);

        $this->assertTrue($teacherA->hasFeature('operator.students'));
        $this->assertFalse($teacherA->hasFeature('operator.teachers'));
        $this->assertTrue($teacherB->hasFeature('operator.teachers'));
        $this->assertFalse($teacherB->hasFeature('operator.students'));
        $this->actingAs($teacherA)->get(route('tu.siswa.index'))->assertOk();
        $this->actingAs($teacherA)->get(route('tu.guru.index'))->assertForbidden();
        $this->actingAs($teacherB)->get(route('tu.guru.index'))->assertOk();
        $this->actingAs($teacherB)->get(route('tu.siswa.index'))->assertForbidden();

        $this->actingAs($kepsek)
            ->patchJson(route('superadmin.manajemen-penugasan.revoke', $assignmentA))
            ->assertOk();

        $this->assertFalse($teacherA->fresh()->hasFeature('operator.students'));
        $this->assertTrue($teacherB->fresh()->hasFeature('operator.teachers'));
        $this->actingAs($teacherA)->get(route('tu.siswa.index'))->assertForbidden();
        $this->actingAs($teacherB)->get(route('tu.guru.index'))->assertOk();
    }

    public function test_draft_revoked_and_legacy_feature_overrides_never_grant_teacher_access(): void
    {
        [$employee, , $teacher] = $this->employee();
        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'draft',
        ]);
        $draftItem = PenugasanItem::create(['penugasan_id' => $draft->id, 'type' => 'operator']);
        PenugasanMenu::create(['penugasan_item_id' => $draftItem->id, 'menu_key' => 'operator.accounts']);

        $revoked = $this->activeAssignment($employee, 'operator', ['operator.teachers']);
        $revoked->update(['status' => 'revoked', 'revoked_at' => now()]);

        UserFeaturePermission::create([
            'user_id' => $teacher->id,
            'feature_key' => 'tu.academic',
            'allowed' => true,
        ]);

        $this->assertFalse($teacher->fresh()->hasFeature('operator.accounts'));
        $this->assertFalse($teacher->fresh()->hasFeature('operator.teachers'));
        $this->assertFalse($teacher->fresh()->hasFeature('tu.academic'));
    }

    public function test_kepsek_has_full_menu_access_and_students_are_denied_management_routes(): void
    {
        $kepsek = $this->kepsek();
        $student = User::factory()->create([
            'role' => 'siswa',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->assertTrue($kepsek->hasFeature('operator.students'));
        $this->actingAs($student)
            ->get(route('superadmin.manajemen-penugasan.index'))
            ->assertRedirect();
    }

    public function test_save_edit_publish_and_revoke_work_and_invalid_menu_keys_are_rejected(): void
    {
        $kepsek = $this->kepsek();
        [$employee, , $teacher] = $this->employee();

        $response = $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.store'), [
            'employee' => $employee->id,
            'assignmentKeys' => [],
        ]);
        $response->assertCreated();
        $draft = Penugasan::where('status', 'draft')->firstOrFail();

        $this->actingAs($kepsek)->putJson(route('superadmin.manajemen-penugasan.update', $draft), [
            'employee' => $employee->id,
            'assignmentKeys' => ['operator'],
            'menus' => ['operator' => ['operator.students']],
        ])->assertOk();

        $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.publish'), [
            'ids' => [$draft->id],
        ])->assertOk();

        $this->assertTrue($teacher->fresh()->hasFeature('operator.students'));
        $this->assertDatabaseHas('penugasans', ['id' => $draft->id, 'status' => 'active']);

        $invalid = $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.store'), [
            'employee' => $employee->id,
            'assignmentKeys' => ['operator'],
            'menus' => ['operator' => ['tu.academic']],
        ]);
        $invalid->assertUnprocessable();
    }

    public function test_bulk_publish_rejects_non_draft_ids_without_partial_changes(): void
    {
        $kepsek = $this->kepsek();
        [$employee] = $this->employee();
        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'draft',
        ]);
        $active = $this->activeAssignment($employee, 'operator', ['operator.students']);

        $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.publish'), [
            'ids' => [$draft->id, $active->id],
        ])->assertUnprocessable();

        $this->assertDatabaseHas('penugasans', ['id' => $draft->id, 'status' => 'draft']);
        $this->assertDatabaseHas('penugasans', ['id' => $active->id, 'status' => 'active']);

        $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.publish'), [
            'ids' => [$draft->id, 999999],
        ])->assertUnprocessable();
        $this->assertDatabaseHas('penugasans', ['id' => $draft->id, 'status' => 'draft']);
    }

    public function test_publish_rejects_a_recipient_who_is_no_longer_a_guru(): void
    {
        $kepsek = $this->kepsek();
        [$employee, , $teacher] = $this->employee();
        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'draft',
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $draft->id,
            'type' => 'operator',
        ]);
        PenugasanMenu::create([
            'penugasan_item_id' => $item->id,
            'menu_key' => 'operator.students',
        ]);
        $teacher->update(['role' => 'admin']);

        $this->actingAs($kepsek)
            ->postJson(route('superadmin.manajemen-penugasan.publish'), ['ids' => [$draft->id]])
            ->assertUnprocessable();

        $this->assertDatabaseHas('penugasans', ['id' => $draft->id, 'status' => 'draft']);
    }

    public function test_publish_requires_active_year_for_legacy_operational_assignments(): void
    {
        $kepsek = $this->kepsek();
        [$employee] = $this->employee();
        $class = Kelas::create(['nama_kelas' => 'VII-A']);
        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'draft',
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $draft->id,
            'type' => 'wali',
        ]);
        $item->details()->create(['kelas_id' => $class->id]);
        PenugasanMenu::create([
            'penugasan_item_id' => $item->id,
            'menu_key' => 'wali.classes',
        ]);

        $this->actingAs($kepsek)
            ->postJson(route('superadmin.manajemen-penugasan.publish'), ['ids' => [$draft->id]])
            ->assertUnprocessable();

        $this->assertDatabaseHas('penugasans', ['id' => $draft->id, 'status' => 'draft']);
    }

    public function test_publish_syncs_operational_records_to_the_active_year_not_the_request_year(): void
    {
        $kepsek = $this->kepsek();
        [$employee, $guru] = $this->employee();
        $activeYear = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        $requestedYear = TahunAjaran::create([
            'tahun' => '2025/2026',
            'semester' => 'Genap',
            'status' => 'selesai',
        ]);
        $class = Kelas::create(['nama_kelas' => 'VII-A']);
        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'draft',
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $draft->id,
            'type' => 'wali',
        ]);
        $item->details()->create(['kelas_id' => $class->id]);
        PenugasanMenu::create([
            'penugasan_item_id' => $item->id,
            'menu_key' => 'wali.classes',
        ]);

        $this->actingAs($kepsek)
            ->postJson(route('superadmin.manajemen-penugasan.publish', ['ta' => $requestedYear->id]), [
                'ids' => [$draft->id],
            ])
            ->assertOk();

        $this->assertDatabaseHas('wali_kelas', [
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $activeYear->id,
        ]);
        $this->assertDatabaseMissing('wali_kelas', [
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $requestedYear->id,
        ]);
    }

    public function test_publish_rejects_operational_assignments_when_active_year_is_ambiguous(): void
    {
        $kepsek = $this->kepsek();
        [$employee, $guru] = $this->employee();
        $firstYear = TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Ganjil',
            'status' => 'aktif',
        ]);
        TahunAjaran::create([
            'tahun' => '2026/2027',
            'semester' => 'Genap',
            'status' => 'aktif',
        ]);
        $class = Kelas::create(['nama_kelas' => 'VII-A']);
        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'status' => 'draft',
        ]);
        $item = PenugasanItem::create([
            'penugasan_id' => $draft->id,
            'type' => 'wali',
        ]);
        $item->details()->create(['kelas_id' => $class->id]);
        PenugasanMenu::create([
            'penugasan_item_id' => $item->id,
            'menu_key' => 'wali.classes',
        ]);

        $this->actingAs($kepsek)
            ->postJson(route('superadmin.manajemen-penugasan.publish', ['ta' => $firstYear->id]), [
                'ids' => [$draft->id],
            ])
            ->assertUnprocessable();

        $this->assertDatabaseHas('penugasans', ['id' => $draft->id, 'status' => 'draft']);
        $this->assertDatabaseMissing('wali_kelas', [
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
        ]);
    }

    public function test_deleting_is_limited_to_draft_assignments(): void
    {
        $kepsek = $this->kepsek();
        [$employee] = $this->employee();
        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'assigned_by' => $kepsek->id,
            'status' => 'draft',
        ]);

        $this->actingAs($kepsek)
            ->deleteJson(route('superadmin.manajemen-penugasan.destroy', $draft))
            ->assertOk();
        $this->assertDatabaseMissing('penugasans', ['id' => $draft->id]);
    }

    public function test_legacy_assignment_sync_creates_unpublished_drafts_without_granting_menus(): void
    {
        [$employee, $guru, $teacher] = $this->employee();
        $activeYear = TahunAjaran::create(['tahun' => '2026/2027', 'semester' => 'Ganjil', 'status' => 'aktif']);
        $oldYear = TahunAjaran::create(['tahun' => '2025/2026', 'semester' => 'Genap', 'status' => 'selesai']);
        $class = Kelas::create(['nama_kelas' => 'X-A']);

        WaliKelas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $activeYear->id,
        ]);

        $migration = require database_path('migrations/2026_11_23_100000_create_penugasan_tables.php');
        $sync = new ReflectionMethod($migration, 'syncLegacyAssignments');
        $sync->setAccessible(true);
        $sync->invoke($migration);
        $sync->invoke($migration);

        $assignment = Penugasan::where('data_kepegawaian_id', $employee->id)->firstOrFail();
        $this->assertSame('draft', $assignment->status);
        $this->assertSame(1, PenugasanItem::where('penugasan_id', $assignment->id)->where('type', 'wali')->count());
        $this->assertSame(1, $assignment->items()->where('type', 'wali')->first()->details()->count());
        $this->assertSame(0, $assignment->items()->where('type', 'wali')->first()->menus()->count());
        $this->assertFalse($teacher->fresh()->hasFeature('wali.classes'));
        $this->assertSame($activeYear->id, WaliKelas::where('guru_id', $guru->id)->value('tahun_ajaran_id'));
        $this->assertNotSame($oldYear->id, $activeYear->id);
    }

    public function test_legacy_sync_preserves_exact_subject_and_extracurricular_pairs_without_grants(): void
    {
        [$employee, $guru, $teacher] = $this->employee();
        $activeYear = TahunAjaran::create(['tahun' => '2026/2027', 'semester' => 'Ganjil', 'status' => 'aktif']);
        $oldYear = TahunAjaran::create(['tahun' => '2025/2026', 'semester' => 'Genap', 'status' => 'selesai']);
        $classA = Kelas::create(['nama_kelas' => 'XI-A']);
        $classB = Kelas::create(['nama_kelas' => 'XI-B']);
        $subjectA = MataPelajaran::create(['nama_mapel' => 'Matematika']);
        $subjectB = MataPelajaran::create(['nama_mapel' => 'IPA']);
        $activity = Ekskul::create(['nama' => 'Futsal']);

        PengampuMapel::create([
            'guru_id' => $guru->id,
            'kelas_id' => $classA->id,
            'mata_pelajaran_id' => $subjectA->id,
            'tahun_ajaran_id' => $activeYear->id,
        ]);
        PengampuMapel::create([
            'guru_id' => $guru->id,
            'kelas_id' => $classB->id,
            'mata_pelajaran_id' => $subjectB->id,
            'tahun_ajaran_id' => $activeYear->id,
        ]);
        PengampuMapel::create([
            'guru_id' => $guru->id,
            'kelas_id' => $classA->id,
            'mata_pelajaran_id' => $subjectB->id,
            'tahun_ajaran_id' => $oldYear->id,
        ]);
        PembinaEkskul::create([
            'guru_id' => $guru->id,
            'ekskul_id' => $activity->id,
            'tahun_ajaran_id' => $activeYear->id,
        ]);

        $migration = require database_path('migrations/2026_11_23_100000_create_penugasan_tables.php');
        $sync = new ReflectionMethod($migration, 'syncLegacyAssignments');
        $sync->setAccessible(true);
        $sync->invoke($migration);
        $sync->invoke($migration);

        $guruItems = PenugasanItem::where('type', 'guru')->get()->filter(function (PenugasanItem $item) use ($classA, $classB, $subjectA, $subjectB) {
            $classId = $item->details()->value('kelas_id');
            $subjectId = $item->details()->value('mata_pelajaran_id');

            return in_array([$classId, $subjectId], [
                [$classA->id, $subjectA->id],
                [$classB->id, $subjectB->id],
            ], true);
        })->values();
        $this->assertCount(2, $guruItems);
        $this->assertEqualsCanonicalizing([
            [$classA->id, $subjectA->id],
            [$classB->id, $subjectB->id],
        ], $guruItems->map(fn (PenugasanItem $item) => [
            $item->details()->value('kelas_id'),
            $item->details()->value('mata_pelajaran_id'),
        ])->all());
        $this->assertSame(0, PenugasanMenu::whereIn(
            'penugasan_item_id',
            $guruItems->pluck('id')
        )->count());

        $ekskulItems = PenugasanItem::where('type', 'ekskul')->get()->filter(
            fn (PenugasanItem $item) => (int) $item->details()->value('ekskul_id') === $activity->id
        )->values();
        $this->assertCount(1, $ekskulItems);
        $this->assertSame($activity->id, (int) $ekskulItems->first()->details()->value('ekskul_id'));
        $this->assertSame(0, $ekskulItems->first()->menus()->count());
        $this->assertFalse($teacher->fresh()->hasFeature('guru.grades'));
        $this->assertFalse($teacher->fresh()->hasFeature('ekskul.attendance'));
    }

    public function test_legacy_sync_skips_ambiguous_active_years_for_manual_reconciliation(): void
    {
        [$employee, $guru] = $this->employee();
        $yearA = TahunAjaran::create(['tahun' => '2026/2027', 'semester' => 'Ganjil', 'status' => 'aktif']);
        TahunAjaran::create(['tahun' => '2026/2027', 'semester' => 'Genap', 'status' => 'aktif']);
        $class = Kelas::create(['nama_kelas' => 'XII-A']);
        $legacyAssignment = WaliKelas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $yearA->id,
        ]);

        $migration = require database_path('migrations/2026_11_23_100000_create_penugasan_tables.php');
        $sync = new ReflectionMethod($migration, 'syncLegacyAssignments');
        $sync->setAccessible(true);
        $sync->invoke($migration);

        $this->assertSame(0, Penugasan::where('data_kepegawaian_id', $employee->id)->count());
        $this->assertDatabaseHas('wali_kelas', ['id' => $legacyAssignment->id]);
    }

    public function test_legacy_sync_skips_teachers_with_multiple_active_homerooms(): void
    {
        [$employee, $guru] = $this->employee();
        $year = TahunAjaran::create(['tahun' => '2026/2027', 'semester' => 'Ganjil', 'status' => 'aktif']);
        $classA = Kelas::create(['nama_kelas' => 'VII-A']);
        $classB = Kelas::create(['nama_kelas' => 'VII-B']);
        WaliKelas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $classA->id,
            'tahun_ajaran_id' => $year->id,
        ]);
        WaliKelas::create([
            'guru_id' => $guru->id,
            'kelas_id' => $classB->id,
            'tahun_ajaran_id' => $year->id,
        ]);

        $migration = require database_path('migrations/2026_11_23_100000_create_penugasan_tables.php');
        $sync = new ReflectionMethod($migration, 'syncLegacyAssignments');
        $sync->setAccessible(true);
        $sync->invoke($migration);

        $this->assertSame(0, Penugasan::where('data_kepegawaian_id', $employee->id)->count());
        $this->assertSame(2, WaliKelas::where('guru_id', $guru->id)->where('tahun_ajaran_id', $year->id)->count());
    }

    public function test_publishing_legacy_assignment_syncs_operational_records_without_revoke_deleting_them(): void
    {
        $kepsek = $this->kepsek();
        [$employee, $guru] = $this->employee();
        $year = TahunAjaran::create(['tahun' => '2026/2027', 'semester' => 'Ganjil', 'status' => 'aktif']);
        $class = Kelas::create(['nama_kelas' => 'XI-B']);
        $subject = MataPelajaran::create(['nama_mapel' => 'Matematika']);
        $activity = Ekskul::create(['nama' => 'Futsal']);

        $response = $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.store'), [
            'employee' => $employee->id,
            'assignmentKeys' => ['wali', 'guru', 'ekskul'],
            'details' => [
                'waliClass' => $class->id,
                'subjects' => [$subject->id],
                'guruClasses' => [$class->id],
                'extracurriculars' => [$activity->id],
            ],
            'menus' => [
                'wali' => ['wali.classes'],
                'guru' => ['guru.grades'],
                'ekskul' => ['ekskul.journal'],
            ],
        ]);
        $response->assertCreated();
        $draft = Penugasan::where('status', 'draft')->firstOrFail();

        $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.publish'), [
            'ids' => [$draft->id],
        ])->assertOk();

        $this->assertDatabaseHas('wali_kelas', [
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
        ]);
        $this->assertDatabaseHas('pengampu_mapel', [
            'guru_id' => $guru->id,
            'kelas_id' => $class->id,
            'mata_pelajaran_id' => $subject->id,
            'tahun_ajaran_id' => $year->id,
        ]);
        $this->assertDatabaseHas('pembina_ekskul', [
            'guru_id' => $guru->id,
            'ekskul_id' => $activity->id,
            'tahun_ajaran_id' => $year->id,
        ]);

        $this->actingAs($kepsek)->patchJson(
            route('superadmin.manajemen-penugasan.revoke', $draft)
        )->assertOk();
        $this->assertDatabaseHas('wali_kelas', ['guru_id' => $guru->id, 'kelas_id' => $class->id]);
    }

    public function test_employee_account_creation_uses_guru_role_and_creates_guru_relation(): void
    {
        [$operatorEmployee, , $operator] = $this->employee('Operator Guru');
        $this->activeAssignment($operatorEmployee, 'operator', ['operator.employees']);

        $employee = DataKepegawaian::create([
            'nama' => 'Pegawai Lama TU',
            'jabatan' => 'Operator Sekolah',
            'tanggal_lahir' => '1990-01-02',
            'nip' => '198001020001',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($operator)
            ->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertRedirect();

        $user = User::where('pegawai_id', $employee->id)->firstOrFail();
        $this->assertSame('guru', $user->role);
        $this->assertTrue($user->guru()->exists());
        $this->assertSame('pending', $user->approval_status);
        $this->assertFalse($user->is_active);
        $this->assertTrue($user->must_change_password);
        $this->assertFalse(Hash::check('19900102', $user->password));
    }

    public function test_spmb_compatibility_dashboard_requires_an_active_ppdb_menu(): void
    {
        [, , $teacher] = $this->employee();
        $this->actingAs($teacher)
            ->get(route('spmb.dashboard'))
            ->assertForbidden();

        $this->activeAssignment($teacher->pegawai, 'ppdb', ['ppdb.dashboard']);
        $this->actingAs($teacher->fresh())
            ->get(route('spmb.dashboard'))
            ->assertRedirect(route('tu.ppdb.dashboard'));
    }

    public function test_employee_with_assignment_history_cannot_be_deleted(): void
    {
        [$operatorEmployee, , $operator] = $this->employee('Operator Guru');
        $this->activeAssignment($operatorEmployee, 'operator', ['operator.employees']);
        [$employee] = $this->employee('Guru Berpenugasan');
        $this->activeAssignment($employee, 'wali', ['wali.classes']);
        $year = TahunAjaran::create(['tahun' => '2025/2026', 'semester' => 'Genap', 'status' => 'selesai']);
        $class = Kelas::create(['nama_kelas' => 'IX-C']);
        WaliKelas::create([
            'guru_id' => $employee->guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
        ]);

        $this->actingAs($operator)
            ->delete(route('tu.datakepegawaian.destroy', $employee))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('data_kepegawaian', ['id' => $employee->id]);
        $this->assertDatabaseHas('penugasans', [
            'data_kepegawaian_id' => $employee->id,
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('wali_kelas', [
            'guru_id' => $employee->guru->id,
            'kelas_id' => $class->id,
            'tahun_ajaran_id' => $year->id,
        ]);
    }
}
