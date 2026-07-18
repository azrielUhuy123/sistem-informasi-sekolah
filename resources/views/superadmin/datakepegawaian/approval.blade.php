@extends('layouts.app')

@section('title', 'Approval Data Kepegawaian')

@section('content')

<div class="container-fluid py-3">

    @php

        $totalData = $data->count();

        $pending = $data->where('approval_status','pending')->count();

        $approved = $data->where('approval_status','approved')->count();

        $rejected = $data->where('approval_status','rejected')->count();

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h2 class="fw-bold mb-2">

                        Approval Data Kepegawaian

                    </h2>

                    <p class="mb-0 opacity-75">

                        Lakukan proses validasi data kepegawaian yang diajukan oleh pengguna,
                        termasuk menyetujui maupun menolak data sesuai hasil verifikasi.

                    </p>

                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center h-100">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $totalData }}

                                </h4>

                                <small class="text-muted">

                                    Total

                                </small>

                            </div>

                        </div>

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center h-100">

                                <h4 class="fw-bold text-warning mb-1">

                                    {{ $pending }}

                                </h4>

                                <small class="text-muted">

                                    Pending

                                </small>

                            </div>

                        </div>

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center h-100">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $approved }}

                                </h4>

                                <small class="text-muted">

                                    Valid

                                </small>

                            </div>

                        </div>

                        <div class="col-3">

                            <div class="bg-white rounded-4 p-3 text-center h-100">

                                <h4 class="fw-bold text-danger mb-1">

                                    {{ $rejected }}

                                </h4>

                                <small class="text-muted">

                                    Ditolak

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm rounded-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar Data Kepegawaian

                    </h5>

                    <small class="text-muted">

                        Seluruh data pegawai yang menunggu proses validasi oleh Kepala Sekolah.

                    </small>

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="70" class="text-center">

                                No

                            </th>

                            <th>

                                Pegawai

                            </th>

                            <th>

                                NIP

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="280" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($data as $row)

        @php

            $badge = match($row->approval_status){

                'pending' => 'warning',

                'approved' => 'success',

                default => 'danger'

            };

            $label = match($row->approval_status){

                'pending' => 'Pending',

                'approved' => 'Valid',

                default => 'Ditolak'

            };

        @endphp

        <tr>

            <td class="text-center">

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $loop->iteration }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="avatar-circle me-3">

                        <i class="bi bi-person-badge-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $row->nama }}

                        </div>

                        <small class="text-muted">

                            Data Pegawai

                        </small>

                    </div>

                </div>

            </td>

            <td>

                {{ $row->nip ?? '-' }}

            </td>

            <td>

                <span class="badge bg-{{ $badge }} rounded-pill px-3 py-2">

                    @if($row->approval_status=='pending')

                        <i class="bi bi-hourglass-split me-1"></i>

                    @elseif($row->approval_status=='approved')

                        <i class="bi bi-check-circle-fill me-1"></i>

                    @else

                        <i class="bi bi-x-circle-fill me-1"></i>

                    @endif

                    {{ $label }}

                </span>

            </td>

            <td>

                <div class="d-flex justify-content-center gap-2 flex-wrap">

                    <a
                        href="{{ route('superadmin.datakepegawaian.show',$row->id) }}"
                        class="btn btn-info btn-sm rounded-pill px-3 text-white"
                    >

                        <i class="bi bi-eye-fill me-1"></i>

                        Detail

                    </a>

                    <form
                        action="{{ route('superadmin.datakepegawaian.approve',$row->id) }}"
                        method="POST"
                    >

                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="btn btn-success btn-sm rounded-pill px-3"
                        >

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Validasi

                        </button>

                    </form>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm rounded-pill px-3"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal{{ $row->id }}"
                    >

                        <i class="bi bi-x-circle-fill me-1"></i>

                        Tolak

                    </button>

                </div>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="5">

                <div class="text-center py-5">

                    <i class="bi bi-folder-x display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Data Kepegawaian

                    </h5>

                    <p class="text-muted mb-0">

                        Tidak terdapat data yang perlu divalidasi.

                    </p>

                </div>

            </td>

        </tr>

    @endforelse

</tbody>

</table>

</div>

</div>

</div>

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    letter-spacing:.5px;

    font-size:.82rem;

    font-weight:700;

    border:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr{

    transition:.2s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.avatar-circle{

    width:50px;

    height:50px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.15rem;

    flex-shrink:0;

}

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.btn{

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

.btn-success{

    border:none;

    background:linear-gradient(135deg,#16a34a,#15803d);

}

.btn-danger{

    border:none;

    background:linear-gradient(135deg,#dc2626,#b91c1c);

}

.btn-info{

    border:none;

    background:linear-gradient(135deg,#0ea5e9,#0284c7);

}

.modal-content{

    border:none;

    border-radius:20px;

}

.modal-header{

    border-bottom:1px solid #eef2f7;

}

.modal-footer{

    border-top:1px solid #eef2f7;

}

textarea.form-control{

    border-radius:14px;

}

textarea.form-control:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

@media(max-width:768px){

    .avatar-circle{

        width:44px;

        height:44px;

        font-size:1rem;

    }

}

</style>


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