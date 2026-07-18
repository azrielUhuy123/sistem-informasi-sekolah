@extends('layouts.app')

@section('title', 'User Ditolak')

@section('content')
<div class="container-fluid py-3">

    @php

        $totalUser = $users->total();

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        User Ditolak

                    </h2>

                    <p class="mb-0 opacity-75">

                        Daftar pengguna yang pengajuan akunnya telah ditolak.
                        Administrator dapat menyetujui kembali akun apabila diperlukan.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="bg-white rounded-4 p-4 text-center">

                        <h2 class="fw-bold text-danger mb-1">

                            {{ $totalUser }}

                        </h2>

                        <small class="text-muted">

                            Total User Ditolak

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar User Ditolak

                    </h5>

                    <small class="text-muted">

                        Seluruh akun yang ditolak selama proses approval pengguna.

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

                                User

                            </th>

                            <th>

                                Role

                            </th>

                            <th>

                                Status

                            </th>

                            <th width="180" class="text-center">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

    @forelse($users as $user)

        @php

            $roleColor = match($user->role){

                'superadmin' => 'danger',

                'admin' => 'primary',

                'tu' => 'warning',

                'guru' => 'success',

                'siswa' => 'secondary',

                default => 'dark'

            };

        @endphp

        <tr>

            <td class="text-center">

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $loop->iteration + ($users->firstItem() ?? 0) - 1 }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="avatar-circle me-3">

                        <i class="bi bi-person-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $user->name }}

                        </div>

                        <small class="text-muted">

                            {{ '@'.$user->username }}

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-{{ $roleColor }} rounded-pill px-3 py-2">

                    {{ ucfirst($user->role) }}

                </span>

            </td>

            <td>

                <span class="badge bg-danger rounded-pill px-3 py-2">

                    <i class="bi bi-x-circle-fill me-1"></i>

                    Ditolak

                </span>

            </td>

            <td class="text-center">

                <form
                    action="{{ route('superadmin.users.approve', $user) }}"
                    method="POST"
                    onsubmit="return confirm('Setujui user ini?')"
                >

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="btn btn-success btn-sm rounded-pill px-4"
                    >

                        <i class="bi bi-check-circle-fill me-1"></i>

                        Setujui

                    </button>

                </form>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="5">

                <div class="text-center py-5">

                    <i class="bi bi-person-x display-3 text-danger"></i>

                    <h5 class="fw-bold mt-3">

                        Tidak Ada User Ditolak

                    </h5>

                    <p class="text-muted mb-0">

                        Belum terdapat akun yang berstatus ditolak.

                    </p>

                </div>

            </td>

        </tr>

    @endforelse

</tbody>

</table>

</div>

<div class="px-4 py-3 border-top">

    {{ $users->links() }}

</div>

</div>

</div>

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(220,38,38,.08)!important;

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

    background:#fff7f7;

}

.avatar-circle{

    width:48px;

    height:48px;

    border-radius:50%;

    background:linear-gradient(135deg,#dc2626,#991b1b);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.1rem;

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

    background:linear-gradient(135deg,#16a34a,#15803d);

    border:none;

}

.pagination{

    justify-content:center;

}

.page-link{

    border:none;

    margin:0 4px;

    border-radius:10px!important;

    color:#dc2626;

}

.page-item.active .page-link{

    background:#dc2626;

}

.page-link:hover{

    background:#fef2f2;

    color:#dc2626;

}

@media(max-width:768px){

    .avatar-circle{

        width:42px;

        height:42px;

    }

}

</style>

@endsection