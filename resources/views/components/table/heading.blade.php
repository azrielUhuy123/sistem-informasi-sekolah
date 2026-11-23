@props([
    'scope' => 'col',
    'sortable' => false,
    'sortKey' => null,
    'sortParam' => 'sort',
    'directionParam' => 'order',
    'align' => null,
    'width' => null,
    'sticky' => false,
    'column' => null,
])

@php
    $style = $width ? 'width:' . $width : null;
    $alignClass = $align === 'right'
        ? 'text-end'
        : ($align === 'center' ? 'text-center' : null);
    $stickyClass = $sticky ? 'dt-column-sticky' : null;
    $currentSort = request($sortParam);
    $currentDirection = request($directionParam, 'asc');
    $isActive = $sortable && $sortKey && $currentSort === $sortKey;
    $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
    if ($column) {
        $attributes = $attributes->merge(['data-dt-column' => $column]);
    }
    $sortUrl = $sortable && $sortKey
        ? request()->fullUrlWithQuery([
            $sortParam => $sortKey,
            $directionParam => $nextDirection,
        ])
        : null;
@endphp

<th
    scope="{{ $scope }}"
    @if ($sortable)
        aria-sort="{{ $isActive ? ($currentDirection === 'desc' ? 'descending' : 'ascending') : 'none' }}"
    @endif
    @if ($style) style="{{ $style }}" @endif
    {{ $attributes->class([$alignClass, $stickyClass]) }}
>
    @if ($sortable && $sortUrl)
        <a
            class="dt-sort-link {{ $isActive ? 'is-active' : '' }}"
            href="{{ $sortUrl }}"
            aria-label="Urutkan {{ trim(strip_tags($slot->toHtml())) }}"
        >
            <span>{{ $slot }}</span>
            <span class="dt-sort-indicator" aria-hidden="true">
                <i class="bi {{ $isActive ? ($currentDirection === 'desc' ? 'bi-arrow-down-short' : 'bi-arrow-up-short') : 'bi-arrow-down-up' }}"></i>
            </span>
        </a>
    @else
        {{ $slot }}
    @endif
</th>
