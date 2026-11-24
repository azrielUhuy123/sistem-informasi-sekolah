<?php

namespace Tests\Feature\Tu;

use App\Models\DataKepegawaian;
use App\Models\Penugasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmployeeAccountSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_employee_account_requires_approval_and_one_time_password_setup(): void
    {
        $operator = $this->createAssignedTeacher('operator', [
            'operator.employees',
            'operator.accounts',
        ]);
        $employee = DataKepegawaian::create([
            'nama' => 'Fiktif Pegawai Baru',
            'nip' => '198001020099',
            'tanggal_lahir' => '1990-01-02',
            'jabatan' => 'Guru',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($operator)
            ->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertRedirect();

        $user = User::where('pegawai_id', $employee->id)->firstOrFail();
        $this->assertSame('guru', $user->role);
        $this->assertSame('pending', $user->approval_status);
        $this->assertFalse($user->is_active);
        $this->assertTrue($user->must_change_password);
        $this->assertFalse(Hash::check('19900102', $user->password));
        $this->assertFalse(Hash::check('02011990', $user->password));

        $this->post(route('logout'));
        $this->post(route('login'), [
            'username' => $employee->nip,
            'password' => '19900102',
        ])->assertSessionHasErrors('username');
        $this->assertGuest();

        $kepsek = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
        $this->actingAs($kepsek)
            ->patch(route('superadmin.users.approve', $user))
            ->assertRedirect();

        $user->refresh();
        $this->assertSame('approved', $user->approval_status);
        $this->assertFalse($user->is_active);
        $this->assertTrue($user->must_change_password);

        $this->actingAs($user)
            ->get(route('guru.pegawai-changes.index'))
            ->assertRedirect(route('login'));
        $this->assertGuest();

        $response = $this->actingAs($operator->fresh())
            ->post(route('tu.akun.reset-password', $user))
            ->assertOk()
            ->assertViewIs('tu.akun.password-reset-link')
            ->assertHeader('Cache-Control', 'no-store, private');

        $resetUrl = $response->viewData('resetUrl');
        $token = basename(parse_url($resetUrl, PHP_URL_PATH));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email, 'token' => $token]);

        $this->post(route('logout'));
        $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Secure-new-password-123!',
            'password_confirmation' => 'Secure-new-password-123!',
        ])->assertRedirect(route('login'));

        $user->refresh();
        $this->assertTrue($user->is_active);
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(Hash::check('Secure-new-password-123!', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

        $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Another-password-123!',
            'password_confirmation' => 'Another-password-123!',
        ])->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('Secure-new-password-123!', $user->fresh()->password));

        $this->post(route('login'), [
            'username' => $employee->nip,
            'password' => 'Secure-new-password-123!',
        ])->assertRedirect(route('guru.pegawai-changes.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_expired_operator_reset_link_does_not_change_password(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.accounts']);
        $user = User::factory()->create([
            'role' => 'guru',
            'approval_status' => 'approved',
            'is_active' => true,
            'password' => 'old-password-123',
        ]);
        $oldPassword = $user->getRawOriginal('password');

        $response = $this->actingAs($operator)
            ->post(route('tu.akun.reset-password', $user))
            ->assertOk();
        $token = basename(parse_url($response->viewData('resetUrl'), PHP_URL_PATH));

        DB::table(config('auth.passwords.users.table'))
            ->where('email', $user->email)
            ->update(['created_at' => now()->subMinutes(config('auth.passwords.users.expire') + 1)]);

        $this->post(route('logout'));
        $this->post(route('password.store'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Secure-new-password-123!',
            'password_confirmation' => 'Secure-new-password-123!',
        ])->assertSessionHasErrors('email');

        $this->assertSame($oldPassword, $user->fresh()->getRawOriginal('password'));
    }

    public function test_operator_cannot_reset_or_reactivate_superadmin_account(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.accounts']);
        $kepsek = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => false,
        ]);

        $this->actingAs($operator)
            ->post(route('tu.akun.reset-password', $kepsek))
            ->assertForbidden();

        $this->post(route('tu.akun.toggle-status', $kepsek))
            ->assertForbidden();

        $this->assertFalse($kepsek->fresh()->is_active);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $kepsek->email]);
    }

    public function test_status_controls_cannot_activate_an_account_before_password_setup(): void
    {
        $user = User::factory()->create([
            'role' => 'guru',
            'approval_status' => 'approved',
            'is_active' => false,
            'must_change_password' => true,
        ]);
        $operator = $this->createAssignedTeacher('operator', ['operator.accounts']);
        $admin = User::factory()->create([
            'role' => 'admin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);
        $kepsek = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        $this->actingAs($operator)
            ->post(route('tu.akun.toggle-status', $user))
            ->assertStatus(409);

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle', $user))
            ->assertStatus(409);

        $this->actingAs($kepsek)
            ->post(route('superadmin.users.unblock', $user))
            ->assertStatus(409);

        $this->assertFalse($user->fresh()->is_active);
    }

    public function test_blocked_superadmin_cannot_self_reactivate_during_existing_session(): void
    {
        $kepsek = User::factory()->create([
            'role' => 'superadmin',
            'approval_status' => 'approved',
            'is_active' => true,
            'must_change_password' => false,
        ]);
        $target = User::factory()->create([
            'role' => 'guru',
            'approval_status' => 'approved',
            'is_active' => false,
            'must_change_password' => false,
        ]);

        $this->actingAs($kepsek);
        DB::table('users')->where('id', $kepsek->id)->update(['is_active' => false]);

        $this->post(route('superadmin.users.unblock', $target))
            ->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertFalse($target->fresh()->is_active);

        $this->actingAs($kepsek->fresh())
            ->post(route('superadmin.users.block', $kepsek))
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_employee_account_creation_rejects_unpublished_and_non_operator_assignments(): void
    {
        $unpublished = $this->createAssignedTeacher('operator', ['operator.employees']);
        Penugasan::where('data_kepegawaian_id', $unpublished->pegawai_id)
            ->update(['published_at' => null]);
        $employee = DataKepegawaian::create([
            'nama' => 'Fiktif Pegawai',
            'nip' => '198001020098',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($unpublished)
            ->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertForbidden();

        $wrongType = $this->createAssignedTeacher('ppdb', ['operator.employees']);
        $this->actingAs($wrongType)
            ->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertForbidden();

        $legacyRole = $this->createAssignedTeacher('operator', ['operator.employees']);
        DB::table('users')->where('id', $legacyRole->id)->update(['role' => 'tu']);
        $this->actingAs($legacyRole)
            ->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['pegawai_id' => $employee->id]);
    }

    public function test_legacy_create_account_alias_requires_the_employee_assignment(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.accounts']);
        $employee = DataKepegawaian::create([
            'nama' => 'Fiktif Pegawai',
            'nip' => '198001020092',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($operator)
            ->post(route('tu.datakepegawaian.create-account', $employee))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['pegawai_id' => $employee->id]);
    }

    public function test_operator_cannot_create_a_superadmin_account_from_an_employee_job_title(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $employee = DataKepegawaian::create([
            'nama' => 'Fiktif Kepala Sekolah',
            'nip' => '198001020097',
            'jabatan' => 'Kepala Sekolah',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($operator)
            ->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['pegawai_id' => $employee->id]);
    }

    public function test_active_session_is_revalidated_after_operator_account_is_disabled(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $employee = DataKepegawaian::create([
            'nama' => 'Fiktif Pegawai',
            'nip' => '198001020096',
            'approval_status' => 'approved',
        ]);
        $this->actingAs($operator);

        DB::table('users')->where('id', $operator->id)->update(['is_active' => false]);

        $this->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['pegawai_id' => $employee->id]);
    }

    public function test_active_session_is_revalidated_after_operator_approval_is_revoked(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $employee = DataKepegawaian::create([
            'nama' => 'Fiktif Pegawai',
            'nip' => '198001020091',
            'approval_status' => 'approved',
        ]);
        $this->actingAs($operator);

        DB::table('users')->where('id', $operator->id)->update(['approval_status' => 'pending']);

        $this->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertRedirect(route('login'));
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['pegawai_id' => $employee->id]);
    }

    public function test_active_operator_session_loses_access_when_assignment_is_revoked(): void
    {
        $operator = $this->createAssignedTeacher('operator', ['operator.employees']);
        $employee = DataKepegawaian::create([
            'nama' => 'Fiktif Pegawai',
            'nip' => '198001020093',
            'approval_status' => 'approved',
        ]);

        $this->actingAs($operator);
        DB::table('penugasans')
            ->where('data_kepegawaian_id', $operator->pegawai_id)
            ->update(['status' => 'revoked']);

        $this->post(route('tu.datakepegawaian.request-account', $employee))
            ->assertForbidden();
        $this->assertDatabaseMissing('users', ['pegawai_id' => $employee->id]);
    }
}
