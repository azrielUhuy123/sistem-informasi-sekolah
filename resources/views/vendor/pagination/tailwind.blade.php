@if ($paginator->hasPages())
    <div class="simple-pagination">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="disabled-page">«</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link">«</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="disabled-page">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="active-page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link">»</a>
        @else
            <span class="disabled-page">»</span>
        @endif

    </div>
@endif
