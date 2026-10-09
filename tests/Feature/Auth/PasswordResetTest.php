<?php

namespace Tests\Feature\Auth;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertOk()
            ->assertViewIs('auth.forgot-password')
            ->assertSee(route('password.email'));

        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('password.request'))
            ->assertSee('Butuh Bantuan?');
    }

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $user = $this->makeNonStudentUser();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertRedirect();

        Notification::assertSentTo($user, ResetPassword::class);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_reset_password_screen_can_be_rendered(): void
    {
        Notification::fake();

        $user = $this->makeNonStudentUser();

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->get(route('password.reset', [
                'token' => $notification->token,
                'email' => $user->email,
            ]));

            $response->assertOk()
                ->assertViewIs('auth.reset-password')
                ->assertSee('name="token"', false)
                ->assertSee('name="email"', false)
                ->assertSee('name="password"', false);

            return true;
        });
    }

    public function test_invalid_reset_token_is_rejected(): void
    {
        $user = $this->makeNonStudentUser();
        $originalPassword = $user->getRawOriginal('password');

        $this->post(route('password.store'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertSessionHasErrors('email');

        $user->refresh();
        $this->assertSame($originalPassword, $user->getRawOriginal('password'));
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $user = $this->makeNonStudentUser();

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post(route('password.store'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ]);

            $response
                ->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            $resetUser = $user->fresh();
            $this->assertTrue(Hash::check('new-password-123', $resetUser->password));

            return true;
        });
    }

    public function test_students_are_directed_to_tu_instead_of_public_reset_email(): void
    {
        Notification::fake();
        $user = $this->makeStudentUser();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }

    public function test_student_reset_link_and_submission_are_rejected(): void
    {
        Notification::fake();
        $user = $this->makeStudentUser();

        Password::sendResetLink(['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->get(route('password.reset', [
                'token' => $notification->token,
                'email' => $user->email,
            ]))
                ->assertRedirect(route('password.request'))
                ->assertSessionHasErrors('email');

            $this->post(route('password.store'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])->assertSessionHasErrors('email');

            return true;
        });
    }

    public function test_expired_reset_token_is_rejected(): void
    {
        Notification::fake();
        $user = $this->makeNonStudentUser();
        $originalPassword = $user->getRawOriginal('password');

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user, $originalPassword) {
            DB::table(config('auth.passwords.users.table'))
                ->where('email', $user->email)
                ->update(['created_at' => now()->subMinutes(config('auth.passwords.users.expire') + 1)]);

            $this->post(route('password.store'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])->assertSessionHasErrors('email');

            $user->refresh();
            $this->assertSame($originalPassword, $user->getRawOriginal('password'));

            return true;
        });
    }

    public function test_non_student_password_reset_behavior_is_unchanged(): void
    {
        Notification::fake();
        $user = User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->post(route('password.store'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])->assertSessionHasNoErrors()
                ->assertRedirect(route('login'));

            $resetUser = $user->fresh();
            $this->assertTrue(Hash::check('new-password-123', $resetUser->password));

            return true;
        });
    }

    public function test_reset_link_throttle_prevents_immediate_duplicate_email(): void
    {
        Notification::fake();
        $user = $this->makeNonStudentUser();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status', trans(Password::RESET_LINK_SENT));

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHasErrors('email');

        Notification::assertSentToTimes($user, ResetPassword::class, 1);
    }

    private function makeStudentUser(): User
    {
        $user = User::factory()->create([
            'name' => 'Password Reset Student',
            'email' => 'password-reset-'.uniqid().'@example.test',
            'username' => 'password-reset-'.uniqid(),
            'role' => 'siswa',
            'approval_status' => 'approved',
            'approved_at' => now(),
            'is_active' => true,
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'nis' => '98'.random_int(1000000, 9999999),
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2018-01-01',
        ]);

        $user->update(['siswa_id' => $siswa->id]);

        return $user;
    }

    private function makeNonStudentUser(): User
    {
        return User::factory()->create([
            'role' => 'tu',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
    }
}
