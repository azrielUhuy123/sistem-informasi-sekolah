<?php

namespace Tests\Feature;

use App\Models\DataKepegawaian;
use App\Models\Guru;
use App\Models\User;
use App\Models\UserFeaturePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_role_defaults_and_explicit_denial_are_supported(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->assertTrue($student->hasFeature('siswa.academic'));

        UserFeaturePermission::create([
            'user_id' => $student->id,
            'feature_key' => 'siswa.academic',
            'allowed' => false,
        ]);

        $this->assertFalse($student->fresh()->hasFeature('siswa.academic'));
        $this->actingAs($student)
            ->get(route('siswa.jadwal.index'))
            ->assertForbidden();
    }

    public function test_teacher_cannot_get_access_from_legacy_feature_or_permission_override(): void
    {
        $employee = DataKepegawaian::create(['nama' => 'Guru Tanpa Penugasan']);
        Guru::create(['data_kepegawaian_id' => $employee->id]);
        $teacher = User::factory()->create([
            'role' => 'guru',
            'pegawai_id' => $employee->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
        UserFeaturePermission::create([
            'user_id' => $teacher->id,
            'feature_key' => 'tu.ppdb',
            'allowed' => true,
        ]);

        $this->assertSame('/guru/dashboard-gurubidang', $teacher->getDashboardUrl());
        $this->assertFalse($teacher->hasFeature('tu.ppdb'));
        $this->assertFalse($teacher->hasFeature('guru.academic'));
        $this->actingAs($teacher)
            ->get(route('tu.ppdb.dashboard'))
            ->assertForbidden();
    }

    public function test_legacy_tu_dashboard_redirect_is_compatible_but_has_no_permissions(): void
    {
        $legacyUser = User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->assertSame('/guru/dashboard-gurubidang', $legacyUser->getDashboardUrl());
        $this->assertFalse($legacyUser->hasFeature('tu.academic'));
    }

    public function test_kepsek_keeps_full_menu_access_and_students_cannot_open_management_routes(): void
    {
        $kepsek = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
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

    public function test_feature_access_ui_is_limited_to_students(): void
    {
        $kepsek = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
        $employee = DataKepegawaian::create(['nama' => 'Guru UI']);
        Guru::create(['data_kepegawaian_id' => $employee->id]);
        $teacher = User::factory()->create([
            'role' => 'guru',
            'pegawai_id' => $employee->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($kepsek)
            ->get(route('superadmin.feature-access.edit', $teacher))
            ->assertForbidden();
    }

    public function test_generic_admin_account_form_cannot_create_tu_or_other_employee_roles(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.users.store'), [
                'name' => 'Akun TU Baru',
                'email' => 'operator@example.test',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'tu',
            ])
            ->assertUnprocessable();
    }
}
