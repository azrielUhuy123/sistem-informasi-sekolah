@php
    $icons = [
        'success' => 'bi-check-circle-fill',
        'danger'  => 'bi-x-circle-fill',
        'warning' => 'bi-exclamation-triangle-fill',
        'info'    => 'bi-info-circle-fill',
    ];
@endphp

@if(session($key))
<div class="alert alert-{{ $type }} alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4">

    <i class="bi {{ $icons[$type] ?? 'bi-bell-fill' }} me-2"></i>

    {{ session($key) }}

    <button class="btn-close" data-bs-dismiss="alert"></button>

</div>
@endif