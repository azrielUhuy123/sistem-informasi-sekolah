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
    $hasToolbarContent = $searchable
        || ($filters && $slot->isNotEmpty())
        || (is_array($filterOptions) && count($filterOptions))
        || (isset($controls) && $controls->isNotEmpty())
        || (isset($actions) && $actions->isNotEmpty())
        || ($exportable && $exportUrl)
        || ($creatable && $createUrl)
        || ($columnToggle && $hasColumnMenu)
        || ($refreshable && $refreshUrl)
        || ($selectable && isset($bulk) && $bulk->isNotEmpty());
@endphp

@if ($hasToolbarContent)
    <div class="dt-toolbar">
    <form
        id="{{ $tableId }}-filter-form"
        @if ($action) action="{{ $action }}" @endif
        method="GET"
        class="dt-toolbar__form"
    >
        <div class="dt-toolbar__fields">
            @if ($searchable)
                <div class="dt-toolbar__search">
                    <label class="visually-hidden" for="{{ $tableId }}-search">{{ $searchLabel }}</label>
                    <i class="bi bi-search dt-toolbar__search-icon" aria-hidden="true"></i>
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

            @if ($filters)
                {{ $slot }}
            @endif

            @isset($controls)
                {{ $controls }}
            @endisset
        </div>

        <div class="dt-toolbar__actions">
            @if (is_array($filterOptions) && count($filterOptions))
                <input
                    type="hidden"
                    name="{{ $filterName }}"
                    value="{{ $filterValue ?? request($filterName) }}"
                    data-dt-filter-value
                >
                @if ($searchable)
                    <input
                        type="hidden"
                        value="{{ $searchValue ?? request($searchName) }}"
                        data-dt-applied-search
                    >
                @endif

                @foreach (request()->except([$searchName, $filterName, 'page']) as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $item)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                        @endforeach
                    @elseif (!is_null($value))
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach

                <div class="dt-toolbar__filter">
                    <div class="dropdown" data-dt-filter-dropdown>
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
                                <legend class="dt-column-menu__title">{{ $filterMenuTitle }}</legend>
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
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dt-filter-reset>Reset</button>
                                    <button type="button" class="btn btn-primary btn-sm" data-dt-filter-apply>Terapkan</button>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            @endif

                @isset($actions)
                    {{ $actions }}
                @endisset

                @if ($exportable && $exportUrl)
                    <a href="{{ $exportUrl }}" class="btn btn-outline-secondary dt-toolbar__button">
                        <i class="bi bi-download" aria-hidden="true"></i>
                        {{ $exportLabel }}
                    </a>
                @endif

                @if ($creatable && $createUrl)
                    <a href="{{ $createUrl }}" class="btn btn-primary dt-toolbar__button">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i>
                        {{ $createLabel }}
                    </a>
                @endif

                @if ($columnToggle && $hasColumnMenu)
                    <div class="dropdown" data-dt-column-toggle data-dt-table-id="{{ $tableId }}">
                        <button
                            type="button"
                            class="btn btn-outline-secondary dt-toolbar__button dropdown-toggle"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            aria-label="Atur kolom tabel"
                        >
                            <i class="bi bi-layout-three-columns" aria-hidden="true"></i>
                            Kolom
                        </button>
                        <div class="dropdown-menu dropdown-menu-end dt-column-menu p-3">
                            <fieldset>
                                <legend class="dt-column-menu__title">Tampilkan kolom</legend>
                                {{ $columnMenu ?? '' }}
                                <div class="dt-column-menu__actions">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dt-columns-reset>Reset</button>
                                    <button type="button" class="btn btn-primary btn-sm" data-dt-columns-apply>Terapkan</button>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                @endif

                @if ($refreshable && $refreshUrl)
                    <a href="{{ $refreshUrl }}" class="btn btn-outline-secondary dt-toolbar__button">
                        <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
                        {{ $refreshLabel }}
                    </a>
                @endif

                @if ($selectable && isset($bulk))
                    {{ $bulk }}
                @endif
            </div>
        </form>
    </div>
@endif
