@extends('layouts.app')

@section('title', 'Jadwal Ekskul')

@section('content')

<div class="container-fluid">

    @php
        $jadwalCount = $jadwal->count();
        $hariCount = $hari->count();
        $aktifCount = $jadwal->where('status', 'aktif')->count();
    @endphp

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Jadwal Ekstrakurikuler</h3>
                    <p class="mb-0 opacity-75">Kelola jadwal kegiatan ekstrakurikuler, waktu pelaksanaan, dan seluruh agenda pembinaan siswa.</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i>
                    Sinergi-MJ
                </div>
            </div>                                         
        </div>
    </div>

    <x-alert.flash key="success" type="success"/>
    <x-alert.flash key="error" type="danger"/>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body pt-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Tambah Jadwal Baru</h5>
                    <small class="text-muted">Pilih hari dan rentang waktu kegiatan ekstrakurikuler.</small>
                </div>                
            </div>

            <form action="{{ route('guru.ekskul.jadwal.store') }}" method="POST">
                @csrf

                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Hari</label>
                        <select name="hari_id" class="form-select" required>
                            @foreach($hari as $h)
                                <option value="{{ $h->id }}">{{ $h->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>

                    <div class="col-lg-2 col-md-6 text-end">
                        <button class="btn btn-primary px-4 py-2 rounded-3">
                            Simpan <i class="bi bi-save ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-table.card
        title="Daftar Jadwal"
        subtitle="Riwayat jadwal yang telah ditambahkan."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $jadwalCount }} <span class="text-badge">Jadwal</span>
            </span>
        </x-slot:badge>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $j)
                            <tr>
                                <td>
                                    <span class="fw-semibold small text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $j->hari->nama }}
                                    </span>                                    
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</div>
                                    <div class="small text-muted">Waktu pelaksanaan</div>
                                </td>
                                <td>                                    
                                    @if($j->status === 'approved')                                        
                                        <span class="badge-status aktif">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            Disetujui
                                        </span>
                                    @elseif($j->status === 'submitted')                                        
                                        <span class="badge-status pending">
                                            <i class="bi bi-hourglass-split me-2"></i>
                                            Pending
                                        </span>
                                    @elseif($j->status === 'rejected')                                        
                                        <span class="badge-status nonaktif">
                                            <i class="bi bi-x-circle-fill me-2"></i>
                                            Ditolak
                                        </span>
                                    @elseif($j->status === 'published')                                        
                                        <span class="badge-status verifikasi">
                                            <i class="bi bi-send-check-fill me-2"></i>
                                            Publish
                                        </span>
                                    @elseif($j->status === 'draft')                                        
                                        <span class="badge-status pending">
                                            <i class="bi bi-hourglass-split me-2"></i>
                                            Pending
                                        </span>
                                    @else
                                        <span class="badge-status draft">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($j->status == 'published')
                                        <span class="text-primary small">
                                            <i class="bi bi-send-check-fill me-2"></i>
                                            Sudah Publish
                                        </span>
                                    @elseif ($j->status == 'approved')
                                        <span class="text-success small">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            Sudah Diterima
                                        </span>
                                    @else
                                        <x-table.actions>
                                            <li>
                                                <a
                                                    class="dropdown-item"
                                                    href="{{ route('guru.ekskul.jadwal.edit', $j->id) }}"
                                                >
                                                    <i class="bi bi-pencil-square me-2"></i>
                                                    Edit
                                                </a>                                            
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('guru.ekskul.jadwal.destroy', $j->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE') 
                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger btn-confirm"
                                                        data-title="HAPUS JADWAL?"
                                                        data-message="Jadwal ini akan dihapus secara permanen."
                                                        data-button="btn-danger"
                                                    >
                                                        <i class="bi bi-trash3 me-2"></i>Delete
                                                    </button>
                                                </form>                                               
                                            </li>
                                        </x-table.actions>                                    
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-calendar-x display-4 text-secondary"></i>
                                        <h5 class="fw-bold mt-3">Belum Ada Jadwal</h5>
                                        <p class="text-muted mb-0">Silakan tambahkan jadwal ekstrakurikuler terlebih dahulu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </x-table.card>

</div>

@endsection