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
        title="Daftar Pengguna Sistem"
        subtitle="Seluruh pengguna sistem yang terdaftar."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $data->total() }} <span class="text-badge">Pengguna</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <div class="d-flex flex-column align-items-center gap-2">
                <form action="{{ route('tu.akun.index') }}" method="GET" class="akun-filter-form">
                    <select
                        name="role"
                        class="form-control akun-filter-status"                        
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
                        class="form-control akun-filter-status"                        
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
                    class="form-control akun-filter-search"
                    placeholder="Cari pengguna..."                   
                >
            </div>

        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama & Username</th>
                        <th width="150">Role</th>
                        <th width="150">Status</th>
                        <th width="90">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="tablePengguna">
                    @forelse($data as $item)
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
                                            {{ $item->name }}
                                        </div>

                                        <small class="text-muted">
                                            Username : {{ $item->username }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <x-table.role-badge :role="$item->role" />                                 
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge-status aktif">
                                        <i class="bi bi-circle-fill me-2"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge-status nonaktif">
                                        <i class="bi bi-circle-fill me-2"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <x-table.actions>                                
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
                                                class="dropdown-item
                                                btn-confirm"
                                                data-title="RESET PASSWORD AKUN INI?"
                                                data-message="Akun ini passowrdnya akan direset kembali ke tanggal lahir."
                                                data-button="btn-danger"
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
                                            <button type="submit" class="dropdown-item text-danger btn-confirm"
                                            data-title="NONAKTIFKAN AKUN INI?"
                                            data-message="Akun ini akan dinonaktifkan dari pengguna sistem ini."
                                            data-button="btn-danger">
                                                <i class="bi bi-person-x me-2"></i>
                                                {{ $item->is_active
                                                    ? 'Nonaktifkan'
                                                    : 'Aktifkan' }}
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
                                    <h5 class="fw-bold mt-3">Belum Ada Pengguna</h5>
                                    <p class="text-muted mb-0">Saat ini belum ada pengguna dari sistem ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $data->links() }}
    </x-table.card>
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