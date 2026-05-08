@extends('layouts.app')

@section('title', 'Approval Data Kepegawaian')

@section('content')

<div class="container-fluid">

    <h4 class="fw-bold mb-4">
        Approval Data Kepegawaian
    </h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th width="260">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $row)

                        <tr>

                            {{-- NAMA --}}
                            <td>
                                {{ $row->nama }}
                            </td>

                            {{-- NIP --}}
                            <td>
                                {{ $row->nip ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>

                                @if($row->approval_status == 'pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($row->approval_status == 'approved')

                                    <span class="badge bg-success">
                                        Valid
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Ditolak
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td class="d-flex gap-1">

                                {{-- DETAIL --}}
                                <a href="{{ route('superadmin.datakepegawaian.show', $row->id) }}"
                                   class="btn btn-info btn-sm text-white">
                                    Detail
                                </a>

                                {{-- APPROVE --}}
                                <form action="{{ route('superadmin.datakepegawaian.approve', $row->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit"
                                            class="btn btn-success btn-sm">
                                        Validasi
                                    </button>

                                </form>

                                {{-- REJECT --}}
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal{{ $row->id }}">
                                    Tolak
                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="text-center text-muted py-4">

                                Belum ada data kepegawaian

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>


@foreach($data as $row)

<!-- MODAL REJECT -->
<div class="modal fade"
     id="rejectModal{{ $row->id }}"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('superadmin.datakepegawaian.reject', $row->id) }}"
                  method="POST">

                @csrf
                @method('PATCH')

                {{-- HEADER --}}
                <div class="modal-header">

                    <h5 class="modal-title">
                        Alasan Penolakan
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Alasan Reject
                        </label>

                        <textarea
                            name="rejected_reason"
                            class="form-control"
                            rows="5"
                            placeholder="Masukkan alasan penolakan..."
                            required></textarea>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button type="submit"
                            class="btn btn-danger">

                        Reject Data

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endforeach

@endsection