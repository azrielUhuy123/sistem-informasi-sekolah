
@props([
    'tableId',
    'action' => null,
    'searchable' => false,
    'searchName' => 'search',
    'searchValue' => null,
    'searchLabel' => 'Cari',
    'searchPlaceholder' => 'Cari data',
    'filters' => false,
    'filterOptions' => [],
    'filterName' => 'status',
    'filterValue' => null,
    'filterLabel' => 'Filter',
    'filterMenuTitle' => 'Filter data',
    'columnToggle' => false,
    'refreshable' => false,
    'refreshUrl' => null,
    'refreshLabel' => 'Refresh',
    'exportable' => false,
    'exportUrl' => null,
    'exportLabel' => 'Ekspor',
    'creatable' => false,
    'createUrl' => null,
    'createLabel' => 'Tambah baru',
    'selectable' => false,
])

@php
    $hasColumnMenu = isset($columnMenu) && $columnMenu->isNotEmpty();
    $hasSearchControl = isset($searchControl) && $searchControl->isNotEmpty();

    $hasFilterOptions = is_array($filterOptions) && count($filterOptions) > 0;

    $hasToolbarContent = $searchable
        || $hasSearchControl
        || ($filters && $slot->isNotEmpty())
        || $hasFilterOptions
        || (isset($controls) && $controls->isNotEmpty())
        || (isset($actions) && $actions->isNotEmpty())
        || ($exportable && $exportUrl)
        || ($creatable && $createUrl)
        || ($columnToggle && $hasColumnMenu)
        || ($refreshable && $refreshUrl)
        || ($selectable && isset($bulk) && $bulk->isNotEmpty());

    $appliedFilterValues = is_array($filterValue ?? request($filterName))
        ? array_values(array_filter(
            (array) ($filterValue ?? request($filterName)),
            'filled'
        ))
        : array_values(array_filter(
            [($filterValue ?? request($filterName))],
            'filled'
        ));
@endphp

@if ($hasToolbarContent)
    <div class="dt-toolbar">
        <form
            id="{{ $tableId }}-filter-form"
            @if ($action) action="{{ $action }}" @endif
            method="GET"
            class="dt-toolbar__form"
        >
            {{-- KIRI: SEARCH SAJA --}}
            <div class="dt-toolbar__fields">
                @if ($hasSearchControl)
                    {{ $searchControl }}
                @elseif ($searchable)
                    <div class="dt-toolbar__search">
                        <label
                            class="visually-hidden"
                            for="{{ $tableId }}-search"
                        >
                            {{ $searchLabel }}
                        </label>

                        <i
                            class="bi bi-search dt-toolbar__search-icon"
                            aria-hidden="true"
                        ></i>

                        <input
                            id="{{ $tableId }}-search"
                            type="search"
                            name="{{ $searchName }}"
                            class="form-control dt-toolbar__control"
                            placeholder="{{ $searchPlaceholder }}"
                            value="{{ $searchValue }}"
                            data-dt-global-search
                        >
                    </div>
                @endif
            </div>

            {{-- KANAN: SELURUH FILTER DAN AKSI --}}
            <div class="dt-toolbar__actions">

                {{-- Search state untuk mekanisme Enter --}}
                @if ($searchable && $hasFilterOptions)
                    <input
                        type="hidden"
                        value="{{ $searchValue ?? request($searchName) }}"
                        data-dt-applied-search
                    >
                @endif

                {{-- Filter standar --}}
                @if ($hasFilterOptions)
                    <div class="dt-toolbar__filter">
                        <div
                            class="dropdown"
                            data-dt-filter-dropdown
                        >
                            <button
                                type="button"
                                class="btn btn-outline-secondary dt-toolbar__button dropdown-toggle"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"
                                aria-expanded="false"
                                aria-label="{{ $filterLabel }}"
                            >
                                <i class="bi bi-funnel" aria-hidden="true"></i>
                                {{ $filterLabel }}
                            </button>

                            <div class="dropdown-menu dt-column-menu dt-filter-menu p-3">
                                <fieldset>
                                    <legend class="dt-column-menu__title">
                                        {{ $filterMenuTitle }}
                                    </legend>

                                    @foreach ($filterOptions as $option)
                                        <label class="dt-column-menu__option">
                                            <input
                                                type="checkbox"
                                                value="{{ $option['value'] }}"
                                                data-dt-filter-option
                                            >
                                            <span>{{ $option['label'] }}</span>
                                        </label>
                                    @endforeach

                                    <div class="dt-column-menu__actions">
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary btn-sm"
                                            data-dt-filter-reset
                                        >
                                            Reset
                                        </button>

                                        <button
                                            type="button"
                                            class="btn btn-primary btn-sm"
                                            data-dt-filter-apply
                                        >
                                            Terapkan
                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>

                    {{-- Nilai filter aktif --}}
                    <span
                        data-dt-filter-values="{{ $filterName }}"
                        hidden
                    >
                        @foreach ($appliedFilterValues as $appliedFilterValue)
                            <input
                                type="hidden"
                                name="{{ $filterName }}[]"
                                value="{{ $appliedFilterValue }}"
                            >
                        @endforeach
                    </span>
                @endif

                {{-- Filter atau kontrol tambahan dari slot --}}
                @if ($filters)
                    {{ $slot }}
                @endif

                @isset($controls)
                    {{ $controls }}
                @endisset

                {{-- Aksi tambahan --}}
                @isset($actions)
                    {{ $actions }}
                @endisset

                {{-- Export --}}
                @if ($exportable && $exportUrl)
                    <a
                        href="{{ $exportUrl }}"
                        class="btn btn-outline-secondary dt-toolbar__button"
                    >
                        <i class="bi bi-download" aria-hidden="true"></i>
                        {{ $exportLabel }}
                    </a>
                @endif

                {{-- Tambah --}}
                @if ($creatable && $createUrl)
                    <a
                        href="{{ $createUrl }}"
                        class="btn btn-primary dt-toolbar__button"
                    >
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        {{ $createLabel }}
                    </a>
                @endif

                <x-table.column-controls
                    :table-id="$tableId"
                    :column-toggle="$columnToggle"
                    :refreshable="$refreshable"
                    :refresh-url="$refreshUrl"
                    :refresh-label="$refreshLabel"
                >
                    @isset($columnMenu)
                        <x-slot:columnMenu>
                            {{ $columnMenu }}
                        </x-slot:columnMenu>
                    @endisset
                </x-table.column-controls>

                {{-- Bulk action --}}
                @if ($selectable && isset($bulk))
                    {{ $bulk }}
                @endif

                {{-- Pertahankan parameter GET lainnya --}}
                @if ($hasFilterOptions)
                    @foreach (request()->except([
                        $searchName,
                        $filterName,
                        'page'
                    ]) as $key => $value)
                        @if (is_array($value))
                            @foreach ($value as $item)
                                <input
                                    type="hidden"
                                    name="{{ $key }}[]"
                                    value="{{ $item }}"
                                >
                            @endforeach
                        @elseif (!is_null($value))
                            <input
                                type="hidden"
                                name="{{ $key }}"
                                value="{{ $value }}"
                            >
                        @endif
                    @endforeach
                @endif

            </div>
        </form>
    </div>
@endif
