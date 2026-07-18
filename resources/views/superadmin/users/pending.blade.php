@extends('layouts.app')

@section('title', 'User Aktif')

@section('content')
<div class="container-fluid py-3">

    @php

        $totalUser = $users->total();

        $aktif = $users->where('is_active', true)->count();

        $blokir = $users->where('is_active', false)->count();

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

                        Approval User

                    </h2>

                    <p class="mb-0 opacity-75">

                        Kelola akun pengguna yang telah disetujui,
                        aktifkan kembali akun yang diblokir,
                        serta lakukan monitoring status seluruh pengguna sistem.

                    </p>

                </div>

                <div class="col-lg-5 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-4">

                            <div class="bg-white rounded-4 p-3 text-center h-100">

                                <h3 class="fw-bold text-primary mb-1">

                                    {{ $totalUser }}

                                </h3>

                                <small class="text-muted">

                                    Total

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 p-3 text-center h-100">

                                <h3 class="fw-bold text-success mb-1">

                                    {{ $aktif }}

                                </h3>

                                <small class="text-muted">

                                    Aktif

                                </small>

                            </div>

                        </div>

                        <div class="col-4">

                            <div class="bg-white rounded-4 p-3 text-center h-100">

                                <h3 class="fw-bold text-danger mb-1">

                                    {{ $blokir }}

                                </h3>

                                <small class="text-muted">

                                    Blokir

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h5 class="fw-bold mb-1">

                        Daftar User Aktif

                    </h5>

                    <small class="text-muted">

                        Kelola status akun pengguna yang telah memperoleh akses ke sistem.

                    </small>

                </div>

            </div>

        </div>

        <div class="card-body">

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

                        <th>

                            Tanggal Approve

                        </th>

                        <th width="180" class="text-center">

                            Aksi

                        </th>

                    </tr>

                </thead>

                <tbody>
                    @forelse($users as $user)

                        <tr>

                            <td class="text-center">

                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                                    {{ $loop->iteration }}

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

                                <span class="badge bg-{{ $roleColor }} rounded-pill px-3 py-2">

                                    {{ ucfirst($user->role) }}

                                </span>

                            </td>

                            <td>

                                @if($user->is_active)

                                    <span class="badge bg-success rounded-pill px-3 py-2">

                                        <i class="bi bi-check-circle-fill me-1"></i>

                                        Aktif

                                    </span>

                                @else

                                    <span class="badge bg-danger rounded-pill px-3 py-2">

                                        <i class="bi bi-slash-circle-fill me-1"></i>

                                        Diblokir

                                    </span>

                                @endif

                            </td>

                            <td>

                                <i class="bi bi-calendar-event me-2 text-primary"></i>

                                {{ $user->approved_at?->format('d M Y H:i') ?? '-' }}

                            </td>

                            <td class="text-center">

                                @if($user->is_active)

                                    <form
                                        action="{{ route('superadmin.users.block',$user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Blokir user ini?')"
                                    >

                                        @csrf

                                        <button
                                            class="btn btn-danger rounded-pill btn-sm px-4"
                                            type="submit"
                                        >

                                            <i class="bi bi-lock-fill me-1"></i>

                                            Blokir

                                        </button>

                                    </form>

                                @else

                                    <form
                                        action="{{ route('superadmin.users.unblock',$user) }}"
                                        method="POST"
                                        onsubmit="return confirm('Aktifkan kembali user ini?')"
                                    >

                                        @csrf

                                        <button
                                            class="btn btn-success rounded-pill btn-sm px-4"
                                            type="submit"
                                        >

                                            <i class="bi bi-unlock-fill me-1"></i>

                                            Aktifkan

                                        </button>

                                    </form>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5"
                            >

                                <i class="bi bi-inbox display-5 text-secondary"></i>

                                <h5 class="mt-3">

                                    Tidak Ada User

                                </h5>

                                <p class="text-muted mb-0">

                                    Belum terdapat data user.

                                </p>

                            </td>

                        </tr>

                        @endforelse
                </tbody>
            </table>

            <div class="mt-3">
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

    box-shadow:0 16px 45px rgba(37,99,235,.08)!important;

}

.table{

    margin-bottom:0;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    letter-spacing:.5px;

    font-size:.82rem;

    font-weight:700;

    border-bottom:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr{

    transition:.25s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.avatar-circle{

    width:48px;

    height:48px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

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

.btn-danger{

    background:linear-gradient(135deg,#dc2626,#b91c1c);

    border:none;

}

.pagination{

    justify-content:center;

}

.page-link{

    border:none;

    margin:0 4px;

    border-radius:10px!important;

    color:#2563eb;

}

.page-item.active .page-link{

    background:#2563eb;

}

.page-link:hover{

    background:#eff6ff;

    color:#2563eb;

}

@media(max-width:768px){

    .table{

        font-size:.92rem;

    }

    .avatar-circle{

        width:42px;

        height:42px;

    }

}

</style>
@endsection