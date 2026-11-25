
@props([
    'items' => [],
])

<nav aria-label="Breadcrumb" class="app-breadcrumb">
    <ol class="app-breadcrumb__list">
        @foreach ($items as $index => $item)
            @php
                $isLast = $loop->last;
            @endphp

            <li class="app-breadcrumb__item">
                @if (!$isLast && !empty($item['url']))
                    <a
                        href="{{ $item['url'] }}"
                        class="app-breadcrumb__link"
                    >
                        @if (!empty($item['icon']))
                            <i class="bi {{ $item['icon'] }}"
                               aria-hidden="true"></i>
                        @endif
                        {{ $item['label'] }}
                    </a>
                @else
                    <span
                        class="app-breadcrumb__current"
                        @if ($isLast) aria-current="page" @endif
                    >
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
