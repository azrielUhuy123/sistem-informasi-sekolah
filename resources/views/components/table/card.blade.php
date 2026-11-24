@props([
    'title',
    'subtitle' => null,
    'density' => 'comfortable',
    'count' => null,
    'countLabel' => 'data',
    'countId' => null,
])

@php
    if (!in_array($density, ['compact', 'comfortable', 'spacious'], true)) {
        throw new \InvalidArgumentException('Unsupported table density: ' . $density);
    }

    $headingId = 'table-card-heading-' . \Illuminate\Support\Str::uuid();
@endphp

<section {{ $attributes->merge([
    'class' => 'card border-0 shadow-sm rounded-4 dt-card',
    'aria-labelledby' => $headingId,
    'data-density' => $density,
]) }}>

    <div class="card-header bg-white border-0 py-4 table-card-header dt-card-header">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div class="table-card-title">

                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">

                    <h4 id="{{ $headingId }}" class="fw-bold mb-0">
                        {{ $title }}
                    </h4>

                    @if (!is_null($count))
                        <span class="dt-card-count">
                            <span class="dt-card-count__value" @if ($countId) id="{{ $countId }}" @endif>{{ $count }}</span>
                            @if (filled($countLabel))
                                <span>{{ $countLabel }}</span>
                            @endif
                        </span>
                    @endif

                    @isset($badge)
                        {{ $badge }}
                    @endisset

                </div>

                @isset($subtitle)
                    <small class="text-muted">
                        {{ $subtitle }}
                    </small>
                @endisset

            </div>

        </div>

    </div>

    @isset($toolbar)
        <div class="dt-card-toolbar">
            {{ $toolbar }}
        </div>
    @endisset

    <div class="card-body overflow-visible dt-card-body">
        {{ $slot }}
    </div>

</section>