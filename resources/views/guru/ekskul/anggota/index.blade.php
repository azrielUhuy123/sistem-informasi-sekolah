@extends('layouts.app')

@section('title', 'Anggota Ekskul')

@section('content')

@php
    $totalSiswa = method_exists($siswa, 'total') ? $siswa->total() : $siswa->count();
    $sudahBergabung = count($anggotaIds);
    $belumBergabung = max($totalSiswa - $sudahBergabung, 0);
@endphp

<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Pendaftaran Anggota Ekstrakurikuler</h3>
                    <p class="mb-0 opacity-75">Pilih siswa yang akan menjadi anggota ekstrakurikuler kemudian daftarkan secara bersamaan menggunakan sistem seleksi anggota.</p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    <x-table.card
        title="Daftar Siswa"
        subtitle="Pilih siswa yang akan menjadi anggota ekstrakurikuler."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $totalSiswa }} <span class="text-badge">Siswa</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <form method="GET" class="siswa-filter-form d-flex align-items-center gap-2 ms-auto" action="{{ url()->current() }}">
                <div class="position-relative flex-grow-1" style="min-width: 220px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input
                        type="text"
                        id="searchInput"
                        name="search"
                        class="form-control table-search ps-5"
                        placeholder="Cari Nama atau NIS..."
                        value="{{ $search }}"
                    >
                </div>
                <button type="submit" class="btn btn-primary rounded-3">
                    <i class="bi bi-search me-1"></i>
                    Cari
                </button>
            </form>
        </x-slot:actions>

        <div class="bulk-action-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-pill">                    
                    <span id="selectedCountBadge">0</span>
                    siswa dipilih
                </span>
            </div>

            <form id="memberForm" action="{{ route('guru.ekskul.anggota.store') }}" method="POST" class="d-inline">
                @csrf
                <div id="selectedIdsContainer"></div>

                <button id="submitSelectedBtn" type="submit" class="btn btn-primary btn-sm rounded-3" disabled>                    
                    <span id="submitButtonText">Tambahkan Anggota</span>
                </button>
            </form>
        </div>

        <div id="previewBox" class="mb-4 d-none preview-card p-4 rounded-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-1">Siswa Yang Akan Ditambahkan</h6>
                    <p class="text-muted small mb-0">Daftar siswa yang telah dipilih.</p>
                </div>
                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                    <i class="bi bi-list-check me-2"></i>
                    <span id="previewCount">0</span>
                </span>
            </div>
            <div id="previewList" class="d-flex flex-wrap gap-2"></div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="60">
                            <input type="checkbox" class="form-check-input modern-checkbox" id="checkAll" aria-label="Pilih semua siswa di halaman ini">
                        </th>
                        <th width="70">No</th>
                        <th>Identitas</th>
                        <th width="180">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($siswa->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-people display-4 text-secondary"></i>
                                    <h5 class="fw-bold mt-3">
                                        @if(!empty($search))
                                            Siswa Tidak Ditemukan
                                        @else
                                            Belum Ada Data Siswa
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-0">
                                        @if(!empty($search))
                                            Tidak ada siswa yang sesuai dengan pencarian "{{ $search }}".
                                        @else
                                            Belum terdapat siswa yang dapat ditambahkan ke ekstrakurikuler.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($siswa as $s)
                            @php
                                $sudahJoin = in_array($s->id, $anggotaIds);
                                $namaSiswa = $s->nama ?? '';
                                $inisial = strtoupper(substr(trim($namaSiswa), 0, 1));
                            @endphp

                            <tr class="{{ $sudahJoin ? 'row-joined' : '' }}">
                                <td>
                                    @if(!$sudahJoin)
                                        <input
                                            type="checkbox"
                                            name="siswa_id[]"
                                            value="{{ $s->id }}"
                                            class="form-check-input checkSiswa modern-checkbox"
                                            data-id="{{ $s->id }}"
                                            data-nama="{{ $s->nama }}"
                                            aria-label="Pilih {{ $s->nama }}"
                                        >
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-circle">
                                        {{ $siswa->firstItem() + $loop->index }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>

                                        <div>
                                            <div class="fw-semibold">{{ $s->nama }}</div>
                                            <small class="text-muted">{{ $s->nis }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($sudahJoin)
                                        <span class="badge-status aktif">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            Sudah Bergabung
                                        </span>
                                    @else
                                        <span class="badge-status pending">
                                            <i class="bi bi-hourglass-split me-2"></i>
                                            Belum Bergabung
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <x-table.pagination :paginator="$siswa" />
    </x-table.card>
</div>

<style>
    .siswa-filter-form {
        width: 100%;
        max-width: 420px;
    }

    .preview-card {
        background: rgba(248, 250, 252, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.18);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
    }

    .member-card {
        background: #f8fbff;
        border: 1px solid #e9f2ff;
        border-radius: 18px;
        padding: 0.75rem 1rem;
    }

    .member-remove {
        border: 0;
        background: transparent;
        color: #64748b;
        font-size: 1.1rem;
        line-height: 1;
        padding: 0;
        margin-left: 0.35rem;
    }

    .row-joined {
        background: #f8fafc;
        opacity: 0.9;
    }

    .modern-checkbox {
        width: 1.1rem;
        height: 1.1rem;
        border-radius: 0.35rem;
    }

    .table-search {
        min-width: 220px;
    }

    @media (max-width: 767.98px) {
        .siswa-filter-form {
            max-width: 100%;
            width: 100%;
        }

        .bulk-action-bar {
            align-items: flex-start;
        }

        .table-search {
            min-width: 0;
            width: 100%;
        }
    }
</style>

<script>
    (() => {
        const STORAGE_KEY = @json('guru_ekskul_anggota_selection_' . ($ekskul->id ?? 0));
        const NAMES_KEY = @json('guru_ekskul_anggota_selection_names_' . ($ekskul->id ?? 0));

        const checkAll = document.getElementById('checkAll');
        const checkboxes = Array.from(document.querySelectorAll('.checkSiswa'));
        const previewBox = document.getElementById('previewBox');
        const previewList = document.getElementById('previewList');
        const selectedCountBadge = document.getElementById('selectedCountBadge');
        const previewCount = document.getElementById('previewCount');
        const submitSelectedBtn = document.getElementById('submitSelectedBtn');
        const submitButtonText = document.getElementById('submitButtonText');
        const selectedIdsContainer = document.getElementById('selectedIdsContainer');

        const selectedIds = new Set(readSelectionIds());
        const selectedNames = new Map(readSelectionNames());

        function readSelectionIds() {
            try {
                const raw = sessionStorage.getItem(STORAGE_KEY);
                return raw ? JSON.parse(raw) : [];
            } catch (error) {
                return [];
            }
        }

        function readSelectionNames() {
            try {
                const raw = sessionStorage.getItem(NAMES_KEY);
                const parsed = raw ? JSON.parse(raw) : {};
                return Object.entries(parsed).map(([id, name]) => [String(id), String(name)]);
            } catch (error) {
                return [];
            }
        }

        function persistSelection() {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify([...selectedIds]));
            const names = Object.fromEntries(selectedNames.entries());
            sessionStorage.setItem(NAMES_KEY, JSON.stringify(names));
        }

        function clearSelection() {
            selectedIds.clear();
            selectedNames.clear();
            sessionStorage.removeItem(STORAGE_KEY);
            sessionStorage.removeItem(NAMES_KEY);
            renderSelection();
        }

        function buildHiddenInputs() {
            if (!selectedIdsContainer) {
                return;
            }

            selectedIdsContainer.innerHTML = '';

            [...selectedIds].forEach((id) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'siswa_id[]';
                input.value = id;
                selectedIdsContainer.appendChild(input);
            });
        }

        function syncCheckboxes() {
            checkboxes.forEach((checkbox) => {
                const id = String(checkbox.value);
                checkbox.checked = selectedIds.has(id);
            });

            if (checkAll) {
                const eligible = checkboxes.filter((checkbox) => !checkbox.disabled);
                if (eligible.length) {
                    checkAll.checked = eligible.every((checkbox) => selectedIds.has(String(checkbox.value)));
                } else {
                    checkAll.checked = false;
                }
            }
        }

        function renderPreview() {
            if (!previewList || !previewBox) {
                return;
            }

            previewList.innerHTML = '';

            const selected = [...selectedIds].map((id) => ({
                id: String(id),
                name: selectedNames.get(String(id)) || 'Siswa'
            }));

            const total = selected.length;

            if (selectedCountBadge) {
                selectedCountBadge.textContent = total;
            }

            if (previewCount) {
                previewCount.textContent = total;
            }

            if (submitButtonText) {
                submitButtonText.textContent = total > 0 ? `Tambahkan ${total} Anggota` : 'Tambahkan Anggota';
            }

            if (submitSelectedBtn) {
                submitSelectedBtn.disabled = total === 0;
            }

            if (previewBox) {
                previewBox.classList.toggle('d-none', total === 0);
            }

            if (total === 0) {
                buildHiddenInputs();
                persistSelection();
                syncCheckboxes();
                return;
            }

            selected.forEach((item) => {
                const initial = (item.name || 'S').trim().charAt(0).toUpperCase() || 'S';

                const card = document.createElement('div');
                card.className = 'member-card d-flex align-items-center gap-2';
                card.innerHTML = `
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px; min-width: 34px; font-size: 0.9rem;">
                        ${initial}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <div class="fw-semibold text-dark">${item.name}</div>
                            <small class="text-muted">Dipilih</small>
                        </div>
                        <button type="button" class="member-remove" data-remove-id="${item.id}" aria-label="Hapus ${item.name} dari pilihan">×</button>
                    </div>
                `;

                previewList.appendChild(card);
            });

            buildHiddenInputs();
            persistSelection();
            syncCheckboxes();
        }

        function renderSelection() {
            renderPreview();
        }

        function registerCheckboxSelection() {
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function () {
                    const id = String(this.value);
                    const name = this.dataset.nama || 'Siswa';

                    if (this.checked) {
                        selectedIds.add(id);
                        selectedNames.set(id, name);
                    } else {
                        selectedIds.delete(id);
                        selectedNames.delete(id);
                    }

                    renderSelection();
                });
            });
        }

        function registerPreviewRemoval() {
            previewList?.addEventListener('click', function (event) {
                const button = event.target.closest('[data-remove-id]');

                if (!button) {
                    return;
                }

                const id = String(button.dataset.removeId);
                selectedIds.delete(id);
                selectedNames.delete(id);
                renderSelection();
            });
        }

        function registerSelectAll() {
            if (!checkAll) {
                return;
            }

            checkAll.addEventListener('change', function () {
                const visibleEligible = checkboxes.filter((checkbox) => !checkbox.disabled);

                visibleEligible.forEach((checkbox) => {
                    const id = String(checkbox.value);
                    const name = checkbox.dataset.nama || 'Siswa';

                    if (this.checked) {
                        selectedIds.add(id);
                        selectedNames.set(id, name);
                    } else {
                        if (selectedIds.has(id)) {
                            selectedIds.delete(id);
                            selectedNames.delete(id);
                        }
                    }
                });

                renderSelection();
            });
        }

        if (document.body.innerText.includes('Anggota berhasil ditambahkan')) {
            clearSelection();
        }

        registerCheckboxSelection();
        registerPreviewRemoval();
        registerSelectAll();
        renderSelection();

        const memberForm = document.getElementById('memberForm');
        if (memberForm) {
            memberForm.addEventListener('submit', function () {
                const submitButton = document.getElementById('submitSelectedBtn');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="me-2"><i class="bi bi-arrow-repeat me-1"></i></span>Menambahkan...';
                }
            });
        }
    })();
</script>

@endsection