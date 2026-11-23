import { Dropdown } from 'bootstrap';

function storageKey(tableId) {
    return `sinergi-mj:table-columns:${tableId}`;
}

function getColumnKeys(toggle) {
    return [...new Set(
        [...toggle.querySelectorAll('[data-dt-column]')]
            .map((checkbox) => checkbox.dataset.dtColumn)
            .filter(Boolean)
    )];
}

function applyColumnState(tableId, state) {
    const table = document.getElementById(tableId);
    if (!table) return;

    table.querySelectorAll('col[data-dt-column]').forEach((columnElement) => {
        const column = columnElement.dataset.dtColumn;
        columnElement.style.display = state[column] === false ? 'none' : '';
    });

    table.querySelectorAll('[data-dt-column]').forEach((element) => {
        const column = element.dataset.dtColumn;
        element.style.display = state[column] === false ? 'none' : '';
    });

    document.querySelectorAll('[data-dt-column-toggle]').forEach((toggle) => {
        if (toggle.dataset.dtTableId !== tableId) return;
        toggle.querySelectorAll('[data-dt-column]').forEach((checkbox) => {
            checkbox.checked = state[checkbox.dataset.dtColumn] !== false;
        });
    });
}

function writeColumnState(tableId, state) {
    try {
        localStorage.setItem(storageKey(tableId), JSON.stringify(state));
    } catch {
        console.warn(`Table column preferences could not be saved for "${tableId}".`);
    }
}

function readColumnState(tableId, columnKeys) {
    let stored;
    try {
        stored = JSON.parse(localStorage.getItem(storageKey(tableId)) || '{}');
    } catch {
        console.warn(`Table column preferences could not be read for "${tableId}".`);
        return {};
    }

    if (!stored || typeof stored !== 'object' || Array.isArray(stored)) {
        console.warn(`Invalid table column preferences were ignored for "${tableId}".`);
        return {};
    }

    const state = {};
    columnKeys.forEach((key) => {
        if (typeof stored[key] === 'boolean') state[key] = stored[key];
    });

    if (Object.keys(stored).length !== Object.keys(state).length) {
        writeColumnState(tableId, state);
    }

    return state;
}

function initColumnToggle(toggle) {
    const tableId = toggle.dataset.dtTableId;
    const dropdownToggle = toggle.querySelector('[data-bs-toggle="dropdown"]');
    if (!tableId || !dropdownToggle) return;

    const columnKeys = getColumnKeys(toggle);
    const state = readColumnState(tableId, columnKeys);
    applyColumnState(tableId, state);

    toggle.querySelector('[data-dt-columns-apply]')?.addEventListener('click', () => {
        const nextState = {};

        columnKeys.forEach((key) => {
            const checkbox = [...toggle.querySelectorAll('[data-dt-column]')]
                .find((item) => item.dataset.dtColumn === key);
            nextState[key] = checkbox ? checkbox.checked : true;
        });

        writeColumnState(tableId, nextState);
        applyColumnState(tableId, nextState);
        Dropdown.getOrCreateInstance(dropdownToggle).hide();
    });

    toggle.querySelector('[data-dt-columns-reset]')?.addEventListener('click', () => {
        try {
            localStorage.removeItem(storageKey(tableId));
        } catch {
            console.warn(`Table column preferences could not be reset for "${tableId}".`);
        }
        const resetState = {};
        toggle.querySelectorAll('[data-dt-column]').forEach((checkbox) => {
            checkbox.checked = true;
        });
        applyColumnState(tableId, resetState);
        Dropdown.getOrCreateInstance(dropdownToggle).hide();
    });
}

function syncFilterOptions(dropdown) {
    const form = dropdown.closest('form');
    const filterValue = form?.querySelector('[data-dt-filter-value]')?.value || '';

    dropdown.querySelectorAll('[data-dt-filter-option]').forEach((checkbox) => {
        checkbox.checked = checkbox.value === filterValue;
    });
}

function initFilterDropdown(dropdown) {
    const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
    const form = dropdown.closest('form');
    const filterValue = form?.querySelector('[data-dt-filter-value]');
    if (!toggle || !form || !filterValue) return;

    dropdown.addEventListener('show.bs.dropdown', () => syncFilterOptions(dropdown));
    dropdown.addEventListener('hidden.bs.dropdown', () => syncFilterOptions(dropdown));

    dropdown.querySelector('[data-dt-filter-reset]')?.addEventListener('click', () => {
        dropdown.querySelectorAll('[data-dt-filter-option]').forEach((checkbox) => {
            checkbox.checked = false;
        });
    });

    dropdown.querySelector('[data-dt-filter-apply]')?.addEventListener('click', () => {
        const selected = [...dropdown.querySelectorAll('[data-dt-filter-option]:checked')];
        filterValue.value = selected.length === 1 ? selected[0].value : '';
        const searchInput = form.querySelector('[data-dt-global-search]');
        const appliedSearch = form.querySelector('[data-dt-applied-search]');
        if (searchInput && appliedSearch) {
            searchInput.disabled = true;
            if (appliedSearch.value !== '') appliedSearch.name = searchInput.name;
        }
        Dropdown.getOrCreateInstance(toggle).hide();
        form.requestSubmit();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-dt-column-toggle]').forEach(initColumnToggle);
    document.querySelectorAll('[data-dt-filter-dropdown]').forEach(initFilterDropdown);

    document.querySelectorAll('[data-dt-global-search]').forEach((input) => {
        input.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') return;

            event.preventDefault();
            input.form?.requestSubmit();
        });
    });
});
