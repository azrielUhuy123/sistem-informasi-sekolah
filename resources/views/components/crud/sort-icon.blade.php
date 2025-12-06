@php
$active = request('sort') === $column;
$order = request('order', 'asc');
@endphp

@if(!$active)
<svg class="sort-icon" width="18" height="18" fill="none" stroke="currentColor">
    <path d="M8 9l4-4 4 4"/>
    <path d="M8 15l4 4 4-4"/>
</svg>
@else
    @if($order === 'asc')
    <svg class="sort-icon sort-active" width="18" height="18">
        <path d="M8 14l4-4 4 4"/>
    </svg>
    @else
    <svg class="sort-icon sort-active" width="18" height="18">
        <path d="M8 10l4 4 4-4"/>
    </svg>
    @endif
@endif
