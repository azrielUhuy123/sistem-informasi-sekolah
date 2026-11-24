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
    const valuesContainer = form?.querySelector('[data-dt-filter-values]');
    const selectedValues = valuesContainer
        ? [...valuesContainer.querySelectorAll('input[type="hidden"]')].map((input) => input.value)
        : [];

    dropdown.querySelectorAll('[data-dt-filter-option]').forEach((checkbox) => {
        checkbox.checked = selectedValues.includes(checkbox.value);
    });
}

function initLocalSearch(input) {
    input.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter') return;

        event.preventDefault();
        const keyword = input.value.trim().toLowerCase();
        const card = input.closest('.dt-card');
        const table = card?.querySelector('table');
        if (!table) return;

        const tbody = table.tBodies[0];
        if (!tbody) return;

        let matched = 0;
        [...tbody.querySelectorAll('tr')].forEach((row) => {
            if (row.classList.contains('dt-empty-row')) return;
            const matches = keyword === '' || row.textContent.toLowerCase().includes(keyword);
            row.style.display = matches ? '' : 'none';
            if (matches) matched += 1;
        });

        let chips = card.querySelector(':scope > .dt-filter-chips[data-dt-local-chips]');
        if (!chips) {
            chips = document.createElement('div');
            chips.className = 'dt-filter-chips';
            chips.setAttribute('role', 'group');
            chips.setAttribute('aria-label', 'Filter aktif');
            chips.dataset.dtLocalChips = '';
            const toolbarSlot = card.querySelector('.dt-card-toolbar');
            toolbarSlot?.insertAdjacentElement('afterend', chips);
        }

        chips.replaceChildren();
        if (keyword !== '') {
            const chip = document.createElement('span');
            chip.className = 'dt-filter-chip';
            const chipLabel = document.createElement('span');
            chipLabel.textContent = `Search: ${input.value.trim()}`;
            chip.appendChild(chipLabel);
            const remove = document.createElement('a');
            remove.href = '#';
            remove.setAttribute('aria-label', 'Hapus Search');
            remove.innerHTML = '<i class="bi bi-x" aria-hidden="true"></i>';
            remove.addEventListener('click', (removeEvent) => {
                removeEvent.preventDefault();
                input.value = '';
                input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
            });
            chip.appendChild(remove);
            chips.appendChild(chip);

            const clearAll = document.createElement('a');
            clearAll.href = '#';
            clearAll.className = 'dt-filter-chips__clear';
            clearAll.textContent = 'Hapus semua';
            clearAll.addEventListener('click', (clearEvent) => {
                clearEvent.preventDefault();
                input.value = '';
                input.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', bubbles: true }));
            });
            chips.appendChild(clearAll);
        }
    });
}

function initFilterDropdown(dropdown) {
    const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
    const form = dropdown.closest('form');
    const valuesContainer = form?.querySelector('[data-dt-filter-values]');
    if (!toggle || !form || !valuesContainer) return;

    dropdown.addEventListener('show.bs.dropdown', () => syncFilterOptions(dropdown));
    dropdown.addEventListener('hidden.bs.dropdown', () => syncFilterOptions(dropdown));

    dropdown.querySelector('[data-dt-filter-reset]')?.addEventListener('click', () => {
        dropdown.querySelectorAll('[data-dt-filter-option]').forEach((checkbox) => {
            checkbox.checked = false;
        });
    });

    dropdown.querySelector('[data-dt-filter-apply]')?.addEventListener('click', () => {
        const selected = [...dropdown.querySelectorAll('[data-dt-filter-option]:checked')];
        valuesContainer.querySelectorAll('input').forEach((input) => input.remove());
        const filterName = valuesContainer.dataset.dtFilterValues;
        selected.forEach((checkbox) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${filterName}[]`;
            input.value = checkbox.value;
            valuesContainer.appendChild(input);
        });
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
    document.querySelectorAll('[data-dt-local-search]').forEach(initLocalSearch);
});
