<?php

namespace Tests\Feature\Auth;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = $this->makeStudentUser();

        $response = $this->post('/login', [
            'username' => $user->siswa->nis,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('siswa.dashboard.index'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = $this->makeStudentUser();

        $this->post('/login', [
            'username' => $user->siswa->nis,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    private function makeStudentUser(): User
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'nis' => (string) random_int(10000000, 99999999),
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2018-01-01',
        ]);

        $user->update(['siswa_id' => $siswa->id]);

        return $user->fresh('siswa');
    }
}
