@props([
    'label' => 'Aksi lainnya',
    'menuId' => null,
])

@php
    $menuId = $menuId ?: 'table-actions-menu-' . \Illuminate\Support\Str::uuid();
    $triggerId = $menuId . '-trigger';
@endphp

<div {{ $attributes->class(['dropdown table-actions dt-actions']) }}>

    <button
        id="{{ $triggerId }}"
        type="button"
        class="btn btn-sm btn-outline-primary dt-actions__trigger"
        data-bs-toggle="dropdown"
        data-bs-display="dynamic"
        aria-label="{{ $label }}"
        aria-haspopup="true"
        aria-controls="{{ $menuId }}"
        aria-expanded="false"
        title="{{ $label }}"
    >
        <i class="bi bi-gear-fill me-1" aria-hidden="true"></i>
        <i class="bi bi-caret-down-fill" aria-hidden="true"></i>
    </button>

    <ul
        id="{{ $menuId }}"
        class="dropdown-menu dropdown-menu-end shadow-sm border-0 dt-actions__menu"
        aria-labelledby="{{ $triggerId }}"
    >

        {{ $slot }}

    </ul>

</div>
@once
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.table-actions').forEach(function (dropdown) {

        const menu = dropdown.querySelector('.dropdown-menu');

        if (!menu) return;

        dropdown.addEventListener('show.bs.dropdown', function () {

            menu._originalParent = menu.parentNode;
            menu._originalNextSibling = menu.nextSibling;

            document.body.appendChild(menu);

        });

        dropdown.addEventListener('hidden.bs.dropdown', function () {

            if (menu._originalParent) {

                menu._originalParent.insertBefore(
                    menu,
                    menu._originalNextSibling
                );

            }

        });

    });

});
</script>
@endonce