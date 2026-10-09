<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use InvalidArgumentException;

class SinergiNotification extends Notification
{
    public const CATEGORIES = [
        'ppdb',
        'jadwal',
        'dokumen',
        'user',
        'sistem',
        'akademik',
    ];

    public const PRIORITIES = [
        'info',
        'success',
        'warning',
        'action_required',
        'critical',
    ];

    public function __construct(
        private readonly string $title,
        private readonly string $message,
        private readonly string $category,
        private readonly string $priority,
        private readonly ?string $actionUrl = null,
        private readonly ?string $actionLabel = null,
        private readonly ?string $entityType = null,
        private readonly string|int|null $entityId = null,
    ) {
        if (! in_array($category, self::CATEGORIES, true)) {
            throw new InvalidArgumentException('Unsupported notification category.');
        }

        if (! in_array($priority, self::PRIORITIES, true)) {
            throw new InvalidArgumentException('Unsupported notification priority.');
        }
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'category' => $this->category,
            'priority' => $this->priority,
            'action_url' => $this->actionUrl,
            'action_label' => $this->actionLabel,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
        ];
    }
}