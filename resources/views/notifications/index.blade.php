@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="container-fluid py-4 notifications-page">
        <header>
            <div>
                <h1>Notifikasi</h1>
                <p class="text-secondary mb-0">Pemberitahuan untuk akun Anda.</p>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge text-bg-primary">{{ $unreadCount }} belum dibaca</span>
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.readAll') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-check2-all me-1" aria-hidden="true"></i>
                            Tandai semua dibaca
                        </button>
                    </form>
                @endif
            </div>
        </header>

        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        @if ($notifications->isEmpty())
            <div class="notification-empty border rounded bg-white">
                <i class="bi bi-bell-slash" aria-hidden="true"></i>
                <strong class="d-block text-body">Belum ada notifikasi</strong>
                <span>Notifikasi untuk akun Anda akan muncul di sini.</span>
            </div>
        @else
            <div class="notification-list" role="list">
                @foreach ($notifications as $notification)
                    @php
                        $category = data_get($notification->data, 'category', 'sistem');
                        $priority = data_get($notification->data, 'priority', 'info');
                        $priorityClass = match ($priority) {
                            'success' => 'text-bg-success',
                            'warning' => 'text-bg-warning',
                            'action_required' => 'text-bg-primary',
                            'critical' => 'text-bg-danger',
                            default => 'text-bg-info',
                        };
                        $actionUrl = $actionUrls->get($notification->id);
                    @endphp

                    <article class="notification-row {{ $notification->read_at ? 'is-read' : 'is-unread' }}" role="listitem">
                        <div class="notification-row-content">
                            <div class="notification-row-meta">
                                <span class="badge bg-light text-secondary border">
                                    {{ str_replace('_', ' ', ucfirst($category)) }}
                                </span>
                                <span class="badge {{ $priorityClass }}">
                                    {{ str_replace('_', ' ', ucfirst($priority)) }}
                                </span>
                                @if ($notification->read_at)
                                    <span class="small text-secondary">Dibaca</span>
                                @else
                                    <span class="small fw-semibold text-primary">Belum dibaca</span>
                                @endif
                                <time datetime="{{ $notification->created_at?->toIso8601String() }}">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </time>
                            </div>
                            <h2>{{ data_get($notification->data, 'title', 'Notifikasi') }}</h2>
                            <p>{{ data_get($notification->data, 'message', '') }}</p>
                        </div>

                        <div class="notification-row-action">
                            @if ($actionUrl)
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        {{ data_get($notification->data, 'action_label') ?: 'Buka' }}
                                    </button>
                                </form>
                            @elseif (! $notification->read_at)
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            @include('components.table.pagination', ['paginator' => $notifications])
        @endif
    </div>
@endsection