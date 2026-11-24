@props([
    'searchId',
    'searchable' => true,
    'searchLabel' => 'Cari data',
    'searchPlaceholder' => 'Cari data',
    'tableId' => null,
    'columnToggle' => false,
    'refreshable' => false,
    'refreshUrl' => null,
    'refreshLabel' => 'Refresh',
])

@php
    $hasColumnMenu = isset($columnMenu) && $columnMenu->isNotEmpty();
    $hasActions = $slot->isNotEmpty()
        || ($columnToggle && $hasColumnMenu)
        || ($refreshable && $refreshUrl);
@endphp

<div class="dt-toolbar" @if ($searchable) role="search" @else role="group" aria-label="Kontrol tabel" @endif>
    @if ($searchable)
        <div class="dt-toolbar__fields">
            <div class="dt-toolbar__search">
                <label class="visually-hidden" for="{{ $searchId }}">{{ $searchLabel }}</label>
                <i class="bi bi-search dt-toolbar__search-icon" aria-hidden="true"></i>
                <input
                    id="{{ $searchId }}"
                    type="text"
                    class="form-control dt-toolbar__control table-search"
                    placeholder="{{ $searchPlaceholder }}"
                    data-dt-local-search
                    @if ($tableId) data-dt-table-id="{{ $tableId }}" @endif
                >
            </div>
        </div>
    @endif
    @if ($hasActions)
        <div class="dt-toolbar__actions">
            {{ $slot }}
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
        </div>
    @endif
</div>
