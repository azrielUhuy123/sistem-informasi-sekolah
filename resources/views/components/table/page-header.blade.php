@props([
    'icon' => 'bi-table',
    
    'title',
    'description',
])

<header class="dt-siswa-page-header">
    <div>        
        <h1 class="dt-siswa-page-title">{{ $title }}</h1>
        <p class="dt-siswa-page-description">{{ $description }}</p>
    </div>
</header>
