@extends('layouts.app')

@section('title', 'Approval Data Kepegawaian')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <h3 class="fw-bold mb-2">
                        Approval Data Kepegawaian
                    </h3>

                    <p class="mb-0 opacity-75">
                        Validasi data kepegawaian yang diajukan pengguna sebelum digunakan dalam sistem.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>

            </div>
        </div>
    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Data Kepegawaian"
        subtitle="Seluruh data pegawai yang menunggu proses validasi oleh Kepala Sekolah"
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $data->count() }}
                <span class="text-badge">Pegawai</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>

            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari pegawai..."
            >

        </x-slot:actions>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>

                        <th width="70">
                            No
                        </th>

                        <th>
                            Nama Pegawai & NIP
                        </th>

                        <th width="150">
                            Status
                        </th>

                        <th width="80">
                            Validasi
                        </th>

                        <th width="80">
                            Aksi
                        </th>

                    </tr>
                </thead>

                <tbody id="tablePegawai">

                    @forelse($data as $row)

                        <tr>

                            {{-- NOMOR --}}
                            <td>

                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>

                            </td>

                            {{-- PEGAWAI --}}
                            <td>

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
                            <td>

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
                            <td>
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
                            <td>                                
                                <a
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                    href="{{ route('superadmin.datakepegawaian.show', $row->id) }}"
                                >                                        
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>                                    
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center py-5">

                                <i class="bi bi-folder2-open display-3 text-secondary"></i>

                                <h5 class="fw-bold mt-3">
                                    Belum Ada Data Kepegawaian
                                </h5>

                                <p class="text-muted mb-0">
                                    Tidak terdapat data kepegawaian yang perlu diproses.
                                </p>

                            </td>

                        </tr>

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

{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll('#tablePegawai tr');

        rows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                    ? ''
                    : 'none';

        });

    });

</script>

@endsection