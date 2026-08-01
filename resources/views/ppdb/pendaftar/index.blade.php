@extends('layouts.app')

@section('title', 'Data Pendaftar PPDB')

@section('content')

<div class="container-fluid">
{{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Daftar Pendaftar PPDB</h3>
                        <p class="mb-0 opacity-75">Kelola data pendaftar PPDB</p>
                    </div>
                    <div class="badge bg-white text-primary px-3 py-2 fs-6">
                        <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                    </div>
                </div>
            </div>
        </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- CARD --}}
    <x-table.card
        title="Daftar Pendaftar PPDB"
        subtitle="Seluruh pendaftar peserta didik baru pada SDN Mekar Jaya."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $data->count() }} Pendaftar
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <div class="d-flex flex-column align-items-end gap-2">
                <input
                    type="text"
                    id="searchInput"
                    class="form-control rounded-3"
                    placeholder="Cari Pendaftar..."
                    style="width: 320px;"
                >
            </div>

        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="180">No Pendaftaran</th>
                        <th>Nama</th>
                        <th width="150">Jenis Kelamin</th>
                        <th width="250">Tanggal Pendaftaran</th>
                        <th width="150">Status</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tablePPDB">
                    @foreach($data as $item)
                    <tr>
                        <td>
                            <span>
                                {{ $item->no_pendaftaran }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-lowercase">
                                        {{ $item->nama }}
                                    </div>
                                    <small class="text-muted">
                                       NIK : {{ $item->nik }}
                                    </small>
                                </div>
                            </div>
                            
                        </td>
                        
                        <td>
                            <span class="badge bg-teal rounded-pill px-3 py-2">
                                {{ $item->jenis_kelamin }}
                            </span>
                        </td>
                        <td>{{ $item->tanggal_pendaftaran }}</td>
                        <td>
                           <span class="badge bg-secondary rounded-pill px-3 py-2">
                                {{ strtoupper($item->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('tu.ppdb.pendaftar.show', $item) }}"
                            class="btn btn-primary btn-sm rounded-pill d-inline-flex align-items-center gap-2 px-3">
                                <i class="bi bi-eye-fill"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>   
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }} 
            </div>
        </div>
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
                '#tablePPDB tr'
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