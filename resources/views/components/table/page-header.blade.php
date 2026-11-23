@props([
    'icon' => 'bi-table',
    'eyebrow',
    'title',
    'description',
])

<header class="dt-siswa-page-header">
    <div>
        <p class="dt-siswa-eyebrow">
            <i class="bi {{ $icon }}" aria-hidden="true"></i>
            {{ $eyebrow }}
        </p>
        <h1 class="dt-siswa-page-title">{{ $title }}</h1>
        <p class="dt-siswa-page-description">{{ $description }}</p>
    </div>
</header>
