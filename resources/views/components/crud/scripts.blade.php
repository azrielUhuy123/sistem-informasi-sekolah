@isset($routeBulk)
<script>
/* GLOBAL VARIABLES */
document.addEventListener("DOMContentLoaded", function() {
    console.log("SCRIPT READY");

    const csrf = document.querySelector("meta[name='csrf-token']")?.content;
    let selectedIds = new Set();

/* ===========================
    UPDATE BULK BAR
=========================== */
function updateFloatingBar() {
    const bar = document.getElementById("bulkActionBar");
    const count = selectedIds.size;

    document.getElementById("selectedCount").innerText = count;
    bar.style.display = count > 0 ? "flex" : "none";
}

/* ===========================
    SORTING
=========================== */
window.applySort = function (column) {
    const url = new URL(window.location.href);
    const currentSort = url.searchParams.get("sort");
    const currentOrder = url.searchParams.get("order") || "asc";

    if (currentSort === column) {
        url.searchParams.set("order", currentOrder === "asc" ? "desc" : "asc");
    } else {
        url.searchParams.set("sort", column);
        url.searchParams.set("order", "asc");
    }
    window.location.href = url.toString();
}

/* ===========================
    SELECT ALL
=========================== */
document.getElementById("selectAll")?.addEventListener("change", function () {
    const checked = this.checked;

    document.querySelectorAll(".row-checkbox").forEach(cb => {
        cb.checked = checked;

        const id = parseInt(cb.value);
        if (checked) selectedIds.add(id);
        else selectedIds.delete(id);
    });

    updateFloatingBar();
});

/* ===========================
    SINGLE CHECKBOX
=========================== */
document.querySelectorAll(".row-checkbox").forEach(cb => {
    cb.addEventListener("change", function () {
        const id = parseInt(this.value);

        if (this.checked) selectedIds.add(id);
        else selectedIds.delete(id);

        const all = document.querySelectorAll(".row-checkbox").length;
        const checkedCount = document.querySelectorAll(".row-checkbox:checked").length;

        document.getElementById("selectAll").checked = (all === checkedCount);

        updateFloatingBar();
    });
});

/* ===========================
    BULK ACTION
=========================== */

    const BULK_ROUTE = "{{ route($routeBulk) }}";

    async function doBulk(action) {
        if (selectedIds.size === 0) {
            alert("Pilih data terlebih dahulu.");
            return;
        }

        let confirmMsg = {
            delete: `Yakin ingin menghapus ${selectedIds.size} data?`,
            restore: `Pulihkan ${selectedIds.size} data terhapus?`,
            forceDelete: `Hapus permanen ${selectedIds.size} data? Tidak bisa dikembalikan!`
        }[action];

        if (!confirm(confirmMsg)) return;

        try {
            const res = await fetch(BULK_ROUTE, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrf
                },
                body: JSON.stringify({
                    action,
                    ids: [...selectedIds]
                })
            });

            const data = await res.json();
            alert(data.message || "Sukses!");
            location.reload();

        } catch (err) {
            console.error(err);
            alert("Terjadi error: " + err.message);
        }
    }

    /* Bulk Buttons */
    document.getElementById("bulkDelete")?.addEventListener("click", () => doBulk("delete"));
    document.getElementById("bulkRestore")?.addEventListener("click", () => doBulk("restore"));
    document.getElementById("bulkForce")?.addEventListener("click", () => doBulk("forceDelete"));
   



/* ===========================
    COLUMN FILTER
=========================== */
let defaultColumns = {};
document.querySelectorAll(".col-toggle").forEach(chk => {
    defaultColumns[chk.dataset.col] = true;
});

let showColumns = { ...defaultColumns };

document.getElementById("applyColumnFilter")?.addEventListener("click", () => {
    document.querySelectorAll(".col-toggle").forEach(chk => {
        showColumns[chk.dataset.col] = chk.checked;
    });

    for (let col in showColumns) {
        document.querySelectorAll(`[data-col="${col}"]`)
            .forEach(el => el.style.display = showColumns[col] ? "" : "none");
    }
});

document.getElementById("resetColumnFilter")?.addEventListener("click", () => {
    showColumns = { ...defaultColumns };
    document.querySelectorAll(".col-toggle").forEach(chk => chk.checked = true);
    document.querySelectorAll("[data-col]").forEach(el => el.style.display = "");
});

/* ===========================
    PER PAGE
=========================== */
document.getElementById("perPage")?.addEventListener("change", function () {
    const url = new URL(location.href);
    url.searchParams.set("per_page", this.value);
    location.href = url.toString();
});


/* ===========================
    FILTER DELETED
=========================== */
document.getElementById("deletedFilter")?.addEventListener("change", function () {
    const url = new URL(location.href);
    url.searchParams.set("deleted", this.value);
    location.href = url.toString();
});

/* ===========================
        FULL AJAX LIVE SEARCH
   =========================== */

const searchInput = document.getElementById("tableSearch");
const clearBtn = document.getElementById("clearSearch");
const tbody = document.querySelector("table tbody");

let timer = null;

function updateClear() {
    clearBtn.style.display = searchInput.value.trim() ? "inline-block" : "none";
}
updateClear();

/* Ketik → langsung AJAX */
searchInput.addEventListener("input", function () {

    updateClear();
    clearTimeout(timer);

    timer = setTimeout(() => {
        doSearch(this.value.trim());
    }, 200); // 200ms debounce = cepat tapi tidak berat
});

/* Tombol Clear */
clearBtn.addEventListener("click", () => {
    searchInput.value = "";
    updateClear();
    doSearch("");
});

/* AJAX SEARCH */
async function doSearch(keyword) {
    try {
        const url = "{{ route($routeSearch ?? 'tu.kelas.search') }}?q=" + encodeURIComponent(keyword);
        const res = await fetch(url);
        const data = await res.json();

        renderTable(data, keyword);

    } catch (err) {
        console.error("Search error:", err);
    }
}

/* Render hasil ke tabel */
function renderTable(rows) {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = "";

    if (rows.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="99" class="text-center py-4">Tidak ada hasil</td>
            </tr>
        `;
        return;
    }

    rows.forEach(row => {
        tbody.innerHTML += `
            <tr>
                <td data-col="select">
                    <input type="checkbox" class="row-checkbox" value="${row.id}">
                </td>
                <td data-col="nama_kelas">${row.nama_kelas}</td>
                <td data-col="tingkatan">${row.tingkatan}</td>
                <td data-col="tahun_ajaran">${row.tahun_ajaran}</td>
                <td data-col="aksi">
                    <a href="/tu/kelas/${row.id}/edit" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        `;
    });
}


/* Highlight keyword */
function highlight(text, keyword) {
    if (!keyword) return text;

    const regex = new RegExp(`(${keyword})`, "gi");
    return text.replace(regex, `<span class="highlight">$1</span>`);
}


}); // END DOMContentLoaded

</script>
 @endisset