@extends('layouts.app')

@section('title', 'Manajemen Penugasan')

@section('content')
<div class="container-fluid dt-siswa-page assignment-page">
    <x-table.page-header
        icon="bi-person-check"
        eyebrow="Manajemen / Penugasan"
        title="Manajemen Penugasan"
        description="Atur tanggung jawab dan akses pendukung setiap pegawai dalam satu tempat."
    />

    <div id="assignmentListView">
        <x-table.card
            title="Data Penugasan"
            subtitle="Daftar tanggung jawab pegawai dan statusnya"
            :count="0"
            count-id="assignmentRecordCount"
        >
            <x-slot:actions>
                <div class="assignment-table-toolbar">
                    <input
                        type="search"
                        id="assignmentSearch"
                        class="form-control table-search"
                        placeholder="Cari pegawai atau penugasan..."
                    >
                    <select id="assignmentStatusFilter" class="form-select assignment-status-filter" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        <option value="Draft">Draft</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Dicabut">Dicabut</option>
                    </select>
                    <button type="button" class="btn btn-outline-primary rounded-3" id="publishSelectedDrafts" disabled>
                        <i class="bi bi-send-check me-2"></i>Publikasikan Penugasan
                        <span class="badge bg-primary ms-1" id="selectedDraftCount">0</span>
                    </button>
                    <button type="button" class="btn btn-primary rounded-3" id="addAssignment">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Penugasan
                    </button>
                </div>
            </x-slot:actions>

            <div class="table-responsive dt-siswa-table-wrap">
                <table class="table table-hover align-middle mb-0 dt-table">
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th width="50" class="text-center">
                                <input type="checkbox" class="form-check-input" id="selectAllDrafts" aria-label="Pilih semua draft pada halaman" disabled>
                            </th>
                            <th width="240">Pegawai</th>
                            <th width="190">Penugasan</th>
                            <th>Rincian</th>
                            <th width="160">Status</th>
                            <th width="300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="assignmentTableBody">
                        <x-table.empty
                            :colspan="7"
                            title="Belum Ada Penugasan"
                            description="Penugasan yang disimpan sebagai simulasi akan muncul di sini."
                        />
                    </tbody>
                </table>
            </div>
            <div id="assignmentPagination"></div>
        </x-table.card>
        <p class="small text-muted mt-2">Perubahan draft dan status penugasan disimpan permanen.</p>
    </div>

    <div id="assignmentFormView" class="d-none">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1" id="assignmentFormTitle">Tambah Penugasan</h4>
                <p class="text-muted mb-0">Lengkapi penugasan lalu simpan sebagai draft.</p>
            </div>
            <span class="badge bg-warning-subtle text-warning-emphasis align-self-sm-center">Draft Penugasan</span>
        </div>
        <div id="formValidationError" class="alert alert-danger d-none" role="alert"></div>
        <div id="formModeNotice" class="alert alert-info d-none" role="status"></div>

    <div class="card assignment-card shadow-sm mb-4">
        <div class="card-header px-4 pt-4 pb-2">
            <h5 class="fw-bold mb-1">1. Pilih Pegawai</h5>
            <p class="text-muted mb-0">Pilih satu pegawai untuk melihat dan mengatur penugasannya.</p>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="d-flex flex-wrap gap-2 mb-3 assignment-filter" id="employeeFilter">
                <button type="button" class="btn btn-outline-secondary btn-sm active" data-filter="all">Semua Pegawai</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-filter="guru">Guru</button>
            </div>

            <div class="row g-3 align-items-end">
                <div class="col-lg-8">
                    <label for="employeeSelect" class="form-label fw-semibold">Nama Pegawai</label>
                    <input type="search" class="form-control mb-2" id="employeeSearch" placeholder="Cari nama atau NIP..." autocomplete="off">
                    <select class="form-select" id="employeeSelect">
                        <option value="">-- Pilih pegawai --</option>
                        @foreach($employees as $employee)
                            @php
                                $isGuru = (bool) $employee->guru;
                                $role = $employee->user?->role;
                                $category = 'guru';
                                $identity = 'Guru';
                            @endphp
                            <option
                                value="{{ $employee->id }}"
                                data-name="{{ e($employee->nama) }}"
                                data-nip="{{ e($employee->nip) }}"
                                data-category="{{ $category }}"
                                data-role="{{ e($role ?? '') }}"
                                data-identity="{{ $identity }}"
                            >
                                {{ $employee->nama }}@if($employee->nip) â€” NIP {{ $employee->nip }}@endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4">
                    <div id="selectedEmployeeSummary" class="border rounded-3 p-3 bg-light text-muted">
                        Belum ada pegawai dipilih.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="assignmentWorkspace">
        <div class="card assignment-card shadow-sm mb-4">
            <div class="card-header px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-1">2. Penugasan</h5>
                <p class="text-muted mb-0">Satu pegawai dapat memiliki beberapa tanggung jawab sekaligus.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <div id="employeeLockedNote" class="locked-overlay border rounded-3 p-3 mb-3">
                    <i class="bi bi-lock-fill me-2"></i>Pilih pegawai terlebih dahulu untuk mengatur penugasan.
                </div>
                <div class="row g-3">
                    @php
                        $assignmentOptions = [
                            'operator' => ['Operator Sekolah', 'Mengelola data operasional sekolah.', 'bi bi-building'],
                            'ppdb' => ['Admin PPDB', 'Mengelola proses penerimaan peserta didik baru.', 'bi bi-mortarboard'],
                            'wali' => ['Wali Kelas', 'Mengelola kelas yang menjadi tanggung jawab.', 'bi bi-person-badge'],
                            'guru' => ['Guru Bidang Studi', 'Mengajar mata pelajaran pada kelas tertentu.', 'bi bi-journal-text'],
                            'ekskul' => ['Pembina Ekstrakurikuler', 'Mengelola kegiatan ekstrakurikuler.', 'bi bi-trophy'],
                        ];
                    @endphp
                    @foreach($assignmentOptions as $key => $option)
                        <div class="col-md-6 col-xl-4">
                            <label class="assignment-option d-flex align-items-start gap-3 is-locked" data-assignment-option="{{ $key }}">
                                <input type="checkbox" class="form-check-input mt-1 assignment-checkbox" value="{{ $key }}" disabled>
                                <span class="assignment-copy flex-grow-1">
                                    <span class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi {{ $option[2] }} text-primary"></i>
                                        <span class="fw-semibold assignment-title">{{ $option[0] }}</span>
                                    </span>
                                    <small class="text-muted d-block">{{ $option[1] }}</small>
                                </span>
                                <i class="bi bi-check-circle-fill assignment-check"></i>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card assignment-card shadow-sm mb-4">
            <div class="card-header px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-1">3. Rincian Penugasan</h5>
                <p class="text-muted mb-0">Pengaturan ini mengikuti penugasan yang dipilih.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="detail-panel is-disabled p-3 h-100" id="waliPanel">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Wali Kelas</h6>
                                <i class="bi bi-lock-fill text-secondary"></i>
                            </div>
                            <p class="locked-overlay mb-2" id="waliLockedText">Aktifkan penugasan Wali Kelas untuk memilih kelas.</p>
                            <select class="form-select" id="waliClass" disabled>
                                <option value="">Pilih kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->nama_kelas_label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-panel is-disabled p-3 h-100" id="guruPanel">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Guru Bidang Studi</h6>
                                <i class="bi bi-lock-fill text-secondary"></i>
                            </div>
                            <p class="locked-overlay mb-2" id="guruLockedText">Aktifkan Guru Bidang Studi untuk memilih mata pelajaran dan kelas.</p>
                            <fieldset class="border-0 p-0 mb-2" id="subjectOptions" disabled>
                                <legend class="form-label small fw-semibold mb-2">Mata pelajaran</legend>
                                <div class="detail-checkbox-list border rounded-3 p-2">
                                    @foreach($subjects as $subject)
                                        <label class="form-check mb-1">
                                            <input class="form-check-input subject-checkbox" type="checkbox" value="{{ $subject->id }}">
                                            <span class="form-check-label">{{ $subject->nama_mapel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                            <fieldset class="border-0 p-0" id="guruClassOptions" disabled>
                                <legend class="form-label small fw-semibold mb-2">Kelas diajar</legend>
                                <div class="detail-checkbox-list border rounded-3 p-2">
                                    @foreach($classes as $class)
                                        <label class="form-check mb-1">
                                            <input class="form-check-input guru-class-checkbox" type="checkbox" value="{{ $class->id }}">
                                            <span class="form-check-label">{{ $class->nama_kelas_label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-panel is-disabled p-3 h-100" id="ekskulPanel">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Pembina Ekstrakurikuler</h6>
                                <i class="bi bi-lock-fill text-secondary"></i>
                            </div>
                            <p class="locked-overlay mb-2" id="ekskulLockedText">Aktifkan Pembina Ekstrakurikuler untuk memilih kegiatan.</p>
                            <fieldset class="border-0 p-0" id="ekskulOptions" disabled>
                                <legend class="form-label small fw-semibold mb-2">Kegiatan yang dibina</legend>
                                <div class="detail-checkbox-list border rounded-3 p-2">
                                    @foreach($extracurriculars as $extracurricular)
                                        <label class="form-check mb-1">
                                            <input class="form-check-input ekskul-checkbox" type="checkbox" value="{{ $extracurricular->id }}">
                                            <span class="form-check-label">{{ $extracurricular->nama }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card assignment-card shadow-sm mb-4">
            <div class="card-header px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-1">4. Hak Akses Menu</h5>
                <p class="text-muted mb-0">Setiap penugasan memiliki pilihan menu sendiri. Hak dari beberapa penugasan dapat digabungkan.</p>
            </div>
            <div class="card-body px-4 pb-4">
                <p id="menuAssignmentEmptyNote" class="text-muted border rounded-3 bg-light p-3 mb-3">
                    Pilih penugasan terlebih dahulu untuk mengatur hak akses menu.
                </p>
                <div class="row g-3">
                    @php($assignmentMenus = config('assignment_menus.types', []))
                    @foreach($assignmentMenus as $assignmentKey => $assignment)
                        <div class="col-xl-6 d-none" data-menu-panel="{{ $assignmentKey }}">
                            <div class="assignment-menu-panel is-disabled p-3 h-100">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $assignment['title'] }}</h6>
                                        <small class="text-muted" data-menu-count="{{ $assignmentKey }}">
                                            0 dari {{ count($assignment['menus']) }} menu dipilih
                                        </small>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-outline-primary btn-sm menu-select-all" data-menu-assignment="{{ $assignmentKey }}" disabled>Centang Semua</button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm menu-clear-all" data-menu-assignment="{{ $assignmentKey }}" disabled>Hapus Semua</button>
                                    </div>
                                </div>
                                <div class="assignment-menu-body row g-2">
                                    @foreach($assignment['menus'] as $menuKey => $menu)
                                        <div class="col-md-6">
                                            <label class="menu-access-option d-flex align-items-start gap-2 h-100">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input mt-1 menu-access-checkbox"
                                                    data-menu-assignment="{{ $assignmentKey }}"
                                                    value="{{ $menuKey }}"
                                                    disabled
                                                >
                                                <span>
                                                    <span class="fw-semibold d-block">{{ $menu[0] }}</span>
                                                    <small class="text-muted">{{ $menu[1] }}</small>
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="locked-overlay mt-3 mb-0" data-menu-lock="{{ $assignmentKey }}">
                                    Aktifkan penugasan {{ $assignment['title'] }} untuk mengatur menu.
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mb-5">
            <button type="button" class="btn btn-outline-secondary rounded-3 px-4" id="cancelAssignmentForm">
                <i class="bi bi-x-lg me-2"></i>Batal
            </button>
            <button type="button" class="btn btn-primary rounded-3 px-4" id="saveAssignmentDraft">
                <i class="bi bi-save me-2"></i>Simpan Draft
            </button>
        </div>
    </div>
</div>
</div>

<script>
window.assignmentInitialRecords = @json($records);
</script>

<div class="modal fade" id="assignmentDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Penugasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="assignmentDetailBody"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="publishAssignmentsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Konfirmasi Publikasi Penugasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Hanya draft yang dipilih berikut akan berubah menjadi Aktif. Draft yang tidak dipilih, serta penugasan Aktif dan Dicabut, tidak akan berubah.</p>
                <div id="publishAssignmentsSummary"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmPublishAssignments">Ya, Publikasikan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="unsavedChangesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Perubahan Belum Disimpan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">Perubahan pada formulir akan hilang jika kembali sekarang.</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Lanjutkan Mengisi</button>
                <button type="button" class="btn btn-danger" id="leaveWithoutSaving">Kembali Tanpa Menyimpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="assignmentActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="assignmentActionTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="assignmentActionMessage"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmAssignmentAction">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>


@endsection
