@props([
    'tableId' => null,
    'columnToggle' => false,
    'refreshable' => false,
    'refreshUrl' => null,
    'refreshLabel' => 'Refresh',
])

@php
    $hasColumnMenu = isset($columnMenu) && $columnMenu->isNotEmpty();
@endphp

@if ($columnToggle && $hasColumnMenu)
    <div
        class="dropdown"
        data-dt-column-toggle
        data-dt-table-id="{{ $tableId }}"
    >
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
                <legend class="dt-column-menu__title">
                    Tampilkan kolom
                </legend>

                {{ $columnMenu }}

                <div class="dt-column-menu__actions">
                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-dt-columns-reset
                    >
                        Reset
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary btn-sm"
                        data-dt-columns-apply
                    >
                        Terapkan
                    </button>
                </div>
            </fieldset>
        </div>
    </div>
@endif

@if ($refreshable && $refreshUrl)
    <a
        href="{{ $refreshUrl }}"
        class="btn btn-outline-secondary dt-toolbar__button"
    >
        <i class="bi bi-arrow-clockwise" aria-hidden="true"></i>
        {{ $refreshLabel }}
    </a>
@endif
