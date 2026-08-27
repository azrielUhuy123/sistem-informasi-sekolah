@props([
    'paginator',
])

@if ($paginator->hasPages())

    <div class="table-pagination-wrapper">

        {{-- Information --}}
        <div class="table-pagination-info">
            Menampilkan
            <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
            –
            <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
            dari
            <strong>{{ $paginator->total() }}</strong>
            data
        </div>


        {{-- Pagination --}}
        <nav
            class="table-pagination"
            aria-label="Navigasi halaman"
        >

            {{-- Previous --}}
            @if ($paginator->onFirstPage())

                <span
                    class="table-pagination-btn disabled"
                    aria-disabled="true"
                >
                    <i class="bi bi-chevron-left"></i>
                </span>

            @else

                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="table-pagination-btn"
                    aria-label="Halaman sebelumnya"
                >
                    <i class="bi bi-chevron-left"></i>
                </a>

            @endif


            {{-- Page Numbers --}}
            @for ($page = 1; $page <= $paginator->lastPage(); $page++)

                @if ($page === $paginator->currentPage())

                    <span
                        class="table-pagination-btn active"
                        aria-current="page"
                    >
                        {{ $page }}
                    </span>

                @else

                    <a
                        href="{{ $paginator->url($page) }}"
                        class="table-pagination-btn"
                    >
                        {{ $page }}
                    </a>

                @endif

            @endfor


            {{-- Next --}}
            @if ($paginator->hasMorePages())

                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="table-pagination-btn"
                    aria-label="Halaman berikutnya"
                >
                    <i class="bi bi-chevron-right"></i>
                </a>

            @else

                <span
                    class="table-pagination-btn disabled"
                    aria-disabled="true"
                >
                    <i class="bi bi-chevron-right"></i>
                </span>

            @endif

        </nav>

    </div>

@endif