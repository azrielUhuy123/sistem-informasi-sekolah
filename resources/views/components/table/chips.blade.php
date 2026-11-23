@props([
    'criteria' => [],
    'clearAllLabel' => 'Hapus semua',
    'clearable' => true,
    'clearAllValues' => [],
])

@php
    $validCriteria = collect($criteria)->filter(
        fn ($criterion) => is_array($criterion)
            && isset($criterion['name'], $criterion['label'])
    )->values();

    $activeCriteria = $validCriteria->filter(
        fn ($criterion) => is_scalar($criterion['value'] ?? null)
            && filled($criterion['value'])
    )->values();

    $clearParameters = $validCriteria->pluck('name')->unique()->all();
    $clearAllQuery = request()->query();
    foreach ($clearParameters as $parameter) {
        unset($clearAllQuery[$parameter]);
    }
    foreach ($clearAllValues as $parameter => $value) {
        if (in_array($parameter, $clearParameters, true)) {
            $clearAllQuery[$parameter] = $value;
        }
    }
    unset($clearAllQuery['page']);

    $clearAllUrl = request()->url()
        . ($clearAllQuery ? '?' . http_build_query($clearAllQuery) : '');
@endphp

@if ($activeCriteria->isNotEmpty())
    <div class="dt-filter-chips" role="group" aria-label="Filter aktif">
        @foreach ($activeCriteria as $criterion)
            @php
                $query = request()->query();
                if (array_key_exists('removeValue', $criterion)) {
                    $query[$criterion['name']] = $criterion['removeValue'];
                } else {
                    unset($query[$criterion['name']]);
                }
                unset($query['page']);
                $removeUrl = request()->url()
                    . ($query ? '?' . http_build_query($query) : '');
                $displayValue = $criterion['display'] ?? $criterion['value'];
            @endphp
            <span class="dt-filter-chip">
                <span>{{ $criterion['label'] }}: {{ $displayValue }}</span>
                <a
                    href="{{ $removeUrl }}"
                    aria-label="Hapus {{ $criterion['label'] }}"
                    title="Hapus {{ $criterion['label'] }}"
                >
                    <i class="bi bi-x" aria-hidden="true"></i>
                </a>
            </span>
        @endforeach

        @if ($clearable)
            <a href="{{ $clearAllUrl }}" class="dt-filter-chips__clear">{{ $clearAllLabel }}</a>
        @endif
    </div>
@endif
