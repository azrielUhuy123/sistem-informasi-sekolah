<div class="dropdown table-actions">

    <button
        type="button"
        class="btn btn-sm btn-outline-primary"
        data-bs-toggle="dropdown"
        data-bs-display="dynamic"
        aria-expanded="false"
    >
        <i class="bi bi-gear-fill me-1"></i>
        <i class="bi bi-caret-down-fill"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

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