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

    $activeCriteria = collect();
    foreach ($validCriteria as $criterion) {
        $values = is_array($criterion['value'] ?? null)
            ? array_values(array_filter($criterion['value'], 'filled'))
            : (filled($criterion['value'] ?? null) ? [$criterion['value']] : []);
        foreach ($values as $value) {
            $chip = $criterion;
            $chip['value'] = $value;
            $chip['removeValue'] = $value;
            $activeCriteria->push($chip);
        }
    }

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
                $requestValues = request()->query($criterion['name']);
                if (is_array($requestValues)) {
                    $query[$criterion['name']] = array_values(array_filter(
                        $requestValues,
                        fn ($value) => (string) $value !== (string) $criterion['removeValue']
                    ));
                    if ($query[$criterion['name']] === []) {
                        unset($query[$criterion['name']]);
                    }
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
