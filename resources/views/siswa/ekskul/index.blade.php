@extends('layouts.app')

@section('title', 'Ekskul Saya')

@section('content')

<div class="container-fluid py-4">

    @php
    $totalEkskul = $ekskul->count();
    $aktif = $ekskul->where('status','aktif')->count();
    $nonAktif = $ekskul->where('status','!=','aktif')->count();
    @endphp

    {{-- ================= HERO ================= --}}
    <x-hero.page
        title="Ekstrakurikuler Saya"
        description="Lihat seluruh kegiatan ekstrakurikuler yang sedang diikuti,
                    lengkap dengan pembina, status keanggotaan, dan akses cepat
                    menuju detail maupun absensi.">
        
        <div class="row g-3">
            <div class="col-4">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-primary mb-1">{{ $totalEkskul }}</h4>
                    <small class="text-muted">Ekskul</small>
                </div>
            </div>

            <div class="col-4">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-success mb-1">{{ $aktif }}</h4>
                    <small class="text-muted">Aktif</small>
                </div>
            </div>

            <div class="col-4">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-secondary mb-1">{{ $nonAktif }}</h4>
                    <small class="text-muted">Nonaktif</small>
                </div>
            </div>
        </div>

    </x-hero.page>

    {{-- ================= TABLE ================= --}}
    <x-table.card
        title="Daftar Ekstrakurikuler"
        subtitle="Seluruh kegiatan ekstrakurikuler yang sedang Anda ikuti."
    > 
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $totalEkskul }} Ekskul
            </span>
        </x-slot:badge>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70" class="text-center">No</th>
                        <th>Ekstrakurikuler</th>
                        <th>Pembina</th>
                        <th width="140">Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ekskul as $item)
                    @php $aktif = $item->status == 'aktif'; @endphp

                    <tr>
                        <td>
                            <span class="badge bg-light text-dark rounded-circle">{{ $loop->iteration }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="ekskul-icon me-3"><i class="bi bi-trophy-fill"></i></div>
                                <div>
                                    <div class="fw-bold">{{ $item->ekskul->nama }}</div>
                                    <small class="text-muted">Ekstrakurikuler</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->ekskul->pembina->guru->pegawai->nama ?? '-' }}</div>
                            <small class="text-muted">Pembina</small>
                        </td>
                        <td>
                            @if($aktif)
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Aktif
                            </span>
                            @else
                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                <i class="bi bi-pause-circle-fill me-1"></i>
                                Tidak Aktif
                            </span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('siswa.ekskul.show',$item->ekskul_id) }}" class="btn btn-outline-primary rounded-pill btn-sm">
                                    <i class="bi bi-eye-fill me-1"></i>
                                    Detail
                                </a>

                                <a href="{{ route('siswa.ekskul.absensi',$item->ekskul_id) }}" class="btn btn-primary rounded-pill btn-sm">
                                    <i class="bi bi-calendar-check-fill me-1"></i>
                                    Absensi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="text-center py-5">
                                <i class="bi bi-trophy display-3 text-secondary"></i>
                                <h5 class="fw-bold mt-3">Belum Mengikuti Ekstrakurikuler</h5>
                                <p class="text-muted mb-0">Anda belum terdaftar pada kegiatan ekstrakurikuler mana pun.</p>
                            </div>
                        </td>
                    </tr>
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
    .btn{font-weight:600;}
    .btn-primary{box-shadow:0 8px 20px rgba(37,99,235,.20);}
    .btn-primary:hover{transform:translateY(-2px);}
    .btn-outline-primary:hover{transform:translateY(-2px);}

    @media(max-width:768px){
        .ekskul-icon{width:42px;height:42px;font-size:1rem;}
    }
</style>

@endsection