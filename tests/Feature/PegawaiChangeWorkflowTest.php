<?php

namespace Tests\Feature;

use App\Models\DataKepegawaian;
use App\Models\PegawaiChangeRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PegawaiChangeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_requests_are_private_and_owner_is_taken_from_the_login(): void
    {
        $teacherA = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $teacherB = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $requestA = $this->makeRequest($teacherA, 'Nama A', 'Nama A Baru');
        $requestB = $this->makeRequest($teacherB, 'Nama B', 'Nama B Baru');

        $this->actingAs($teacherA)
            ->get(route('guru.pegawai-changes.index'))
            ->assertOk()
            ->assertSee('Pengajuan #'.$requestA->id)
            ->assertDontSee('Pengajuan #'.$requestB->id);
        $this->actingAs($teacherA)
            ->get(route('guru.pegawai-changes.create'))
            ->assertOk()
            ->assertSee('Nilai yang diajukan');

        $this->actingAs($teacherA)
            ->get(route('guru.pegawai-changes.show', $requestB))
            ->assertNotFound();

        $response = $this->actingAs($teacherA)
            ->post(route('guru.pegawai-changes.store'), [
                'data_kepegawaian_id' => $teacherB->pegawai_id,
                'changes' => ['tempat_lahir' => 'Kota A Diperbarui'],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pegawai_change_requests', [
            'data_kepegawaian_id' => $teacherA->pegawai_id,
            'submitted_by' => $teacherA->id,
        ]);
        $this->assertDatabaseMissing('pegawai_change_requests', [
            'data_kepegawaian_id' => $teacherB->pegawai_id,
            'submitted_by' => $teacherA->id,
        ]);
    }

    public function test_profile_endpoint_cannot_directly_change_official_employee_data(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $employee = $teacher->pegawai;

        $this->actingAs($teacher)
            ->get(route('guru.profile.detail'))
            ->assertOk()
            ->assertSee('Data Kepegawaian Saat Ini');

        $this->actingAs($teacher)
            ->put(route('guru.profile.detail.update'), [
                'email' => $teacher->email,
                'nama' => 'Nama yang Tidak Boleh Langsung Disimpan',
                'jabatan' => 'Kepala Sekolah',
            ])
            ->assertRedirect(route('guru.profile.detail'));

        $this->assertDatabaseHas('data_kepegawaian', [
            'id' => $employee->id,
            'nama' => $employee->nama,
            'jabatan' => $employee->jabatan,
        ]);
    }

    public function test_account_deletion_preserves_change_history_and_submitter_snapshot(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $submitterName = $teacher->name;
        $changeRequest = $this->makeRequest(
            $teacher,
            $teacher->pegawai->nama,
            'Nama yang Diajukan'
        );

        $this->actingAs($teacher)
            ->delete(route('profile.destroy'), [
                'password' => 'test-password',
            ])
            ->assertRedirect('/');

        $this->assertDatabaseHas('pegawai_change_requests', [
            'id' => $changeRequest->id,
            'submitted_by' => null,
            'submitted_by_name' => $submitterName,
        ]);
    }

    public function test_only_an_active_operator_assignment_can_verify_and_updates_are_recorded(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $changeRequest = $this->makeRequest(
            $teacher,
            $teacher->pegawai->nama,
            'Nama Disetujui'
        );
        $item = $changeRequest->items()->firstOrFail();

        $this->actingAs($operator)
            ->get(route('tu.pegawai-changes.show', $changeRequest))
            ->assertOk()
            ->assertSee('Setujui dan Terapkan');

        $this->actingAs($operator)
            ->patch(route('tu.pegawai-changes.approve', $item))
            ->assertRedirect();

        $this->assertDatabaseHas('data_kepegawaian', [
            'id' => $teacher->pegawai_id,
            'nama' => 'Nama Disetujui',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $teacher->id,
            'name' => 'Nama Disetujui',
        ]);
        $this->assertDatabaseHas('pegawai_change_items', [
            'id' => $item->id,
            'status' => 'approved',
            'verified_by' => $operator->id,
        ]);
        $this->assertDatabaseHas('pegawai_change_decisions', [
            'pegawai_change_item_id' => $item->id,
            'actor_id' => $operator->id,
            'action' => 'approved',
        ]);
    }

    public function test_inactive_or_unapproved_operator_cannot_verify_changes(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $changeRequest = $this->makeRequest(
            $teacher,
            $teacher->pegawai->nama,
            'Nama Disetujui'
        );
        $item = $changeRequest->items()->firstOrFail();

        foreach ([
            ['is_active' => false, 'approval_status' => 'approved'],
            ['is_active' => true, 'approval_status' => 'pending'],
        ] as $status) {
            $operator->update($status);

            $this->actingAs($operator->fresh())
                ->patch(route('tu.pegawai-changes.approve', $item))
                ->assertRedirect(route('login'));
            $this->assertGuest();
        }

        $this->assertDatabaseHas('pegawai_change_items', [
            'id' => $item->id,
            'status' => 'pending',
        ]);
    }

    public function test_operator_cannot_verify_a_request_for_their_own_employee_record(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $changeRequest = $this->makeRequest(
            $operator,
            $operator->pegawai->nama,
            'Nama Sendiri Diubah'
        );
        $item = $changeRequest->items()->firstOrFail();

        $this->actingAs($operator)
            ->patch(route('tu.pegawai-changes.approve', $item))
            ->assertForbidden();

        $this->assertDatabaseHas('pegawai_change_items', [
            'id' => $item->id,
            'status' => 'pending',
        ]);
    }

    public function test_assignment_must_be_operator_type_even_if_it_has_the_menu_key(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $wrongAssignment = $this->createAssignedTeacher('guru', ['operator.employees']);
        $changeRequest = $this->makeRequest(
            $teacher,
            $teacher->pegawai->nama,
            'Nama Disetujui'
        );
        $item = $changeRequest->items()->firstOrFail();

        $this->actingAs($wrongAssignment)
            ->patch(route('tu.pegawai-changes.approve', $item))
            ->assertForbidden();

        $this->assertDatabaseHas('pegawai_change_items', [
            'id' => $item->id,
            'status' => 'pending',
        ]);
    }

    public function test_evidence_is_private_and_accessible_to_its_owner(): void
    {
        Storage::fake('local');
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $otherTeacher = $this->createAssignedTeacher('guru', ['guru.schedule']);

        $this->actingAs($teacher)
            ->post(route('guru.pegawai-changes.store'), [
                'changes' => ['nip' => '123456789'],
                'evidence' => [
                    'nip' => UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf'),
                ],
            ])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $changeRequest = PegawaiChangeRequest::query()
            ->where('submitted_by', $teacher->id)
            ->with('items')
            ->firstOrFail();
        $item = $changeRequest->items->firstOrFail();
        Storage::disk('local')->assertExists($item->evidence_path);

        $this->actingAs($otherTeacher)
            ->get(route('pegawai-changes.evidence', $item))
            ->assertForbidden();

        $this->actingAs($teacher)
            ->get(route('pegawai-changes.evidence', $item))
            ->assertOk();

        DB::table('users')->where('id', $teacher->id)->update(['is_active' => false]);
        $this->get(route('pegawai-changes.evidence', $item))
            ->assertRedirect(route('login'));
        $this->assertGuest();

        $kepsek = User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@example.test',
            'username' => 'kepsek',
            'password' => 'test-password',
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        foreach ([
            ['is_active' => false, 'approval_status' => 'approved'],
            ['is_active' => true, 'approval_status' => 'pending'],
        ] as $status) {
            $kepsek->update($status);

            $this->actingAs($kepsek->fresh())
                ->get(route('pegawai-changes.evidence', $item))
                ->assertRedirect(route('login'));
            $this->assertGuest();
        }
    }

    public function test_nip_changes_reject_existing_identifiers_at_submission_and_verification(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $otherTeacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $otherTeacher->pegawai->update(['nip' => '123456789']);

        $this->actingAs($teacher)
            ->post(route('guru.pegawai-changes.store'), [
                'changes' => ['nip' => '123456789'],
            ])
            ->assertSessionHasErrors('changes.nip');

        $changeRequest = $this->makeRequest($teacher, $teacher->pegawai->nama, '123456789');
        $item = $changeRequest->items()->firstOrFail();
        $item->update([
            'field' => 'nip',
            'old_value' => null,
        ]);

        $this->actingAs($operator)
            ->patch(route('tu.pegawai-changes.approve', $item))
            ->assertRedirect()
            ->assertSessionHas('warning');

        $this->assertDatabaseHas('data_kepegawaian', [
            'id' => $teacher->pegawai_id,
            'nip' => null,
        ]);
        $this->assertDatabaseHas('pegawai_change_items', [
            'id' => $item->id,
            'status' => 'rejected',
        ]);
    }

    public function test_partial_decisions_update_the_request_aggregate_status(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $changeRequest = $this->makeRequest(
            $teacher,
            $teacher->pegawai->nama,
            'Nama Disetujui'
        );
        $secondItem = $changeRequest->items()->create([
            'field' => 'tempat_lahir',
            'old_value' => null,
            'new_value' => 'Kota Baru',
            'status' => 'pending',
        ]);
        $firstItem = $changeRequest->items()->firstOrFail();

        $this->actingAs($operator)
            ->patch(route('tu.pegawai-changes.approve', $firstItem))
            ->assertRedirect();
        $this->actingAs($operator)
            ->patch(route('tu.pegawai-changes.reject', $secondItem), [
                'reason' => 'Bukti belum memadai.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('pegawai_change_requests', [
            'id' => $changeRequest->id,
            'status' => 'partially_processed',
        ]);
    }

    public function test_stale_snapshot_is_rejected_without_overwriting_newer_official_data(): void
    {
        $teacher = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $originalName = $teacher->pegawai->nama;
        $changeRequest = $this->makeRequest($teacher, $originalName, 'Nama yang Diajukan');
        $item = $changeRequest->items()->firstOrFail();
        $teacher->pegawai->update(['nama' => 'Nama Terbaru']);

        $this->actingAs($operator)
            ->patch(route('tu.pegawai-changes.approve', $item))
            ->assertRedirect()
            ->assertSessionHas('warning');

        $this->assertDatabaseHas('data_kepegawaian', [
            'id' => $teacher->pegawai_id,
            'nama' => 'Nama Terbaru',
        ]);
        $this->assertDatabaseHas('pegawai_change_items', [
            'id' => $item->id,
            'status' => 'rejected',
        ]);
    }

    public function test_operator_legacy_edit_only_updates_administrative_fields(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $employee = DataKepegawaian::create([
            'nama' => 'Nama Resmi',
            'jabatan' => 'Guru',
        ]);

        $this->actingAs($operator)
            ->put(route('tu.datakepegawaian.update', $employee), [
                'nama' => 'Nama dari Request',
                'nip' => '999999',
                'jabatan' => 'Operator Sekolah',
            ])
            ->assertRedirect(route('tu.datakepegawaian.index'));

        $this->assertDatabaseHas('data_kepegawaian', [
            'id' => $employee->id,
            'nama' => 'Nama Resmi',
            'jabatan' => 'Operator Sekolah',
        ]);
    }

    public function test_kepsek_can_monitor_multiple_teachers_without_an_approval_action(): void
    {
        $teacherA = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $teacherB = $this->createAssignedTeacher('guru', ['guru.schedule']);
        $requestA = $this->makeRequest($teacherA, 'Nama A', 'Nama A Baru');
        $requestB = $this->makeRequest($teacherB, 'Nama B', 'Nama B Baru');
        $kepsek = User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@example.test',
            'username' => 'kepsek',
            'password' => 'test-password',
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($kepsek)
            ->get(route('superadmin.pegawai-changes.employees'))
            ->assertOk()
            ->assertSee($teacherA->pegawai->nama);

        $this->actingAs($kepsek)
            ->get(route('superadmin.pegawai-changes.employee', $teacherA->pegawai))
            ->assertOk()
            ->assertSee($teacherA->pegawai->nama);

        $this->actingAs($kepsek)
            ->get(route('superadmin.pegawai-changes.index'))
            ->assertOk()
            ->assertSee($teacherA->pegawai->nama)
            ->assertSee($teacherB->pegawai->nama)
            ->assertDontSee('Setujui dan Terapkan');

        $this->actingAs($kepsek)
            ->get(route('superadmin.pegawai-changes.show', $requestA))
            ->assertOk()
            ->assertSee('Nama A Baru');

        $monitoringRoutes = [
            ['superadmin.pegawai-changes.employees', []],
            ['superadmin.pegawai-changes.index', []],
            ['superadmin.pegawai-changes.employee', [$requestA->data_kepegawaian_id]],
            ['superadmin.pegawai-changes.show', [$requestA]],
        ];

        foreach ([
            ['is_active' => false, 'approval_status' => 'approved'],
            ['is_active' => true, 'approval_status' => 'pending'],
        ] as $status) {
            $kepsek->update($status);

            foreach ($monitoringRoutes as [$routeName, $parameters]) {
                $this->actingAs($kepsek->fresh())
                    ->get(route($routeName, $parameters))
                    ->assertRedirect(route('login'));
                $this->assertGuest();
            }
        }

        $kepsek->update([
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
        $this->actingAs($kepsek);
        DB::table('users')->where('id', $kepsek->id)->update(['is_active' => false]);

        $this->get(route('superadmin.pegawai-changes.index'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    private function makeRequest(User $teacher, string $oldValue, string $newValue): PegawaiChangeRequest
    {
        $changeRequest = PegawaiChangeRequest::create([
            'data_kepegawaian_id' => $teacher->pegawai_id,
            'submitted_by' => $teacher->id,
            'submitted_by_name' => $teacher->name,
            'status' => 'pending',
        ]);
        $changeRequest->items()->create([
            'field' => 'nama',
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'status' => 'pending',
        ]);

        return $changeRequest;
    }
}
