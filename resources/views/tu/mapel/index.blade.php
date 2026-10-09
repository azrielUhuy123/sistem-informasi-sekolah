@extends('layouts.app')

@section('title', 'Mata Pelajaran')

@section('content')

<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Mata Pelajaran</h3>
                    <p class="mb-0 opacity-75">Kelola Data Mata Pelajaran Sekolah.</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />
 
    <x-table.card title="Daftar Mata Pelajaran" subtitle="Mata pelajaran yang terdata di sekolah">
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $mata_pelajaran->count() }} <span class="text-badge">Mata Pelajaran</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <x-button.add
                :href="route('tu.mapel.create')"
                label="Tambah Mata Pelajaran"
            />            
        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th width="90">Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th width="90">Aksi</th>
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
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data mata pelajaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
</div>

@endsection