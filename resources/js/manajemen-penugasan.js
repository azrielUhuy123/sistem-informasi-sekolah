import { Modal } from 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const employeeSelect = document.getElementById('employeeSelect');
    const tableBody = document.getElementById('assignmentTableBody');
    const listView = document.getElementById('assignmentListView');
    const formView = document.getElementById('assignmentFormView');

    if (!employeeSelect || !tableBody || !listView || !formView) return;

    const employeeSearch = document.getElementById('employeeSearch');
    const filterButtons = [...document.querySelectorAll('#employeeFilter [data-filter]')];
    const assignmentCheckboxes = [...document.querySelectorAll('.assignment-checkbox')];
    const records = Array.isArray(window.assignmentInitialRecords)
        ? window.assignmentInitialRecords.map(function (record) { return record; })
        : [];
    const selectedDraftIds = new Set();
    const pageSize = 10;
    let activeFilter = 'all';
    let currentPage = 1;
    let editingRecordId = null;
    let formBaseline = '';
    let pendingAction = null;
    let nextRecordNumber = 1;
    const baseUrl = '/superadmin/manajemen-penugasan';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    async function assignmentRequest(url, method, body) {
        const response = await fetch(url, {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: body ? JSON.stringify(body) : undefined
        });
        const payload = await response.json().catch(function () { return {}; });
        if (!response.ok) {
            throw new Error(payload.message || 'Permintaan tidak dapat diproses.');
        }
        return payload;
    }

    const modal = function (id) {
        const element = document.getElementById(id);
        return element ? Modal.getOrCreateInstance(element) : null;
    };

    const showModal = function (id) {
        const instance = modal(id);
        if (instance) instance.show();
    };

    const hideModal = function (id) {
        const instance = modal(id);
        if (instance) instance.hide();
    };

    function escapeHtml(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (character) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[character];
        });
    }

    function selectedEmployee() {
        const option = employeeSelect.options[employeeSelect.selectedIndex];
        return option && employeeSelect.value ? {
            id: employeeSelect.value,
            name: option.dataset.name,
            nip: option.dataset.nip,
            role: option.dataset.role,
            identity: option.dataset.identity
        } : null;
    }

    function updateEmployeeOptions() {
        const term = employeeSearch.value.trim().toLowerCase();
        [...employeeSelect.options].forEach(function (option) {
            if (!option.value) return;
            const matchesFilter = activeFilter === 'all' || option.dataset.category === activeFilter;
            const matchesSearch = !term ||
                (option.dataset.name || '').toLowerCase().includes(term) ||
                (option.dataset.nip || '').toLowerCase().includes(term);
            option.hidden = !(matchesFilter && matchesSearch);
        });
    }

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            activeFilter = button.dataset.filter;
            filterButtons.forEach(function (item) { item.classList.remove('active'); });
            button.classList.add('active');
            updateEmployeeOptions();
        });
    });
    employeeSearch.addEventListener('input', updateEmployeeOptions);

    function setDetailState(panelId, controlIds, enabled, lockedTextId) {
        const panel = document.getElementById(panelId);
        panel.classList.toggle('is-disabled', !enabled);
        document.getElementById(lockedTextId).classList.toggle('d-none', enabled);
        controlIds.forEach(function (controlId) {
            document.getElementById(controlId).disabled = !enabled;
        });
    }

    function updateMenuCount(assignmentKey) {
        const checkboxes = document.querySelectorAll('.menu-access-checkbox[data-menu-assignment="' + assignmentKey + '"]');
        const selectedCount = [...checkboxes].filter(function (checkbox) { return checkbox.checked; }).length;
        checkboxes.forEach(function (checkbox) {
            checkbox.closest('.menu-access-option').classList.toggle('is-unselected', !checkbox.checked);
        });
        const countElement = document.querySelector('[data-menu-count="' + assignmentKey + '"]');
        if (countElement) countElement.textContent = selectedCount + ' dari ' + checkboxes.length + ' menu dipilih';
    }

    document.querySelectorAll('.menu-access-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateMenuCount(checkbox.dataset.menuAssignment);
        });
    });
    document.querySelectorAll('.menu-select-all').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('.menu-access-checkbox[data-menu-assignment="' + button.dataset.menuAssignment + '"]')
                .forEach(function (checkbox) { checkbox.checked = true; });
            updateMenuCount(button.dataset.menuAssignment);
        });
    });
    document.querySelectorAll('.menu-clear-all').forEach(function (button) {
        button.addEventListener('click', function () {
            document.querySelectorAll('.menu-access-checkbox[data-menu-assignment="' + button.dataset.menuAssignment + '"]')
                .forEach(function (checkbox) { checkbox.checked = false; });
            updateMenuCount(button.dataset.menuAssignment);
        });
    });

    function updateAssignments() {
        const employee = selectedEmployee();
        assignmentCheckboxes.forEach(function (checkbox) {
            checkbox.disabled = !employee;
            checkbox.closest('.assignment-option').classList.toggle('is-locked', !employee);
        });
        document.getElementById('employeeLockedNote').classList.toggle('d-none', !!employee);

        const summary = document.getElementById('selectedEmployeeSummary');
        if (employee) {
            summary.innerHTML =
                '<span class="employee-chip d-inline-block mb-2 text-primary fw-semibold">' + escapeHtml(employee.identity || 'Pegawai') + '</span>' +
                '<div class="fw-semibold">' + escapeHtml(employee.name) + '</div>' +
                (employee.nip ? '<small class="text-muted">NIP ' + escapeHtml(employee.nip) + '</small>' : '');
        } else {
            summary.textContent = 'Belum ada pegawai dipilih.';
        }

        const selected = {};
        assignmentCheckboxes.forEach(function (checkbox) { selected[checkbox.value] = checkbox.checked; });
        setDetailState('waliPanel', ['waliClass'], !!employee && selected.wali, 'waliLockedText');
        setDetailState('guruPanel', ['subjectOptions', 'guruClassOptions'], !!employee && selected.guru, 'guruLockedText');
        setDetailState('ekskulPanel', ['ekskulOptions'], !!employee && selected.ekskul, 'ekskulLockedText');

        document.querySelectorAll('[data-menu-panel]').forEach(function (panel) {
            const assignmentKey = panel.dataset.menuPanel;
            const active = !!employee && !!selected[assignmentKey];
            const menuPanel = panel.querySelector('.assignment-menu-panel');
            panel.classList.toggle('d-none', !selected[assignmentKey]);
            menuPanel.classList.toggle('is-disabled', !active);
            panel.querySelectorAll('.menu-access-checkbox').forEach(function (checkbox) {
                checkbox.disabled = !active;
            });
            panel.querySelectorAll('.menu-select-all, .menu-clear-all').forEach(function (button) {
                button.disabled = !active;
            });
            panel.querySelector('[data-menu-lock]').classList.toggle('d-none', active);
            updateMenuCount(assignmentKey);
        });
        document.getElementById('menuAssignmentEmptyNote').classList.toggle(
            'd-none',
            Object.values(selected).some(Boolean)
        );
    }

    employeeSelect.addEventListener('change', updateAssignments);
    assignmentCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateAssignments);
    });

    function assignmentTitle(key) {
        const checkbox = assignmentCheckboxes.find(function (item) { return item.value === key; });
        return checkbox
            ? checkbox.closest('.assignment-option').querySelector('.assignment-title').textContent.trim()
            : key;
    }

    function selectedValues(selector) {
        return [...document.querySelectorAll(selector + ':checked')].map(function (checkbox) {
            return checkbox.value;
        });
    }

    function readForm() {
        const menus = {};
        document.querySelectorAll('[data-menu-panel]').forEach(function (panel) {
            menus[panel.dataset.menuPanel] = [...panel.querySelectorAll('.menu-access-checkbox:checked')]
                .map(function (checkbox) { return checkbox.value; });
        });
        return {
            employee: selectedEmployee(),
            assignmentKeys: assignmentCheckboxes.filter(function (checkbox) { return checkbox.checked; })
                .map(function (checkbox) { return checkbox.value; }),
            details: {
                waliClass: document.getElementById('waliClass').value,
                subjects: selectedValues('.subject-checkbox'),
                guruClasses: selectedValues('.guru-class-checkbox'),
                extracurriculars: selectedValues('.ekskul-checkbox')
            },
            menus: menus
        };
    }

    function checkboxLabel(selector, value) {
        const checkbox = [...document.querySelectorAll(selector)].find(function (item) {
            return item.value === String(value);
        });
        return checkbox ? checkbox.closest('label').querySelector('.form-check-label').textContent.trim() : '';
    }

    function recordDetails(record) {
        const details = [];
        if (record.assignmentKeys.includes('wali')) {
            const option = [...document.getElementById('waliClass').options].find(function (item) {
                return item.value === record.details.waliClass;
            });
            details.push('Wali Kelas: ' + (option ? option.textContent.trim() : 'Belum dipilih'));
        }
        if (record.assignmentKeys.includes('guru')) {
            const subjects = (record.details.subjects || []).map(function (value) {
                return checkboxLabel('.subject-checkbox', value);
            }).filter(Boolean);
            const classes = (record.details.guruClasses || []).map(function (value) {
                return checkboxLabel('.guru-class-checkbox', value);
            }).filter(Boolean);
            details.push('Guru Bidang Studi: Mapel ' + (subjects.length ? subjects.join(', ') : 'Belum dipilih') +
                '; Kelas ' + (classes.length ? classes.join(', ') : 'Belum dipilih'));
        }
        if (record.assignmentKeys.includes('ekskul')) {
            const activities = (record.details.extracurriculars || []).map(function (value) {
                return checkboxLabel('.ekskul-checkbox', value);
            }).filter(Boolean);
            details.push('Pembina Ekstrakurikuler: ' + (activities.length ? activities.join(', ') : 'Belum dipilih'));
        }
        return details;
    }

    function menuCount(record) {
        return record.assignmentKeys.reduce(function (total, key) {
            return total + ((record.menus[key] || []).length);
        }, 0);
    }

    function statusBadge(status) {
        const classes = { Draft: 'draft', Aktif: 'aktif', Dicabut: 'nonaktif' };
        return '<span class="badge-status ' + (classes[status] || 'draft') + '">' + escapeHtml(status) + '</span>';
    }

    function actionButton(id, action, label, style) {
        return '<button type="button" class="btn btn-sm ' + style + '" data-action="' + action +
            '" data-id="' + escapeHtml(id) + '">' + escapeHtml(label) + '</button>';
    }

    function visibleRecords() {
        const term = document.getElementById('assignmentSearch').value.trim().toLowerCase();
        const status = document.getElementById('assignmentStatusFilter').value;
        return records.filter(function (record) {
            if (status && record.status !== status) return false;
            const searchable = [
                record.employee.name,
                record.employee.nip,
                record.assignmentKeys.map(assignmentTitle).join(' '),
                recordDetails(record).join(' ')
            ].join(' ').toLowerCase();
            return !term || searchable.includes(term);
        });
    }

    function updatePublishControls(pageRecords) {
        const draftIds = pageRecords.filter(function (record) {
            return record.status === 'Draft';
        }).map(function (record) { return record.id; });
        const selectAll = document.getElementById('selectAllDrafts');
        const selectedOnPage = draftIds.filter(function (id) { return selectedDraftIds.has(id); });
        selectAll.disabled = !draftIds.length;
        selectAll.checked = draftIds.length > 0 && selectedOnPage.length === draftIds.length;
        selectAll.indeterminate = selectedOnPage.length > 0 && selectedOnPage.length < draftIds.length;

        const selectedCount = [...selectedDraftIds].filter(function (id) {
            return records.some(function (record) { return record.id === id && record.status === 'Draft'; });
        }).length;
        document.getElementById('selectedDraftCount').textContent = selectedCount;
        document.getElementById('publishSelectedDrafts').disabled = selectedCount === 0;
    }

    function renderPagination(total, start, end, pageCount) {
        const container = document.getElementById('assignmentPagination');
        if (!total) {
            container.innerHTML = '';
            return;
        }
        let buttons = '<button type="button" class="table-pagination-btn' + (currentPage === 1 ? ' disabled' : '') +
            '" data-page="' + (currentPage - 1) + '" aria-label="Halaman sebelumnya"' +
            (currentPage === 1 ? ' disabled' : '') + '><i class="bi bi-chevron-left"></i></button>';
        for (let page = 1; page <= pageCount; page += 1) {
            buttons += '<button type="button" class="table-pagination-btn' + (page === currentPage ? ' active' : '') +
                '" data-page="' + page + '">' + page + '</button>';
        }
        buttons += '<button type="button" class="table-pagination-btn' + (currentPage === pageCount ? ' disabled' : '') +
            '" data-page="' + (currentPage + 1) + '" aria-label="Halaman berikutnya"' +
            (currentPage === pageCount ? ' disabled' : '') + '><i class="bi bi-chevron-right"></i></button>';
        container.innerHTML = '<div class="table-pagination-wrapper">' +
            '<div class="table-pagination-info">Menampilkan <strong>' + start + '-' + end +
            '</strong> dari <strong>' + total + '</strong> data</div>' +
            '<nav class="table-pagination" aria-label="Navigasi halaman">' + buttons + '</nav></div>';
    }

    function renderTable() {
        const filteredRecords = visibleRecords();
        const pageCount = Math.max(1, Math.ceil(filteredRecords.length / pageSize));
        currentPage = Math.min(currentPage, pageCount);
        const startIndex = (currentPage - 1) * pageSize;
        const pageRecords = filteredRecords.slice(startIndex, startIndex + pageSize);
        document.getElementById('assignmentRecordCount').textContent = records.length;
        updatePublishControls(pageRecords);

        if (!records.length) {
            tableBody.innerHTML = '<tr><td colspan="7"><div class="text-center py-5">' +
                '<i class="bi bi-folder2-open display-5 text-secondary"></i>' +
                '<h5 class="fw-bold mt-3">Belum Ada Penugasan</h5>' +
                '<p class="text-muted mb-0">Tambahkan penugasan untuk mulai mengelola tanggung jawab pegawai.</p>' +
                '</div></td></tr>';
        } else if (!filteredRecords.length) {
            tableBody.innerHTML = '<tr><td colspan="7"><div class="text-center py-5">' +
                '<i class="bi bi-search display-5 text-secondary"></i>' +
                '<h5 class="fw-bold mt-3">Data Tidak Ditemukan</h5>' +
                '<p class="text-muted mb-0">Coba ubah kata pencarian atau filter status.</p>' +
                '</div></td></tr>';
        } else {
            tableBody.innerHTML = pageRecords.map(function (record, index) {
                const employee = record.employee || {};
                const assignments = record.assignmentKeys.map(assignmentTitle);
                const details = recordDetails(record);
                let actions = actionButton(record.id, 'detail', 'Detail', 'btn-outline-secondary');
                if (record.status === 'Draft') {
                    actions += ' ' + actionButton(record.id, 'continue', 'Lanjutkan Draft', 'btn-outline-primary');
                    actions += ' ' + actionButton(record.id, 'delete', 'Hapus Draft', 'btn-outline-danger');
                } else {
                    actions += ' ' + actionButton(record.id, 'edit', 'Edit', 'btn-outline-primary');
                    if (record.status === 'Aktif') {
                        actions += ' ' + actionButton(record.id, 'revoke', 'Cabut', 'btn-outline-danger');
                    }
                }
                const selection = record.status === 'Draft'
                    ? '<input type="checkbox" class="form-check-input assignment-table-select draft-select" data-id="' +
                        escapeHtml(record.id) + '" aria-label="Pilih draft ' + escapeHtml(employee.name) + '"' +
                        (selectedDraftIds.has(record.id) ? ' checked' : '') + '>'
                    : '';
                return '<tr>' +
                    '<td>' + (startIndex + index + 1) + '</td>' +
                    '<td class="text-center">' + selection + '</td>' +
                    '<td><div class="fw-semibold">' + escapeHtml(employee.name) + '</div>' +
                    (employee.nip ? '<small class="text-muted">NIP ' + escapeHtml(employee.nip) + '</small>' : '') + '</td>' +
                    '<td>' + (assignments.length ? assignments.map(escapeHtml).join('<br>') : '<span class="text-muted">Belum dipilih</span>') + '</td>' +
                    '<td>' + (details.length ? details.map(escapeHtml).join('<br>') : '<span class="text-muted">—</span>') + '</td>' +
                    '<td>' + statusBadge(record.status) + '</td>' +
                    '<td><div class="d-flex flex-wrap justify-content-end gap-1">' + actions + '</div></td>' +
                    '</tr>';
            }).join('');
        }
        const rangeStart = filteredRecords.length ? startIndex + 1 : 0;
        const rangeEnd = Math.min(startIndex + pageSize, filteredRecords.length);
        renderPagination(filteredRecords.length, rangeStart, rangeEnd, pageCount);
    }

    function showView(view) {
        const showForm = view === 'form';
        listView.classList.toggle('d-none', showForm);
        formView.classList.toggle('d-none', !showForm);
    }

    function resetForm() {
        employeeSelect.value = '';
        employeeSearch.value = '';
        activeFilter = 'all';
        filterButtons.forEach(function (button) {
            button.classList.toggle('active', button.dataset.filter === 'all');
        });
        assignmentCheckboxes.forEach(function (checkbox) { checkbox.checked = false; });
        document.getElementById('waliClass').value = '';
        document.querySelectorAll('.subject-checkbox, .guru-class-checkbox, .ekskul-checkbox')
            .forEach(function (checkbox) { checkbox.checked = false; });
        document.querySelectorAll('.menu-access-checkbox').forEach(function (checkbox) {
            checkbox.checked = false;
        });
        document.getElementById('formValidationError').classList.add('d-none');
        document.getElementById('formModeNotice').classList.add('d-none');
        updateEmployeeOptions();
        updateAssignments();
    }

    function applyRecord(record) {
        resetForm();
        employeeSelect.value = record.employee.id;
        assignmentCheckboxes.forEach(function (checkbox) {
            checkbox.checked = record.assignmentKeys.includes(checkbox.value);
        });
        document.getElementById('waliClass').value = record.details.waliClass || '';
        document.querySelectorAll('.subject-checkbox').forEach(function (checkbox) {
            checkbox.checked = (record.details.subjects || []).includes(checkbox.value);
        });
        document.querySelectorAll('.guru-class-checkbox').forEach(function (checkbox) {
            checkbox.checked = (record.details.guruClasses || []).includes(checkbox.value);
        });
        document.querySelectorAll('.ekskul-checkbox').forEach(function (checkbox) {
            checkbox.checked = (record.details.extracurriculars || []).includes(checkbox.value);
        });
        document.querySelectorAll('[data-menu-panel]').forEach(function (panel) {
            const allowed = record.menus[panel.dataset.menuPanel] || [];
            panel.querySelectorAll('.menu-access-checkbox').forEach(function (checkbox) {
                checkbox.checked = allowed.includes(checkbox.value);
            });
        });
        updateAssignments();
    }

    function currentSnapshot() {
        return JSON.stringify(readForm());
    }

    function openNewForm() {
        editingRecordId = null;
        resetForm();
        document.getElementById('assignmentFormTitle').textContent = 'Tambah Penugasan';
        formBaseline = currentSnapshot();
        showView('form');
    }

    function openEditForm(record, continueDraft) {
        const makeCopy = record.status === 'Aktif' || record.status === 'Dicabut';
        editingRecordId = makeCopy ? null : record.id;
        applyRecord(record);
        document.getElementById('assignmentFormTitle').textContent = continueDraft
            ? 'Lanjutkan Draft'
            : (makeCopy ? 'Edit Penugasan (Salinan)' : 'Edit Penugasan');
        const notice = document.getElementById('formModeNotice');
        if (makeCopy) {
            notice.textContent = 'Data Aktif atau Dicabut tidak akan diubah. Perubahan disimpan sebagai draft baru.';
            notice.classList.remove('d-none');
        } else {
            notice.classList.add('d-none');
        }
        formBaseline = currentSnapshot();
        showView('form');
    }

    function returnToTable() {
        showView('list');
        editingRecordId = null;
        currentPage = 1;
        renderTable();
        resetForm();
    }

    function showFormError(message) {
        const error = document.getElementById('formValidationError');
        error.textContent = message;
        error.classList.remove('d-none');
        error.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function saveDraft() {
        const data = readForm();
        if (!data.employee) {
            showFormError('Pilih nama pegawai terlebih dahulu.');
            return;
        }
        const payload = {
            employee: data.employee.id,
            assignmentKeys: data.assignmentKeys,
            details: data.details,
            menus: data.menus
        };
        const makeCopy = editingRecordId && !records.find(function (item) { return item.id === editingRecordId && item.status === 'Draft'; });
        const request = editingRecordId && !makeCopy
            ? assignmentRequest(baseUrl + '/' + editingRecordId, 'PUT', payload)
            : assignmentRequest(baseUrl, 'POST', payload);
        request.then(function (response) {
            const index = records.findIndex(function (item) { return item.id === response.record.id; });
            if (index >= 0) records[index] = response.record;
            else records.push(response.record);
            selectedDraftIds.delete(response.record.id);
            returnToTable();
        }).catch(function (error) {
            showFormError(error.message);
        });
    }

    function assignmentDetailHtml(record) {
        const details = recordDetails(record);
        const menuGroups = record.assignmentKeys.map(function (key) {
            const menus = record.menus[key] || [];
            return '<li><strong>' + escapeHtml(assignmentTitle(key)) + ':</strong> ' +
                (menus.length ? escapeHtml(menus.join(', ')) : 'Tidak ada menu') + '</li>';
        });
        return '<p><strong>Pegawai:</strong> ' + escapeHtml(record.employee.name) +
            (record.employee.nip ? ' — NIP ' + escapeHtml(record.employee.nip) : '') + '</p>' +
            '<p><strong>Status:</strong> ' + statusBadge(record.status) + '</p>' +
            '<p><strong>Penugasan:</strong> ' + (record.assignmentKeys.length
                ? escapeHtml(record.assignmentKeys.map(assignmentTitle).join(', '))
                : 'Belum dipilih') + '</p>' +
            '<p><strong>Rincian:</strong> ' + (details.length ? escapeHtml(details.join('; ')) : 'Belum ada rincian.') + '</p>' +
            '<p class="mb-1"><strong>Hak akses menu:</strong></p>' +
            (menuGroups.length ? '<ul class="mb-0">' + menuGroups.join('') + '</ul>' : '<p class="mb-0">Belum ada menu.</p>');
    }

    function showActionConfirmation(title, message, buttonLabel, buttonClass, callback) {
        document.getElementById('assignmentActionTitle').textContent = title;
        document.getElementById('assignmentActionMessage').textContent = message;
        const confirmButton = document.getElementById('confirmAssignmentAction');
        confirmButton.textContent = buttonLabel;
        confirmButton.className = 'btn ' + buttonClass;
        pendingAction = callback;
        showModal('assignmentActionModal');
    }

    function publishSelectedDrafts() {
        const selectedRecords = records.filter(function (record) {
            return selectedDraftIds.has(record.id) && record.status === 'Draft';
        });
        if (!selectedRecords.length) return;
        document.getElementById('publishAssignmentsSummary').innerHTML = selectedRecords.map(function (record) {
            const details = recordDetails(record);
            return '<div class="border rounded-3 p-3 mb-2">' +
                '<div class="fw-semibold">' + escapeHtml(record.employee.name) +
                (record.employee.nip ? ' — NIP ' + escapeHtml(record.employee.nip) : '') + '</div>' +
                '<div><strong>Penugasan:</strong> ' +
                (record.assignmentKeys.length ? escapeHtml(record.assignmentKeys.map(assignmentTitle).join(', ')) : 'Belum dipilih') + '</div>' +
                '<div><strong>Rincian:</strong> ' + escapeHtml(details.length ? details.join('; ') : 'Tidak ada rincian tambahan.') + '</div>' +
                '<div><strong>Jumlah menu yang akan diberikan:</strong> ' + menuCount(record) + '</div>' +
                '</div>';
        }).join('');
        showModal('publishAssignmentsModal');
    }

    tableBody.addEventListener('change', function (event) {
        if (!event.target.classList.contains('draft-select')) return;
        if (event.target.checked) selectedDraftIds.add(event.target.dataset.id);
        else selectedDraftIds.delete(event.target.dataset.id);
        renderTable();
    });

    document.getElementById('selectAllDrafts').addEventListener('change', function (event) {
        visibleRecords().slice((currentPage - 1) * pageSize, currentPage * pageSize).forEach(function (record) {
            if (record.status !== 'Draft') return;
            if (event.target.checked) selectedDraftIds.add(record.id);
            else selectedDraftIds.delete(record.id);
        });
        renderTable();
    });

    tableBody.addEventListener('click', function (event) {
        const button = event.target.closest('[data-action]');
        if (!button) return;
        const record = records.find(function (item) { return item.id === button.dataset.id; });
        if (!record) return;

        if (button.dataset.action === 'detail') {
            document.getElementById('assignmentDetailBody').innerHTML = assignmentDetailHtml(record);
            showModal('assignmentDetailModal');
        } else if (button.dataset.action === 'edit' || button.dataset.action === 'continue') {
            openEditForm(record, button.dataset.action === 'continue');
        } else if (button.dataset.action === 'delete') {
            showActionConfirmation('Hapus Draft', 'Draft penugasan ini akan dihapus permanen.', 'Hapus Draft', 'btn-danger', function () {
                assignmentRequest(baseUrl + '/' + record.id, 'DELETE').then(function () {
                    const index = records.findIndex(function (item) { return item.id === record.id; });
                    if (index >= 0 && records[index].status === 'Draft') records.splice(index, 1);
                    selectedDraftIds.delete(record.id);
                    renderTable();
                }).catch(function (error) {
                    showFormError(error.message);
                });
            });
        } else if (button.dataset.action === 'revoke') {
            showActionConfirmation('Cabut Penugasan', 'Penugasan aktif ini akan berubah menjadi Dicabut.', 'Cabut Penugasan', 'btn-danger', function () {
                assignmentRequest(baseUrl + '/' + record.id + '/revoke', 'PATCH').then(function () {
                    const current = records.find(function (item) { return item.id === record.id; });
                    if (current && current.status === 'Aktif') current.status = 'Dicabut';
                    selectedDraftIds.delete(record.id);
                    renderTable();
                }).catch(function (error) {
                    showFormError(error.message);
                });
            });
        }
    });

    document.getElementById('assignmentPagination').addEventListener('click', function (event) {
        const button = event.target.closest('[data-page]');
        if (!button || button.classList.contains('disabled')) return;
        currentPage = Number(button.dataset.page);
        renderTable();
    });

    document.getElementById('assignmentSearch').addEventListener('input', function () {
        currentPage = 1;
        renderTable();
    });
    document.getElementById('assignmentStatusFilter').addEventListener('change', function () {
        currentPage = 1;
        renderTable();
    });
    document.getElementById('addAssignment').addEventListener('click', openNewForm);
    document.getElementById('cancelAssignmentForm').addEventListener('click', function () {
        if (currentSnapshot() !== formBaseline) showModal('unsavedChangesModal');
        else returnToTable();
    });
    document.getElementById('leaveWithoutSaving').addEventListener('click', function () {
        hideModal('unsavedChangesModal');
        returnToTable();
    });
    document.getElementById('saveAssignmentDraft').addEventListener('click', function () {
        document.getElementById('formValidationError').classList.add('d-none');
        saveDraft();
    });
    document.getElementById('publishSelectedDrafts').addEventListener('click', publishSelectedDrafts);
    document.getElementById('confirmPublishAssignments').addEventListener('click', function () {
        const ids = [...selectedDraftIds];
        assignmentRequest(baseUrl + '/publish', 'POST', { ids }).then(function () {
            records.forEach(function (record) {
                if (selectedDraftIds.has(record.id) && record.status === 'Draft') {
                    record.status = 'Aktif';
                    selectedDraftIds.delete(record.id);
                }
            });
            hideModal('publishAssignmentsModal');
            renderTable();
        }).catch(function (error) {
            document.getElementById('publishAssignmentsSummary').innerHTML =
                '<div class="alert alert-danger mb-0">' + escapeHtml(error.message) + '</div>';
        });
    });
    document.getElementById('confirmAssignmentAction').addEventListener('click', function () {
        hideModal('assignmentActionModal');
        const action = pendingAction;
        pendingAction = null;
        if (action) action();
    });

    window.addEventListener('beforeunload', function (event) {
        if (!formView.classList.contains('d-none') && currentSnapshot() !== formBaseline) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    resetForm();
    renderTable();
});
