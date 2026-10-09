@if ($notification_visible)
    <div class="dropdown notifications-dropdown">
        <button
            class="btn btn-light border notifications-toggle"
            type="button"
            id="notificationsDropdown"
            data-bs-toggle="dropdown"
            data-bs-display="static"
            aria-expanded="false"
            aria-label="{{ $unread_count > 0 ? $unread_count . ' notifikasi belum dibaca' : 'Notifikasi' }}"
        >
            <i class="bi bi-bell" aria-hidden="true"></i>
            @if ($unread_count > 0)
                <span class="notifications-count" aria-hidden="true">
                    {{ $unread_count > 99 ? '99+' : $unread_count }}
                </span>
            @endif
        </button>

        <div
            class="dropdown-menu dropdown-menu-end shadow-sm notifications-menu"
            aria-labelledby="notificationsDropdown"
        >
            <div class="d-flex align-items-center justify-content-between px-2 pb-2">
                <strong>Notifikasi</strong>
                @if ($unread_count > 0)
                    <span class="badge text-bg-primary">{{ $unread_count }} belum dibaca</span>
                @endif
            </div>

            @forelse ($recent_notifications as $notification)
                @php
                    $category = data_get($notification->data, 'category', 'sistem');
                @endphp
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                    @csrf
                    <button
                        type="submit"
                        class="dropdown-item notifications-preview {{ $notification->read_at ? 'is-read' : 'is-unread' }}"
                    >
                        <i class="bi bi-{{ $notification->read_at ? 'envelope-open' : 'envelope' }} mt-1" aria-hidden="true"></i>
                        <span class="notifications-preview-copy">
                            <span class="notifications-preview-title">{{ data_get($notification->data, 'title', 'Notifikasi') }}</span>
                            <span class="notifications-preview-message">{{ data_get($notification->data, 'message', '') }}</span>
                            <span class="small text-secondary">{{ str_replace('_', ' ', ucfirst($category)) }} · {{ $notification->created_at?->diffForHumans() }}</span>
                        </span>
                    </button>
                </form>
            @empty
                <div class="notification-empty py-4">
                    <i class="bi bi-bell-slash" aria-hidden="true"></i>
                    <span>Belum ada notifikasi.</span>
                </div>
            @endforelse

            <div class="border-top mt-2 pt-2 text-center">
                <a class="dropdown-item text-primary text-center" href="{{ route('notifications.index') }}">
                    Lihat semua
                </a>
            </div>
        </div>
    </div>
@endif