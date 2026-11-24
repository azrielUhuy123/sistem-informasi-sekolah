@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-person-gear"
        eyebrow="Administrasi / Akun"
        title="Manajemen Akun"
        description="Kelola akun pengguna sistem."
    />
    
    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>
 
    {{-- CARD --}}
    <x-table.card
        title="Daftar Pengguna Sistem"
        subtitle="Seluruh pengguna sistem yang terdaftar."
        :count="$data->total()"
        count-label="data"
    >
        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari pengguna"
                search-placeholder="Cari pengguna..."
            >
                <form action="{{ route('tu.akun.index') }}" method="GET" class="akun-filter-form">
                    <select name="role" class="form-control akun-filter-status" aria-label="Filter role pengguna">
                        <option value="">Semua Role</option>
                        <option value="guru" @selected(request('role') == 'guru')>Guru</option>
                        <option value="siswa" @selected(request('role') == 'siswa')>Siswa</option>
                    </select>
                    <select name="status" class="form-control akun-filter-status" aria-label="Filter status akun">
                        <option value="">Semua Status</option>
                        <option value="1" @selected(request('status') === '1')>Aktif</option>
                        <option value="0" @selected(request('status') === '0')>Nonaktif</option>
                    </select>
                    <button class="btn btn-primary dt-toolbar__button" type="submit">Filter</button>
                </form>
            </x-table.local-toolbar>
        </x-slot:toolbar>

        <x-table.chips
            :criteria="[
                [
                    'name' => 'role',
                    'label' => 'Role',
                    'value' => request('role'),
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'value' => request('status'),
                    'display' => request('status') === '1'
                        ? 'Aktif'
                        : (request('status') === '0' ? 'Nonaktif' : request('status')),
                ],
            ]"
        />

        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading>Nama &amp; Username</x-table.heading>
                        <x-table.heading width="150px">Role</x-table.heading>
                        <x-table.heading width="150px">Status</x-table.heading>
                        <x-table.heading width="90px">Aksi</x-table.heading>
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
                                    @if(in_array($item->role, ['guru', 'siswa'], true))
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
                                                data-message="Akan membuat tautan reset satu kali yang harus diberikan kepada pemilik akun melalui kanal aman."
                                                data-button="btn-danger"
                                            >
                                                <i class="bi bi-key me-2"></i>
                                                Buat Tautan Reset Password
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
                        @if (request()->filled('role') || request()->filled('status'))
                            <x-table.empty
                                :colspan="5"
                                state="no-results"
                                title="Tidak ada pengguna yang cocok"
                                description="Ubah role atau status, lalu coba lagi."
                            />
                        @else
                            <x-table.empty
                                :colspan="5"
                                title="Belum ada pengguna"
                                description="Saat ini belum ada pengguna dari sistem ini."
                            />
                        @endif
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-table.pagination :paginator="$data->appends(request()->query())" :show-summary="true" />
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