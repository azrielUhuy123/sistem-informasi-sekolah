@extends('layouts.app')

@section('title', 'Approval Data Kepegawaian')

@section('content')

<div class="container-fluid dt-siswa-page">
    <nav aria-label="Breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">Kepala Sekolah</li>
            <li class="breadcrumb-item">Manajemen Kepegawaian</li>
            <li class="breadcrumb-item active" aria-current="page">Pegawai Baru</li>
        </ol>
    </nav>

    <x-table.page-header
        icon="bi-person-vcard"
        eyebrow="Manajemen Kepegawaian"
        title="Pegawai Baru"
        description="Validasi pengajuan data kepegawaian baru sebelum digunakan dalam sistem."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Persetujuan Data Kepegawaian"
        subtitle="Seluruh data pegawai yang menunggu proses validasi oleh Kepala Sekolah"
        :count="$data->total()"

    >
        <x-slot:headerActions>
            <nav class="dt-employee-navigation" aria-label="Navigasi Manajemen Kepegawaian">

                <a
                    href="{{ route('superadmin.pegawai-changes.employees') }}"
                    class="dt-employee-navigation__link {{ request()->routeIs('superadmin.pegawai-changes.employees') ? 'is-active' : '' }}"
                    @if (request()->routeIs('superadmin.pegawai-changes.employees')) aria-current="page" @endif
                >
                    Data Pegawai
                </a>

                <a
                    href="{{ route('superadmin.datakepegawaian.approval') }}"
                    class="dt-employee-navigation__link {{ request()->routeIs('superadmin.datakepegawaian.approval') ? 'is-active' : '' }}"
                    @if (request()->routeIs('superadmin.datakepegawaian.approval')) aria-current="page" @endif
                >
                    Pegawai Baru
                </a>

                <a
                    href="{{ route('superadmin.pegawai-changes.index') }}"
                    class="dt-employee-navigation__link {{ request()->routeIs('superadmin.pegawai-changes.index') ? 'is-active' : '' }}"
                    @if (request()->routeIs('superadmin.pegawai-changes.index')) aria-current="page" @endif
                >
                    Perubahan Data
                </a>

            </nav>
        </x-slot:headerActions>
        <x-slot:toolbar>
            <x-table.toolbar
                table-id="dt-superadmin-kepegawaian"
                action="{{ route('superadmin.datakepegawaian.approval') }}"
                :searchable="true"
                search-name="search"
                search-value="{{ $search }}"
                search-label="Cari pegawai"
                search-placeholder="Nama, NIP, atau jabatan"
                filter-name="status"
                :filter-value="$selectedStatuses"
                filter-label="Status"
                filter-menu-title="Filter status kepegawaian"
                :filter-options="$statusOptions"
                :column-toggle="true"
                :refreshable="true"
                refresh-url="{{ request()->fullUrl() }}"
            >
                <x-slot:columnMenu>
                    @foreach ([
                        'number' => 'No',
                        'employee' => 'Nama Pegawai & NIP',
                        'status' => 'Status',
                        'approval' => 'Validasi',
                        'action' => 'Aksi',
                    ] as $column => $label)
                        <label class="dt-column-menu__option">
                            <input
                                type="checkbox"
                                data-dt-column="{{ $column }}"
                                checked
                            >
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </x-slot:columnMenu>
            </x-table.toolbar>
        </x-slot:toolbar>
        <x-table.chips
            :criteria="array_merge(
                [
                    [
                        'name' => 'search',
                        'label' => 'Search',
                        'value' => $search,
                    ],
                ],
                $statusChipCriteria
            )"
        />

        <div class="table-responsive dt-siswa-table-wrap">

            <table id="dt-superadmin-kepegawaian" class="table table-hover align-middle dt-table">
                <caption class="visually-hidden">Data kepegawaian beserta status validasi dan tindakan yang tersedia</caption>
                <colgroup>
                    <col data-dt-column="nip">
                    <col data-dt-column="employee">
                    <col data-dt-column="status">                    
                    <col data-dt-column="action">
                </colgroup>

                <thead>
                    <tr>
                        <th data-dt-column="nip">NIP</th>
                        <th data-dt-column="employee">Pegawai</th>
                        <th data-dt-column="status">Status</th>
                        <th data-dt-column="action">Aksi</th>                        
                    </tr>                    
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            {{-- NOMOR --}}
                            <td data-dt-column="nip">
                                {{ $row->nip ?? '-' }}
                            </td>
                            {{-- PEGAWAI --}}
                            <td data-dt-column="employee">
                                {{ $row->nama }}
                            </td>
                            {{-- STATUS --}}
                            <td data-dt-column="status">
                                @if($row->approval_status == 'approved')
                                    <span class="badge-status aktif">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Valid
                                    </span>
                                @elseif($row->approval_status == 'rejected')
                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-x-circle-fill me-1"></i>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="badge-status pending">
                                        <i class="bi bi-hourglass-split me-1"></i>
                                        Validasi
                                    </span>
                                @endif
                            </td>
                            <td data-dt-column="action">
                            {{-- VALIDASI --}}
                                <div class="d-flex flex-wrap gap-2">
                                    @if($row->approval_status != 'rejected')                            
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger dt-siswa-detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $row->id }}"
                                        >
                                            Tolak <i class="bi bi-x-lg"></i>                                                                             
                                        </button>                                    
                                    @endif
                                    @if($row->approval_status != 'approved')                                
                                        <form
                                            action="{{ route('superadmin.datakepegawaian.approve', $row->id) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-primary dt-siswa-detail btn-confirm"
                                                data-title="VALIDASI DATA KEPEGAWAIAN INI?"
                                                data-message="Data kepegawaian ini akan divalidasi dan dinyatakan valid."
                                                data-button="btn-success"
                                            >                                            
                                                Setujui <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>                                     
                                    @endif                            
                                    
                                </div>
                            </td>
                        </tr>

                    @empty
                        @if ($search !== '' || ! empty($selectedStatuses))
                            <x-table.empty
                                :colspan="5"
                                state="no-results"
                                title="Tidak ada data pegawai yang cocok"
                                description="Ubah status atau kata kunci pencarian, lalu coba lagi."
                            />
                        @else
                            <x-table.empty
                                :colspan="5"
                                state="empty"
                                title="Belum ada data kepegawaian"
                                description="Data kepegawaian belum tersedia."
                            />
                        @endif

                    @endforelse

                </tbody>

            </table>

        </div>
        <x-table.pagination
            :paginator="$data"
            :show-summary="true"
        />

    </x-table.card>


    {{-- MODAL REJECT --}}
    @foreach($data as $row)

        <div
            class="modal fade"
            id="rejectModal{{ $row->id }}"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow">

                    <form
                        action="{{ route('superadmin.datakepegawaian.reject', $row->id) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        {{-- HEADER --}}
                        <div class="modal-header">

                            <h5 class="modal-title fw-bold">
                                Alasan Penolakan
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                            ></button>

                        </div>

                        {{-- BODY --}}
                        <div class="modal-body">

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Alasan Reject
                                </label>

                                <textarea
                                    name="rejected_reason"
                                    class="form-control"
                                    rows="5"
                                    placeholder="Masukkan alasan penolakan..."
                                    required
                                ></textarea>

                            </div>

                        </div>

                        {{-- FOOTER --}}
                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-light border rounded-pill px-4"
                                data-bs-dismiss="modal"
                            >
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="btn btn-danger rounded-pill px-4"
                                data-title="Tolak Data Kepegawaian"
                                data-message="Data kepegawaian ini akan ditolak."
                                data-button="btn-danger"
                            >
                                <i class="bi bi-x-circle me-1"></i>
                                Tolak Data
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endforeach

</div>


</script>

@endsection