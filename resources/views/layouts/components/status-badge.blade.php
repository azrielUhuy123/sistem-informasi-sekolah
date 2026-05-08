@props([
    'type' => 'secondary'
])

<span class="badge bg-{{ $type }}">
    {{ $slot }}
</span>