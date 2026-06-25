@extends('layouts.app')

@section('title', 'Data Kepegawaian')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h1>Data Kepegawaian</h1>

    <div class="d-flex gap-2">

        <a href="{{ route('tu.datakepegawaian.create') }}"
           class="btn btn-primary">

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

@if(session('success'))

    <div class="alert alert-success">

        {{ session('success') }}

    </div>

@endif

<div class="card">

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

            <tbody>

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

@endpush