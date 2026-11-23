<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserFeaturePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_default_allows_feature(): void
    {
        $tu = User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->assertSame('tu', $tu->role);
        $features = config('feature_access.features', []);
        $this->assertContains('tu', $features['tu.ppdb']['roles'] ?? []);
        $this->assertTrue($tu->hasFeature('tu.ppdb'));
    }

    public function test_explicit_denial_overrides_role_default(): void
    {
        $tu = User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        UserFeaturePermission::create([
            'user_id' => $tu->id,
            'feature_key' => 'tu.ppdb',
            'allowed' => false,
        ]);

        $this->assertFalse($tu->hasFeature('tu.ppdb'));
    }

    public function test_superadmin_bypasses_feature_override(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->assertTrue($superadmin->hasFeature('tu.ppdb'));
    }

    public function test_feature_middleware_rejects_explicit_denial(): void
    {
        $tu = User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        UserFeaturePermission::create([
            'user_id' => $tu->id,
            'feature_key' => 'tu.ppdb',
            'allowed' => false,
        ]);

        $this->actingAs($tu)
            ->get('/tu/ppdb/dashboard')
            ->assertForbidden();
    }

    public function test_superadmin_can_open_feature_access_menu(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($superadmin)
            ->get(route('superadmin.feature-access.index'))
            ->assertOk();
    }

    public function test_superadmin_can_store_explicit_feature_denial(): void
    {
        $superadmin = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $tu = User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($superadmin)
            ->put(route('superadmin.feature-access.update', $tu), [
                'permissions' => [],
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('user_feature_permissions', [
            'user_id' => $tu->id,
            'feature_key' => 'tu.ppdb',
            'allowed' => false,
        ]);

        $this->assertFalse($tu->fresh()->hasFeature('tu.ppdb'));
    }

    public function test_siswa_feature_denial_rejects_direct_route_access(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        UserFeaturePermission::create([
            'user_id' => $student->id,
            'feature_key' => 'siswa.academic',
            'allowed' => false,
        ]);

        $this->actingAs($student)
            ->get(route('siswa.jadwal.index'))
            ->assertForbidden();
    }

    public function test_guru_feature_denial_rejects_direct_route_access(): void
    {
        $guruUser = User::factory()->create([
            'role' => 'guru',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        UserFeaturePermission::create([
            'user_id' => $guruUser->id,
            'feature_key' => 'guru.academic',
            'allowed' => false,
        ]);

        $this->actingAs($guruUser)
            ->get(route('guru.dashboard-gurubidang'))
            ->assertForbidden();
    }
}
