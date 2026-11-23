@extends('layouts.app')

@section('title', 'Ekskul')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-trophy"
        eyebrow="Kegiatan / Ekstrakurikuler"
        title="Manajemen Ekskul"
        description="Kelola kegiatan ekstrakurikuler sekolah."
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Ekstrakurikuler"
        subtitle="Seluruh kegiatan ekstrakurikuler."
        :count="$ekskuls->count()"
        count-label="data"
    >

        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari ekstrakurikuler"
                search-placeholder="Cari ekskul..."
            >
                <x-button.add
                    :href="route('tu.ekskul.create')"
                    label="Tambah Ekskul"
                />
            </x-table.local-toolbar>
        </x-slot:toolbar>

        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading>Nama Ekskul</x-table.heading>
                        <x-table.heading width="450px">Pembina</x-table.heading>
                        <x-table.heading width="150px">Jadwal</x-table.heading>
                        <x-table.heading width="150px">Kuota</x-table.heading>
                        <x-table.heading width="150px">Status</x-table.heading>
                        <x-table.heading width="90px">Aksi</x-table.heading>
                    </tr>
                </thead>
                <tbody id="tableEkskul">
                    @forelse($ekskuls as $i => $e)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">   
                                                                
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $e->nama }}
                                        </div>

                                        <small class="text-muted">
                                            {{ Str::limit($e->deskripsi, 50) }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>     
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $e->guru->pegawai->nama ?? '-' }}
                                        </div>
                                        <small class="text-muted">Pembina</small>
                                    </div>
                                </div
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $e->hari ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($e->jam_mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($e->jam_selesai)->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark fw-semibold rounded-pill px-3 py-2">
                                    {{ $e->kuota }}
                                </span>
                            </td>
                            <td>
                                @if($e->status == 'aktif')
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
                                        <a class="dropdown-item" href="{{ route('tu.ekskul.edit', $e->id) }}">
                                            <i class="bi bi-pencil-square me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('tu.ekskul.destroy', $e->id) }}"
                                            method="POST">
                                            
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger
                                            text-danger
                                            btn-confirm"
                                            data-title="HAPUS EKSKUL INI?"
                                            data-message="Ekskul ini akan dihapus dari sistem ini."
                                            data-button="btn-danger">                                
                                                <i class="bi bi-trash me-2"></i> Hapus
                                            </button>
                                        </form>
                                    </li>                                
                                </x-table.actions>  
                            </td>
                        </tr>
                    @empty
                        <x-table.empty
                            :colspan="7"
                            title="Belum ada data ekstrakurikuler"
                            description="Data ekstrakurikuler belum tersedia."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
</div>
<style>
    .ekskul-icon{
        width:50px;height:50px;border-radius:16px;display:flex;align-items:center;justify-content:center;
        background:linear-gradient(135deg,#2563eb,#0f4c81);color:#fff;font-size:1.2rem;flex-shrink:0;
    }

</style>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tableEkskul tr'
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