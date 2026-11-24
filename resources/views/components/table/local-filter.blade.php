@props([
    'id',
    'label' => 'Filter',
    'menuTitle' => 'Filter data',
    'options' => [],
])

<div class="dt-toolbar__filter">
    <div class="dropdown" id="{{ $id }}" data-local-filter-dropdown>
        <button
            type="button"
            class="btn btn-outline-secondary dt-toolbar__button dropdown-toggle"
            data-bs-toggle="dropdown"
            data-bs-auto-close="outside"
            aria-expanded="false"
            aria-label="{{ $label }}"
        >
            <i class="bi bi-funnel" aria-hidden="true"></i>
            {{ $label }}
        </button>
        <div class="dropdown-menu dropdown-menu-end dt-column-menu dt-filter-menu p-3">
            <fieldset>
                <legend class="dt-column-menu__title">{{ $menuTitle }}</legend>
                @foreach ($options as $option)
                    <label class="dt-column-menu__option">
                        <input
                            type="checkbox"
                            value="{{ $option['value'] }}"
                            data-local-filter-option
                        >
                        <span>{{ $option['label'] }}</span>
                    </label>
                @endforeach
                <div class="dt-column-menu__actions">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-local-filter-reset>Reset</button>
                    <button type="button" class="btn btn-primary btn-sm" data-local-filter-apply>Terapkan</button>
                </div>
            </fieldset>
        </div>
    </div>
</div>
