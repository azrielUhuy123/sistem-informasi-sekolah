<?php

namespace Tests\Feature;

use App\Models\DataKepegawaian;
use App\Models\Guru;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    private function employee(): DataKepegawaian
    {
        $employee = DataKepegawaian::create(['nama' => 'Guru Penugasan']);
        Guru::create(['data_kepegawaian_id' => $employee->id]);

        User::factory()->create([
            'name' => 'Guru Penugasan',
            'role' => 'guru',
            'pegawai_id' => $employee->id,
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        return $employee;
    }

    public function test_kepsek_can_save_edit_delete_draft_and_publish_assignment(): void
    {
        $kepsek = $this->kepsek();
        $employee = $this->employee();

        $response = $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.store'), [
            'employee' => $employee->id,
            'assignmentKeys' => [],
        ]);
        $response->assertCreated();

        $draft = Penugasan::where('status', 'draft')->firstOrFail();
        $this->assertDatabaseHas('penugasans', [
            'id' => $draft->id,
            'status' => 'draft',
            'data_kepegawaian_id' => $employee->id,
        ]);

        $this->actingAs($kepsek)->putJson(
            route('superadmin.manajemen-penugasan.update', $draft),
            [
                'employee' => $employee->id,
                'assignmentKeys' => ['operator'],
                'menus' => ['operator' => ['tu.academic']],
            ]
        )->assertOk();

        $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.publish'), [
            'ids' => [$draft->id],
        ]);
        $this->assertDatabaseHas('penugasans', ['id' => $draft->id, 'status' => 'active']);

        $guruUser = User::where('pegawai_id', $employee->id)->firstOrFail();
        $this->assertTrue($guruUser->hasFeature('tu.academic'));

        $this->actingAs($kepsek)->patchJson(
            route('superadmin.manajemen-penugasan.revoke', $draft)
        )->assertOk();

        $this->assertFalse($guruUser->fresh()->hasFeature('tu.academic'));
    }

    public function test_invalid_publication_is_rejected(): void
    {
        $kepsek = $this->kepsek();
        $employee = $this->employee();

        $created = $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.store'), [
            'employee' => $employee->id,
            'assignmentKeys' => ['wali'],
            'menus' => ['wali' => ['guru.academic']],
        ]);
        $created->assertCreated();

        $draft = Penugasan::where('status', 'draft')->firstOrFail();
        $this->actingAs($kepsek)->postJson(route('superadmin.manajemen-penugasan.publish'), [
            'ids' => [$draft->id],
        ])->assertStatus(422);
    }

    public function test_delete_is_limited_to_draft(): void
    {
        $kepsek = $this->kepsek();
        $employee = $this->employee();

        $draft = Penugasan::create([
            'data_kepegawaian_id' => $employee->id,
            'assigned_by' => $kepsek->id,
            'status' => 'draft',
        ]);

        $this->actingAs($kepsek)->deleteJson(
            route('superadmin.manajemen-penugasan.destroy', $draft)
        )->assertOk();
        $this->assertDatabaseMissing('penugasans', ['id' => $draft->id]);
    }
}
