<?php

namespace Tests\Feature\Tu;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AkunPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_assigned_operator_can_issue_a_one_time_student_password_reset_link(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'approval_status' => 'approved',
            'is_active' => true,
            'password' => Hash::make('previous-password'),
        ]);

        $siswa = Siswa::create([
            'user_id' => $student->id,
            'nama' => $student->name,
            'nis' => '12000001',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2018-01-01',
        ]);
        $student->forceFill(['siswa_id' => $siswa->id])->save();

        $operatorTeacher = $this->createAssignedTeacher('operator', ['operator.accounts']);

        $response = $this->actingAs($operatorTeacher)
            ->post(route('tu.akun.reset-password', $student))
            ->assertOk()
            ->assertViewIs('tu.akun.password-reset-link')
            ->assertHeader('Cache-Control', 'no-store, private');

        $this->assertTrue(Hash::check('previous-password', $student->fresh()->password));
        $resetUrl = $response->viewData('resetUrl');
        $token = basename(parse_url($resetUrl, PHP_URL_PATH));
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $student->email,
            'token' => $token,
        ]);

        $this->post(route('logout'));
        $this->post(route('password.store'), [
            'token' => $token,
            'email' => $student->email,
            'password' => 'student-new-password-123',
            'password_confirmation' => 'student-new-password-123',
        ])->assertRedirect(route('login'));

        $this->post(route('login'), [
            'username' => $siswa->nis,
            'password' => 'student-new-password-123',
        ])->assertRedirect(route('siswa.dashboard.index'));

        $this->assertAuthenticatedAs($student);
    }
}