@extends('layouts.app')

@section('title', 'Monitoring Kenaikan Kelas')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Monitoring Kenaikan Kelas</h3>
                    <p class="mb-0 opacity-75">Monitoring hasil pengajuan kenaikan kelas yang diajukan oleh wali kelas.</p>
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

    {{-- TOOLBAR --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center gap-3">                                               
                {{-- RIGHT --}}
                <div class="d-flex flex-wrap gap-2">
                    <div class="badge-status aktif">
                        <div class="me-2">
                            <span class="desc-add">Total Siswa</span> Naik Kelas: 
                        </div>{{ $jumlahNaik }}
                    </div> 
                    <div class="badge-status nonaktif">
                        <div class="me-2">
                            <span class="desc-add">Total Siswa</span> Tinggal Kelas : 
                        </div>{{ $jumlahTinggal }}
                    </div>                          
                </div>
                
                <div class="d-flex flex-wrap justify-content-center gap-2"> 
                    @if($approvalStatus == 'pending')                  
                        {{-- REJECT --}}
                        <button
                            class="btn btn-danger btn-sm rounded-3"
                            data-bs-toggle="modal"
                            data-bs-target="#rejectModal">                        
                            <i class="bi bi-x-circle me-1"></i>
                            Tolak
                        </button>
                        {{-- APPROVE --}}                        
                        <button
                            class="btn btn-success btn-sm rounded-3"
                            data-bs-toggle="modal"
                            data-bs-target="#finalApproveModal">                         
                            <i class="bi bi-check-circle me-1"></i>
                            Persetujuan Akhir
                        </button>                        
                    @endif       
                </div>
            </div>
        </div>
    </div>

    <x-table.card
        title="Kelas {{ $kelas->nama_kelas_label }}"
        subtitle="Wali Kelas : {{ $kelas->waliKelas?->guru?->pegawai?->nama ?? '-' }}"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $totalSiswa }} <span class="text-badge">Siswa</span>
            </span>  
            <span class="badge bg-success rounded-pill px-2 py-1">
                {{ $jumlahNaik }} <span class="text-badge">Naik Kelas</span>
            </span>           
            <span class="badge bg-danger rounded-pill px-2 py-1">
                {{ $jumlahTinggal }} <span class="text-badge">Tinggal Kelas</span>
            </span>                
            </span>            
        </x-slot:badge>         

        <div class="table-responsive">            
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="70">Absen</th>
                            <th>Nama Siswa</th>
                            <th>Keputusan</th>
                            <th>Catatan Wali Kelas</th>
                            <th width="150">Status Approval</th>
                            <th>Catatan Kepala Sekolah</th>                            
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keputusan as $item)
                            @php
                                $statusBadge = match ($item->approval_status ?? 'draft') {
                                    'pending' => 'badge-status pending',
                                    'rejected' => 'badge-status nonaktif',
                                    'approved' => 'badge-status aktif',
                                    default => 'badge-status draft',
                                };

                                $keputusanBadge = $item->status_keputusan === 'naik' ? 'bg-success' : 'bg-danger';
                            @endphp
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
                                                {{ $item->siswa->nama ?? '-' }}
                                            </div>
                                            <small class="text-muted">
                                                NIS : {{ $item->siswa->nis ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(($item->status ?? '') === 'naik')
                                        <span class="badge-status aktif">Naik Kelas</span>
                                    @elseif(($item->status ?? '') === 'tinggal')
                                        <span class="badge-status nonaktif">Tinggal Kelas</span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2">-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->catatan ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge {{ $statusBadge }} rounded-pill px-2 py-1">
                                        {{ match ($item->approval_status ?? 'draft') {
                                            'pending' => 'Menunggu Persetujuan',
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            default => 'Draft',
                                        } }}
                                    </span>
                                </td>
                                <td>
                                    {{ $item->catatan_kepsek ?? '-' }}
                                </td>                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="alert alert-light text-muted mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Belum ada data pengajuan kenaikan kelas.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>                       
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('superadmin.monitoring-kenaikan.index') }}" 
            class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-chevron-left me-1"></i> Kembali
            </a>    
        </div>
    </x-table.card>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tolak Pengajuan Kenaikan Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.monitoring-kenaikan.reject', $kelas) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="catatanKepsek" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="catatanKepsek" name="catatan_kepsek" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="finalApproveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Persetujuan Akhir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('superadmin.monitoring-kenaikan.approve', $kelas) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Persetujuan ini bersifat final. Siswa kelas I-V diproses sesuai keputusan. Keputusan siswa kelas VI dicatat untuk Penutupan Tahun Ajaran dan Kenaikan Kelas; penempatan siswa sebelumnya tetap berlaku sampai proses tersebut.
                    </div>
                    <div class="mb-3">
                        <label for="confirmation" class="form-label">Konfirmasi</label>
                        <input type="text" class="form-control" id="confirmation" name="confirmation" placeholder="Ketik NAIK KELAS" required>
                        <div class="form-text">Ketik NAIK KELAS untuk melanjutkan.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection