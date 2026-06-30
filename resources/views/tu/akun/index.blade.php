@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Akun</h3>
                    <p class="mb-0 opacity-75">Kelola akun pengguna sistem</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
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

    {{-- CARD --}}

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
                        Daftar Pengguna Sistem
                    </h5>

                    <span class="text-muted small">
                        Total:
                        {{ $data->count() }}
                        pengguna   
                    </span>

                </div>
                

                {{-- Filter + Search --}}
                <div class="d-flex flex-column align-items-end gap-2">

                    <form action="{{ route('tu.akun.index') }}" method="GET" class="d-flex gap-2 flex-wrap">

                        <select
                            name="role"
                            class="form-select rounded-3"
                            style="width: 160px;"
                        >
                            <option value="">Semua Role</option>
                            <option value="guru" @selected(request('role') == 'guru')>
                                Guru
                            </option>
                            <option value="siswa" @selected(request('role') == 'siswa')>
                                Siswa
                            </option>
                        </select>

                        <select
                            name="status"
                            class="form-select rounded-3"
                            style="width: 160px;"
                        >
                            <option value="">Semua Status</option>
                            <option value="1" @selected(request('status') === '1')>
                                Aktif
                            </option>
                            <option value="0" @selected(request('status') === '0')>
                                Nonaktif
                            </option>
                        </select>

                        <button class="btn btn-primary rounded-3" type="submit">
                            Filter
                        </button>

                    </form>

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control rounded-3"
                        placeholder="Cari pengguna..."
                        style="width: 320px;"
                    >

                </div>

            </div>

        </div>


        <div class="card-body">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Nama</th>

                            <th>Username</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody id="tablePengguna">

                        @forelse($data as $item)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}  

                                </td>

                                <td>

                                    {{ $item->name }}

                                </td>

                                <td>

                                    {{ $item->username }}

                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border">

                                        {{ strtoupper($item->role) }}

                                    </span>
                                </td>

                                <td>

                                    @if($item->is_active)

                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i> Aktif
                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">
                                            <i class="bi bi-x-circle me-1"></i> Nonaktif
                                        </span>

                                    @endif

                                </td>

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
                                                <a class="dropdown-item" href="{{ route('tu.akun.show',$item) }}">
                                                    <i class="bi bi-eye me-2"></i> Detail
                                                </a>
                                            </li>
                                            @if(in_array($item->role, ['guru', 'tu', 'siswa', 'superadmin']))

                                                <form
                                                    method="POST"
                                                    action="{{ route('tu.akun.reset-password', $item) }}"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item"
                                                        onclick="return confirm('Reset password akun ini?')"
                                                    >

                                                        <i class="bi bi-key me-2"></i>
                                                        Reset Password

                                                    </button>

                                                </form>

                                            @endif

                                            <li><hr class="dropdown-divider"></li>

                                            <li>
                                                <form
                                                    method="POST"
                                                    action="{{ route('tu.akun.toggle-status',$item) }}"
                                                >
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-person-x me-2"></i>
                                                        {{ $item->is_active
                                                            ? 'Nonaktifkan'
                                                            : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                                
                                            </li>

                                        </ul>

                                    </div>


                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center"
                                >

                                    Data tidak ditemukan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $data->links() }}

        </div>

    </div>

</div>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tablePengguna tr'
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
@endsection