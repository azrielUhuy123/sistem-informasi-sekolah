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
        title="Daftar Data Kepegawaian"
        subtitle="Seluruh data pegawai yang menunggu proses validasi oleh Kepala Sekolah"
        :count="$data->count()"
    >

        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari pegawai"
                search-placeholder="Cari pegawai..."
                table-id="dt-superadmin-kepegawaian"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >
                <x-slot:navigation>
                    <div class="nav nav-pills gap-1" role="tablist">
                        <a class="nav-link" role="tab" aria-selected="false" href="{{ route('superadmin.pegawai-changes.employees') }}">Data Pegawai</a>
                        <a class="nav-link active" role="tab" aria-selected="true" href="{{ route('superadmin.datakepegawaian.approval') }}">Pegawai Baru</a>
                        <a class="nav-link" role="tab" aria-selected="false" href="{{ route('superadmin.pegawai-changes.index') }}">Perubahan Data</a>
                    </div>
                </x-slot:navigation>
                <x-slot:columnMenu>
                    @foreach ([
                        'number' => 'No',
                        'employee' => 'Nama Pegawai & NIP',
                        'status' => 'Status',
                        'approval' => 'Validasi',
                        'action' => 'Aksi',
                    ] as $column => $label)
                        <label class="dt-column-menu__option">
                            <input type="checkbox" data-dt-column="{{ $column }}" checked>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </x-slot:columnMenu>
            </x-table.local-toolbar>
        </x-slot:toolbar>

        <div class="table-responsive dt-siswa-table-wrap">

            <table id="dt-superadmin-kepegawaian" class="table table-hover align-middle mb-0 dt-table">
                <caption class="visually-hidden">Data kepegawaian beserta status validasi dan tindakan yang tersedia</caption>
                <colgroup>
                    <col data-dt-column="number">
                    <col data-dt-column="employee">
                    <col data-dt-column="status">
                    <col data-dt-column="approval">
                    <col data-dt-column="action">
                </colgroup>

                <thead>
                    <tr>

                        <x-table.heading width="70" column="number">
                            No
                        </x-table.heading>

                        <x-table.heading column="employee">
                            Nama Pegawai & NIP
                        </x-table.heading>

                        <x-table.heading width="150" column="status">
                            Status
                        </x-table.heading>

                        <x-table.heading width="80" column="approval">
                            Validasi
                        </x-table.heading>

                        <x-table.heading width="80" column="action">
                            Aksi
                        </x-table.heading>

                    </tr>
                </thead>

                <tbody id="tablePegawai">

                    @forelse($data as $row)

                        <tr>

                            {{-- NOMOR --}}
                            <td data-dt-column="number">

                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>

                            </td>

                            {{-- PEGAWAI --}}
                            <td data-dt-column="employee">

                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width: 48px; height: 48px;"
                                    >
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $row->nama }}
                                        </div>

                                        <small class="text-muted">
                                            NIP : {{ $row->nip ?? '-' }}
                                        </small>

                                    </div>

                                </div>

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
                            <td data-dt-column="approval">
                            {{-- VALIDASI --}}
                                @if($row->approval_status != 'approved')                                
                                    <form
                                        action="{{ route('superadmin.datakepegawaian.approve', $row->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="btn btn-success btm-sm rounded-3 px-3 py-0 btn-confirm"
                                            data-title="VALIDASI DATA KEPEGAWAIAN INI?"
                                            data-message="Data kepegawaian ini akan divalidasi dan dinyatakan valid."
                                            data-button="btn-success"
                                        >                                            
                                            <small>Setujui</small>
                                        </button>
                                    </form>                                     
                                @endif                            
                                @if($row->approval_status != 'rejected')                            
                                    <button
                                        type="button"
                                        class="btn btn-danger btm-sm rounded-3 px-3 py-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $row->id }}"
                                    >
                                        <small>Tolak</small>                                                                             
                                    </button>                                    
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td data-dt-column="action">
                                <a
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                    href="{{ route('superadmin.datakepegawaian.show', $row->id) }}"
                                >                                        
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>                                    
                            </td>

                        </tr>

                    @empty

                        <x-table.empty
                            :colspan="5"
                            title="Belum Ada Data Kepegawaian"
                            description="Tidak terdapat data kepegawaian yang perlu diproses."
                        />

                    @endforelse

                </tbody>

            </table>

        </div>

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