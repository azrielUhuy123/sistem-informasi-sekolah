@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="container-fluid dt-siswa-page">
    <x-table.page-header
        icon="bi-book-half"
        eyebrow="Master Data / Mata Pelajaran"
        title="Mata Pelajaran"
        description="Daftar dan informasi mata pelajaran sekolah."
    />

    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />
 
    <x-table.card
        title="Daftar Mata Pelajaran"
        subtitle="Mata pelajaran yang terdata di sekolah"
        :count="$mata_pelajaran->count()"
        count-label="data"
    >

        <x-slot:pageActions>
            <x-button.add
                :href="route('tu.mapel.create')"
                label="Tambah Mata Pelajaran"
            />            
        </x-slot:pageActions>

        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px" class="text-center">No</x-table.heading>
                        <x-table.heading width="90px">Kode</x-table.heading>
                        <x-table.heading>Nama Mata Pelajaran</x-table.heading>
                        <x-table.heading width="90px">Aksi</x-table.heading>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mata_pelajaran as $row)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill px-2 py-1">
                                    {{ $row->kode_mapel ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-book-half text-primary me-2"></i>
                                    {{ $row->nama_mapel }}
                                </div>
                            </td>
                            <td>
                                <x-table.actions>                                
                                    <li>
                                        <a class="dropdown-item" href="{{ route('tu.mapel.edit', $row->id) }}">
                                            <i class="bi bi-pencil-square me-2"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('tu.mapel.destroy', $row->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                            class="dropdown-item text-danger btn-confirm"
                                            data-title="Hapus Mata Pelajaran"
                                            data-message="Mata Pelajaran akan dihapus dari sistem ini."
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
                            :colspan="4"
                            title="Belum ada data mata pelajaran"
                            description="Data mata pelajaran belum tersedia."
                        />
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
</div>

@endsection