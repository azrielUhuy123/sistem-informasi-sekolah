@props([
    'paginator',
    'showSummary' => false,
    'pageSizeOptions' => null,
    'pageSizeParam' => 'per_page',
    'pageSize' => null,
])

@if ($paginator->hasPages() || $showSummary || (is_array($pageSizeOptions) && count($pageSizeOptions)))

    <div class="table-pagination-wrapper dt-pagination">

        {{-- Information --}}
        @if ($showSummary || $paginator->hasPages())
            <div class="table-pagination-info">
                Menampilkan
                <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
                –
                <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
                dari
                <strong>{{ $paginator->total() }}</strong>
                data
            </div>
        @endif


        {{-- Pagination --}}
        @if ($paginator->hasPages())
            <nav
                class="table-pagination"
                aria-label="Navigasi halaman"
            >

            {{-- Previous --}}
            @if ($paginator->onFirstPage())

                <span
                    class="table-pagination-btn disabled"
                    aria-disabled="true"
                    aria-label="Halaman sebelumnya"
                >
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </span>

            @else

                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="table-pagination-btn"
                    aria-label="Halaman sebelumnya"
                    title="Halaman sebelumnya"
                >
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
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
                        aria-label="Halaman {{ $page }}"
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
                    title="Halaman berikutnya"
                >
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </a>

            @else

                <span
                    class="table-pagination-btn disabled"
                    aria-disabled="true"
                    aria-label="Halaman berikutnya"
                >
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </span>

            @endif

            </nav>
        @endif

        @if (is_array($pageSizeOptions) && count($pageSizeOptions))
            <form
                action="{{ request()->url() }}"
                method="GET"
                class="dt-pagination-size"
                aria-label="Jumlah baris per halaman"
            >
                @foreach (request()->except([$pageSizeParam, 'page']) as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $item)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                        @endforeach
                    @elseif (!is_null($value))
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <label for="{{ $pageSizeParam }}-{{ \Illuminate\Support\Str::slug(request()->url()) }}">Baris</label>
                <select
                    id="{{ $pageSizeParam }}-{{ \Illuminate\Support\Str::slug(request()->url()) }}"
                    name="{{ $pageSizeParam }}"
                    class="form-select form-select-sm"
                    onchange="this.form.submit()"
                >
                    @foreach ($pageSizeOptions as $option)
                        <option value="{{ $option }}" @selected((int) ($pageSize ?? request($pageSizeParam, $paginator->perPage())) === (int) $option)>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif

    </div>

@endif