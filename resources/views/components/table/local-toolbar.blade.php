@props([
    'searchId',
    'searchLabel' => 'Cari data',
    'searchPlaceholder' => 'Cari data',
])

<div class="dt-toolbar" role="search">
    <div class="dt-toolbar__fields">
        <div class="dt-toolbar__search">
            <label class="visually-hidden" for="{{ $searchId }}">{{ $searchLabel }}</label>
            <i class="bi bi-search dt-toolbar__search-icon" aria-hidden="true"></i>
            <input
                id="{{ $searchId }}"
                type="text"
                class="form-control dt-toolbar__control table-search"
                placeholder="{{ $searchPlaceholder }}"
            >
        </div>
    </div>
    @if ($slot->isNotEmpty())
        <div class="dt-toolbar__actions">
            {{ $slot }}
        </div>
    @endif
</div>
