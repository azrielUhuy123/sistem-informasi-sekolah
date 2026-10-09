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

    public function test_tu_reset_restores_student_password_to_date_of_birth(): void
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

        $tu = User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($tu)
            ->post(route('tu.akun.reset-password', $student))
            ->assertSessionHas('success', 'Password berhasil direset.');

        $this->assertTrue(Hash::check('01012018', $student->fresh()->password));

        $this->post(route('logout'));

        $this->post(route('login'), [
            'username' => $siswa->nis,
            'password' => '01012018',
        ])->assertRedirect(route('siswa.dashboard.index'));

        $this->assertAuthenticatedAs($student);
    }
}