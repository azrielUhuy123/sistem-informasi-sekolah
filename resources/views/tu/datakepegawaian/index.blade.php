@extends('layouts.app')

@section('title', 'Data Kepegawaian')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Kepegawaian</h3>
                    <p class="mb-0 opacity-75">Kelola data pegawai sekolah</p>
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
    {{-- CARD --}}
    <x-table.card
        title="Daftar Pegawai"
        subtitle="Seluruh pegawai pada SDN Mekar Jaya."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $data->count() }} <span class="text-badge">Pegawai</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>            
            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari pegawai..."                
            > 
            <x-button.add
                :href="route('tu.datakepegawaian.create')"
                label="Tambah Pegawai"
            />            

        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr> 
                        <th width="70">No</th>
                        <th>Nama Pegawai & NIP</th>
                        <th width="150">Status</th>
                        <th width="150">Akun</th>
                        <th width="150">Pesan</th>
                        <th width="90">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tablePegawai">
                    @forelse($data as $row)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
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
                            <td>
                                @if($row->approval_status == 'approved')
                                    <span class="badge-status aktif">
                                        <i class="bi bi-check-circle-fill me-2"></i> Valid
                                    </span>
                                @elseif($row->approval_status == 'rejected')
                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-x-circle-fill me-2"></i> Ditolak
                                    </span> 
                                @else
                                    <span class="badge-status pending">
                                        <i class="bi bi-hourglass-split me-2"></i> Validasi
                                    </span>
                                @endif
                            </td>
                            {{-- AKUN --}}
                            <td>
                                @if($row->approval_status == 'approved')
                                    @if(!$row->user)
                                        <form
                                            action="{{ route('tu.datakepegawaian.create-account', $row->id) }}"
                                            method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-success rounded-pill btn-sm px-3 py-0">
                                                <i class="bi bi-person-plus me-2"></i> Tambah Akun
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge-status selesai">
                                            <i class="bi bi-person-check me-2"></i> Sudah Ada Akun
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            {{-- PESAN --}}
                            <td>
                                @if($row->approval_status == 'rejected')
                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#messageModal{{ $row->id }}">
                                        <i id="message-icon-{{ $row->id }}"
                                        class="bi {{ $row->is_message_read ? 'bi-envelope-open-fill' : 'bi-envelope-fill' }}">
                                        </i>
                                    </button>
                                @else
                                    <span class="text-muted">
                                        -
                                    </span>
                                @endif
                            </td>
                            {{-- AKSI --}}
                            <td>
                                <x-table.actions>                                
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tu.datakepegawaian.show', $row->id) }}">
                                            <i class="bi bi-eye me-2"></i> Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tu.datakepegawaian.edit', $row->id) }}">
                                            <i class="bi bi-pencil-square me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('tu.datakepegawaian.destroy', $row->id) }}"
                                            method="POST"
                                            
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger
                                            btn-confirm"
                                            data-title="HAPUS PEGAWAI INI?"
                                            data-message="Pegawai ini akan dihapus dari sistem ini."
                                            data-button="btn-danger">
                                                <i class="bi bi-trash me-2"></i> Hapus
                                            </button>
                                        </form>
                                    </li>                               
                                </x-table.actions>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="text-center py-5">
                                    <i class="bi bi-folder2-open display-3 text-secondary"></i>
                                    <h5 class="fw-bold mt-3">Belum Ada Pegawai Yang Terdaftar</h5>
                                    <p class="text-muted mb-0">Saat ini belum ada pegawai yang terdaftar pada sekolah ini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>    
        </div>
    </x-table.card>

    {{-- MODAL PESAN --}}
@foreach ($data as $row)
    @if ($row->approval_status === 'rejected')
        <div
            class="modal fade"
            id="messageModal{{ $row->id }}"
            tabindex="-1"
            aria-labelledby="messageModalLabel{{ $row->id }}"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">

                    {{-- HEADER --}}
                    <div class="modal-header border-0 bg-light">
                        <h5
                            class="modal-title text-muted small"
                            id="messageModalLabel{{ $row->id }}"
                        >
                            Detail Pesan Penolakan
                        </h5>

                        <button
                            type="button"
                            class="btn-close btn-close-dark"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"
                        ></button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body border-0">

                        {{-- JUDUL --}}
                        <div class="text-center mb-4">
                            <h5 class="fw-bold text-primary mb-1">
                                INFORMASI PENOLAKAN DATA
                            </h5>

                            <small class="text-muted">
                                Sistem Informasi Sekolah
                            </small>
                        </div>

                        {{-- INFORMASI --}}
                        <div class="row g-4 mb-4">

                            {{-- INFORMASI PEGAWAI --}}
                            <div class="col-md-6">
                                <div class="d-flex flex-column gap-3">

                                    <div class="row">
                                        <div class="col-5 fw-semibold text-muted">
                                            Pengirim
                                        </div>
                                        <div class="col-7">
                                            Kepala Sekolah
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-5 fw-semibold text-muted">
                                            Nama Pegawai
                                        </div>
                                        <div class="col-7">
                                            {{ $row->nama }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-5 fw-semibold text-muted">
                                            NIP
                                        </div>
                                        <div class="col-7">
                                            {{ $row->nip ?? '-' }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- INFORMASI PESAN --}}
                            <div class="col-md-6">
                                <div class="d-flex flex-column gap-3">

                                    <div class="row">
                                        <div class="col-5 fw-semibold text-muted">
                                            Tanggal Pesan
                                        </div>
                                        <div class="col-7">
                                            {{ $row->rejected_at
                                                ? \Carbon\Carbon::parse($row->rejected_at)
                                                    ->timezone('Asia/Jakarta')
                                                    ->format('d F Y H:i')
                                                : '-' }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-5 fw-semibold text-muted">
                                            Status
                                        </div>
                                        <div class="col-7">
                                            <span class="badge bg-danger">
                                                Ditolak
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- ALASAN PENOLAKAN --}}
                        <div>
                            <label class="fw-semibold mb-2">
                                Alasan Penolakan
                            </label>

                            <div class="alert alert-danger border-0 mb-0">
                                {{ $row->rejected_reason ?: 'Tidak ada alasan penolakan.' }}
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif
@endforeach


@endsection

@push('scripts')

{{-- SWEET ALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    function showExportWarning()
    {
        Swal.fire({

            icon: 'warning',

            title: 'Export Ditolak',

            text: 'Mohon cek kembali data yang belum di Validasi. Data dapat di export jika semua data sudah Valid.',

            confirmButtonText: 'Mengerti',

            confirmButtonColor: '#d33'

        });
    }

</script>

{{-- READ MESSAGE --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    @foreach($data as $row)

        @if($row->approval_status == 'rejected')

            const modal{{ $row->id }} =
                document.getElementById(
                    'messageModal{{ $row->id }}'
                );

            if (modal{{ $row->id }}) {

                modal{{ $row->id }}.addEventListener(
                    'show.bs.modal',
                    function () {

                        fetch(
                            "{{ route('tu.datakepegawaian.read-message', $row->id) }}",
                            {
                                method: 'PATCH',

                                headers: {

                                    'X-CSRF-TOKEN':
                                        '{{ csrf_token() }}',

                                    'Accept':
                                        'application/json',

                                }

                            }
                        );

                        const icon =
                            document.getElementById(
                                'message-icon-{{ $row->id }}'
                            );

                        if (icon) {

                            icon.classList.remove(
                                'bi-envelope-fill'
                            );

                            icon.classList.add(
                                'bi-envelope-open-fill'
                            );

                        }

                    }
                );

            }

        @endif

    @endforeach

});

</script>

{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tablePegawai tr'
            );

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


@endpush