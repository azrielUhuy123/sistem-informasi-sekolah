<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\SinergiNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationInboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_the_inbox(): void
    {
        $this->get(route('notifications.index'))
            ->assertRedirect(route('login'));
    }

    public function test_only_the_five_target_roles_can_access_the_inbox(): void
    {
        foreach (['superadmin', 'admin', 'tu', 'guru', 'siswa'] as $role) {
            $user = $this->makeUser($role);

            $this->actingAs($user)
                ->get(route('notifications.index'))
                ->assertOk();
        }

        $spmbUser = $this->makeUser('spmb');

        $this->actingAs($spmbUser)
            ->get(route('notifications.index'))
            ->assertForbidden();
    }

    public function test_authenticated_user_opens_inbox_and_only_sees_their_notifications(): void
    {
        $user = $this->makeUser();
        $otherUser = $this->makeUser();

        $this->notify($user, 'Visible notification');
        $this->notify($otherUser, 'Private notification');

        $response = $this->actingAs($user)->get('/notifications');

        $response->assertOk()
            ->assertViewIs('notifications.index')
            ->assertViewHas('unreadCount', 1)
            ->assertSee('1 notifikasi belum dibaca')
            ->assertSee('Sistem')
            ->assertSee('Info')
            ->assertSee('Visible notification')
            ->assertDontSee('Private notification');
    }

    public function test_user_can_mark_their_notification_as_read_and_follow_a_relative_internal_action(): void
    {
        $user = $this->makeUser();
        $this->notify($user, 'Open dashboard', '/superadmin/dashboard', 'Lihat dashboard');
        $notification = $user->notifications()->sole();

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect('/superadmin/dashboard');

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_follow_an_absolute_same_origin_internal_action(): void
    {
        $user = $this->makeUser();
        $this->notify($user, 'Open dashboard', url('/superadmin/dashboard'), 'Lihat dashboard');
        $notification = $user->notifications()->sole();

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect('/superadmin/dashboard');

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_cannot_mark_another_users_notification_as_read(): void
    {
        $user = $this->makeUser();
        $otherUser = $this->makeUser();
        $this->notify($otherUser, 'Other user notification');
        $notification = $otherUser->notifications()->sole();

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertNotFound();

        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_read_all_only_updates_the_authenticated_users_unread_notifications(): void
    {
        $user = $this->makeUser();
        $otherUser = $this->makeUser();
        $this->notify($user, 'First own notification');
        $this->notify($user, 'Second own notification');
        $this->notify($otherUser, 'Other user unread notification');

        $this->actingAs($user)
            ->post(route('notifications.readAll'))
            ->assertRedirect(route('notifications.index'));

        $this->assertSame(0, $user->fresh()->unreadNotifications()->count());
        $this->assertSame(1, $otherUser->fresh()->unreadNotifications()->count());
    }

    public function test_inbox_shows_a_read_action_without_a_cta_when_action_url_is_missing(): void
    {
        $user = $this->makeUser();
        $this->notify($user, 'No action notification');
        $notification = $user->notifications()->sole();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertSee('Tandai dibaca')
            ->assertDontSee('Lihat profil');

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect(route('notifications.index'));

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_external_action_url_is_not_rendered_or_redirected(): void
    {
        $user = $this->makeUser();
        $this->notify($user, 'Unsafe action', 'https://outside.example/path', 'Go outside');
        $notification = $user->notifications()->sole();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertDontSee('Go outside');

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect(route('notifications.index'));
    }

    public function test_protocol_relative_external_action_url_is_not_rendered_or_redirected(): void
    {
        $user = $this->makeUser();
        $this->notify($user, 'Protocol relative action', '//evil.example/path', 'Unsafe link');
        $notification = $user->notifications()->sole();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertDontSee('Unsafe link');

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect(route('notifications.index'));
    }

    public function test_non_http_action_schemes_are_not_rendered_or_redirected(): void
    {
        foreach ([
            'javascript:alert(1)',
            'data:text/html,unsafe',
            'vbscript:msgbox(1)',
            'ftp://example.test/path',
        ] as $index => $actionUrl) {
            $user = $this->makeUser();
            $this->notify($user, 'Unsafe scheme '.$index, $actionUrl, 'Unsafe scheme link');
            $notification = $user->notifications()->sole();

            $this->actingAs($user)
                ->get(route('notifications.index'))
                ->assertOk()
                ->assertDontSee('Unsafe scheme link');

            $this->actingAs($user)
                ->post(route('notifications.read', $notification->id))
                ->assertRedirect(route('notifications.index'));
        }
    }

    public function test_userinfo_and_encoded_path_traversal_action_urls_are_rejected(): void
    {
        $validAbsoluteUrl = url('/superadmin/dashboard');
        $parts = parse_url($validAbsoluteUrl);
        $sameOriginWithUserInfo = $parts['scheme'].'://user:pass@'.$parts['host']
            .(isset($parts['port']) ? ':'.$parts['port'] : '')
            .$parts['path'];

        foreach ([
            '/superadmin/../dashboard',
            '/superadmin/%2e%2e/dashboard',
            '/superadmin/%252e%252e/dashboard',
            $sameOriginWithUserInfo,
        ] as $index => $actionUrl) {
            $user = $this->makeUser();
            $this->notify($user, 'Malformed action '.$index, $actionUrl, 'Malformed action link');
            $notification = $user->notifications()->sole();

            $this->actingAs($user)
                ->get(route('notifications.index'))
                ->assertOk()
                ->assertDontSee('Malformed action link');

            $this->actingAs($user)
                ->post(route('notifications.read', $notification->id))
                ->assertRedirect(route('notifications.index'));
        }
    }

    public function test_gateway_fallback_action_url_is_not_rendered_or_redirected(): void
    {
        $user = $this->makeUser();
        $this->notify($user, 'Gateway action', '/notification-action-unregistered', 'Open gateway fallback');
        $notification = $user->notifications()->sole();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertDontSee('Open gateway fallback');

        $this->actingAs($user)
            ->post(route('notifications.read', $notification->id))
            ->assertRedirect(route('notifications.index'));
    }

    public function test_bell_is_visible_only_to_the_five_target_roles(): void
    {
        foreach (['superadmin', 'admin', 'tu', 'guru', 'siswa'] as $role) {
            $this->actingAs($this->makeUser($role));
            $markup = view('layouts.partials.notifications')->render();

            $this->assertStringContainsString('notifications-toggle', $markup);
        }

        $this->actingAs($this->makeUser('spmb'));
        $markup = view('layouts.partials.notifications')->render();

        $this->assertStringNotContainsString('notifications-toggle', $markup);
    }

    public function test_empty_inbox_shows_an_empty_state(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertSee('Belum ada notifikasi')
            ->assertDontSee('Tandai semua dibaca');
    }

    public function test_inbox_paginates_notifications_instead_of_loading_them_all(): void
    {
        $user = $this->makeUser();

        for ($index = 1; $index <= 11; $index++) {
            $this->notify($user, 'Paginated notification '.$index);
        }

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertViewHas('notifications', function ($notifications) {
                return $notifications->count() === 10
                    && $notifications->total() === 11
                    && $notifications->hasMorePages();
            });
    }

    public function test_notifications_route_is_not_consumed_by_the_gateway_catch_all(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->get('/notifications')
            ->assertOk()
            ->assertViewIs('notifications.index');

        $this->get('/unreserved-gateway-path')
            ->assertOk()
            ->assertViewIs('gateway.index');
    }

    private function makeUser(string $role = 'guru'): User
    {
        /** @var User $user */
        $user = User::factory()->create(['role' => $role]);

        return $user;
    }

    private function notify(User $user, string $title, ?string $actionUrl = null, ?string $actionLabel = null): void
    {
        $user->notify(new SinergiNotification(
            title: $title,
            message: 'Notification message for '.$title,
            category: 'sistem',
            priority: 'info',
            actionUrl: $actionUrl,
            actionLabel: $actionLabel,
        ));
    }
}