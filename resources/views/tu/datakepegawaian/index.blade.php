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
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-center mb-4">

        <div class="d-flex gap-2">

            <a href="{{ route('tu.datakepegawaian.create') }}"
            class="btn btn-dark">

                + Tambah Pegawai

            </a>

            @if($belumApprove > 0)

                <button type="button"
                        class="btn btn-success"
                        onclick="showExportWarning()">

                    Export Excel

                </button>

            @else

                <a href="{{ route('tu.datakepegawaian.export.excel') }}"
                class="btn btn-success">

                    Export Excel

                </a>

            @endif

        </div>

    </div>

    {{-- ALERT SUCCESS --}}
        @if(session('success'))

            <div class="alert alert-success border-0 shadow-sm">

                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}

            </div>

        @endif

        {{-- ALERT ERROR --}}
        @if(session('error'))

            <div class="alert alert-danger border-0 shadow-sm">

                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('error') }}

            </div>

        @endif

    <div class="card border-0 shadow-sm">

        {{-- TOP BAR --}}
            <div
                class="card-header bg-light border-0 p-4"
            >

                <div
                    class="d-flex justify-content-between align-items-center flex-wrap gap-3"
                >

                    <div>

                        <h5 class="fw-semibold mb-1">
                            Daftar Pegawai
                        </h5>

                        <span class="text-muted small">
                            Total:
                            {{ $data->count() }}
                            pegawai
                        </span>

                    </div>

                    <div style="max-width: 280px; width:100%;">

                        <input
                            type="text"
                            id="searchInput"
                            class="form-control rounded-3"
                            placeholder="Cari pegawai..."
                        >

                    </div>

                </div>

            </div>

        <div class="card-body">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th>Akun</th>
                        <th>Pesan</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody id="tablePegawai">

                    @forelse($data as $row)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $row->nama }}
                            </td>

                            <td>
                                {{ $row->nip ?? '-' }}
                            </td>

                            <td>

                                @if($row->approval_status == 'approved')

                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                        <i class="bi bi-check-circle me-1"></i> Valid
                                    </span>

                                @elseif($row->approval_status == 'rejected')

                                    <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">
                                        <i class="bi bi-x-circle me-1"></i> Ditolak
                                    </span>

                                @else

                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="bi bi-hourglass-split me-1"></i> Validasi
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

                                            <button class="btn btn-outline-success btn-sm">
                                                <i class="bi bi-person-plus me-1"></i> Tambah Akun
                                            </button>

                                        </form>

                                    @else

                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                            <i class="bi bi-person-check me-1"></i> Sudah Ada Akun
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

                                <div class="dropdown">

                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                        <i class="bi bi-gear-fill"></i>

                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

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
                                                onsubmit="return confirm('Hapus data ini?')">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Hapus
                                                </button>

                                            </form>
                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-4">

                                Belum ada data pegawai

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- MODAL PESAN --}}
    @foreach($data as $row)

        @if($row->approval_status == 'rejected')

            <div class="modal fade"
                id="messageModal{{ $row->id }}"
                tabindex="-1">

                <div class="modal-dialog modal-lg modal-dialog-centered">

                    <div class="modal-content border-0 shadow">

                        {{-- HEADER --}}
                        <div class="modal-header bg-primary text-white">

                            <h5 class="modal-title fw-bold">
                                Detail Pesan Penolakan
                            </h5>

                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal">
                            </button>

                        </div>

                        {{-- BODY --}}
                        <div class="modal-body p-4">

                            {{-- JUDUL --}}
                            <div class="text-center mb-4">

                                <h5 class="fw-bold mb-1">
                                    INFORMASI PENOLAKAN DATA
                                </h5>

                                <small class="text-muted">
                                    Sistem Informasi Sekolah
                                </small>

                            </div>

                            {{-- INFORMASI --}}
                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <table class="table table-borderless">

                                        <tr>

                                            <td width="170">
                                                <strong>Pengirim</strong>
                                            </td>

                                            <td>
                                                : Kepala Sekolah
                                            </td>

                                        </tr>

                                        <tr>

                                            <td>
                                                <strong>Nama Pegawai</strong>
                                            </td>

                                            <td>
                                                : {{ $row->nama }}
                                            </td>

                                        </tr>

                                        <tr>

                                            <td>
                                                <strong>NIP</strong>
                                            </td>

                                            <td>
                                                : {{ $row->nip ?? '-' }}
                                            </td>

                                        </tr>

                                    </table>

                                </div>

                                <div class="col-md-6">

                                    <table class="table table-borderless">

                                        <tr>

                                            <td width="170">
                                                <strong>Tanggal Pesan</strong>
                                            </td>

                                            <td>

                                                :

                                                {{ $row->rejected_at
                                                    ? \Carbon\Carbon::parse($row->rejected_at)->timezone('Asia/Jakarta')->format('d F Y H:i')
                                                    : '-' }}

                                            </td>

                                        </tr>

                                        <tr>

                                            <td>
                                                <strong>Status</strong>
                                            </td>

                                            <td>

                                                :

                                                <span class="badge bg-danger">
                                                    Ditolak
                                                </span>

                                            </td>

                                        </tr>

                                    </table>

                                </div>

                            </div>

                            {{-- ALASAN --}}
                            <div>

                                <label class="fw-bold mb-2">

                                    Alasan Penolakan

                                </label>

                                <div class="alert alert-danger border-0 mb-0">

                                    {{ $row->rejected_reason }}

                                </div>

                            </div>

                        </div>

                        {{-- FOOTER --}}
                        <div class="modal-footer border-0">

                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

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