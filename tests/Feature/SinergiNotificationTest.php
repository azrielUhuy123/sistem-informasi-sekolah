<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\SinergiNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;
use Tests\TestCase;

class SinergiNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_is_persisted_for_the_selected_user_with_the_expected_payload(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $user->notify($this->notification());

        $storedNotification = $user->notifications()->sole();

        $this->assertDatabaseCount('notifications', 1);
        $this->assertSame(User::class, $storedNotification->notifiable_type);
        $this->assertSame((string) $user->getKey(), (string) $storedNotification->notifiable_id);
        $this->assertSame(SinergiNotification::class, $storedNotification->type);
        $this->assertSame([
            'title' => 'Jadwal perlu ditinjau',
            'message' => 'Pengajuan jadwal menunggu persetujuan.',
            'category' => 'jadwal',
            'priority' => 'action_required',
            'action_url' => '/superadmin/monitoring-jadwal',
            'action_label' => 'Tinjau jadwal',
            'entity_type' => 'PengajuanJadwal',
            'entity_id' => 42,
        ], $storedNotification->data);
        $this->assertNull($storedNotification->read_at);
        $this->assertCount(0, $otherUser->notifications);
    }

    public function test_a_user_cannot_retrieve_another_users_notification_through_their_notifications_relation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $userB->notify($this->notification());

        $notificationB = $userB->notifications()->sole();

        $this->assertCount(0, $userA->notifications);
        $this->assertNull($userA->notifications()->find($notificationB->getKey()));
        $this->assertSame($userB->getKey(), $notificationB->notifiable_id);
    }

    public function test_notification_read_state_uses_the_native_database_notification_model(): void
    {
        $user = User::factory()->create();

        $user->notify($this->notification());

        $this->assertCount(1, $user->unreadNotifications);

        $user->unreadNotifications()->sole()->markAsRead();

        $user->refresh();

        $this->assertCount(0, $user->unreadNotifications);
        $this->assertNotNull($user->notifications()->sole()->read_at);
    }

    public function test_only_supported_categories_and_priorities_are_accepted(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new SinergiNotification(
            title: 'Invalid category',
            message: 'This category is not supported.',
            category: 'unknown',
            priority: 'info',
        );
    }

    public function test_unsupported_priority_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new SinergiNotification(
            title: 'Invalid priority',
            message: 'This priority is not supported.',
            category: 'sistem',
            priority: 'urgent',
        );
    }

    private function notification(): SinergiNotification
    {
        return new SinergiNotification(
            title: 'Jadwal perlu ditinjau',
            message: 'Pengajuan jadwal menunggu persetujuan.',
            category: 'jadwal',
            priority: 'action_required',
            actionUrl: '/superadmin/monitoring-jadwal',
            actionLabel: 'Tinjau jadwal',
            entityType: 'PengajuanJadwal',
            entityId: 42,
        );
    }
}