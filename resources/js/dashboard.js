document.addEventListener("DOMContentLoaded", () => {

    initDarkMode();
    initGlobalSearch();
    initActiveMenus();

});

/* =========================
   DARK MODE
========================= */
function initDarkMode() {

    const darkToggle = document.getElementById("darkToggle");

    if (!darkToggle) return;

    if (localStorage.getItem("darkMode") === "enabled") {

        document.body.classList.add("dark-mode");
        darkToggle.innerText = "☀️";

    }

    darkToggle.addEventListener("click", () => {

        document.body.classList.toggle("dark-mode");

        if (document.body.classList.contains("dark-mode")) {

            localStorage.setItem("darkMode", "enabled");
            darkToggle.innerText = "☀️";

        } else {

            localStorage.setItem("darkMode", "disabled");
            darkToggle.innerText = "🌙";

        }

    });

}

/* =========================
   GLOBAL SEARCH
========================= */
function initGlobalSearch() {

    const searchInput = document.getElementById("globalSearch");
    const searchResults = document.getElementById("searchResults");

    if (!searchInput || !searchResults) return;

    searchInput.addEventListener("keyup", function () {

        let query = this.value.trim();

        if (query.length < 2) {

            searchResults.style.display = "none";
            return;

        }

        fetch(`/tu/search-ajax?q=${query}`)
            .then(res => res.json())
            .then(data => {

                let html = "";

                if (
                    data.siswa.length === 0 &&
                    data.kelas.length === 0
                ) {

                    html = `
                        <div class="p-2 text-muted">
                            Tidak ada hasil...
                        </div>
                    `;

                }

                /* SISWA */
                if (data.siswa.length > 0) {

                    html += `
                        <div class="fw-bold px-2 pt-2">
                            Siswa
                        </div>
                    `;

                    data.siswa.forEach(s => {

                        html += `
                            <div
                                class="p-2 search-item"
                                onclick="window.location='/tu/siswa/${s.id}'"
                            >

                                <i class="bi bi-person me-2"></i>

                                ${s.nama}

                                <div class="small text-muted">
                                    ${s.nis}
                                </div>

                            </div>
                        `;

                    });

                }

                /* KELAS */
                if (data.kelas.length > 0) {

                    html += `
                        <div class="fw-bold px-2 pt-2">
                            Kelas
                        </div>
                    `;

                    data.kelas.forEach(k => {

                        html += `
                            <div
                                class="p-2 search-item"
                                onclick="window.location='/tu/kelas/${k.id}'"
                            >

                                <i class="bi bi-people me-2"></i>

                                ${k.nama_kelas}

                            </div>
                        `;

                    });

                }

                searchResults.innerHTML = html;
                searchResults.style.display = "block";

            });

    });

    document.addEventListener("click", (e) => {

        if (e.target.closest('.sidebar')) return;

        if (
            !searchInput.contains(e.target) &&
            !searchResults.contains(e.target)
        ) {

            searchResults.style.display = "none";

        }

    });

}

/* =========================
   AUTO OPEN ACTIVE MENU
========================= */
function initActiveMenus() {

    const submenus = document.querySelectorAll(".submenu");

    submenus.forEach(menu => {

        const menuId = menu.id;

        const arrow = menu
            .previousElementSibling
            ?.querySelector(".dropdown-arrow");

        /* AUTO OPEN JIKA ADA ACTIVE */
        if (menu.querySelector(".menu-item.active")) {

            menu.classList.add("show");

            if (arrow) {
                arrow.classList.add("rotate");
            }

        }

        /* RESTORE DARI LOCAL STORAGE */
        if (localStorage.getItem(menuId) === "open") {

            menu.classList.add("show");

            if (arrow) {
                arrow.classList.add("rotate");
            }

        }

    });

}
/* =========================
   TOAST
========================= */
window.showToast = function (
    message,
    type = 'success'
) {

    const toastEl = document.getElementById('appToast');
    const toastBody = toastEl.querySelector('.toast-body');

    toastEl.classList.remove(
        'bg-success',
        'bg-danger',
        'text-white'
    );

    if (type === 'success') {

        toastEl.classList.add(
            'bg-success',
            'text-white'
        );

    }

    if (type === 'error') {

        toastEl.classList.add(
            'bg-danger',
            'text-white'
        );

    }

    toastBody.innerText = message;

    const toast = new bootstrap.Toast(
        toastEl,
        {
            delay: 3000
        }
    );

    toast.show();

}

/* =========================
   SIDEBAR TOGGLE
========================= */
window.toggleMenu = function (
    menuId,
    arrowId
) {

    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);

    if (menu.classList.contains("show")) {

        menu.classList.remove("show");
        arrow.classList.remove("rotate");

        localStorage.removeItem(menuId);

    } else {

        menu.classList.add("show");
        arrow.classList.add("rotate");

        localStorage.setItem(menuId, "open");

    }

}
const selectedCount =
    document.getElementById('selectedCount');

if (selectedCount) {

    const checkboxes =
        document.querySelectorAll('.kelas-checkbox');

    function updateCounter()
    {
        const checked =
            document.querySelectorAll(
                '.kelas-checkbox:checked'
            ).length;

        selectedCount.innerText =
            checked + ' kelas dipilih';
    }

    checkboxes.forEach(cb => {

        cb.addEventListener(
            'change',
            updateCounter
        );

    });

}