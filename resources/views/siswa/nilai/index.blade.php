@extends('layouts.app')

@section('title', 'Nilai')

@section('content')

<div class="container-fluid py-3">
    @php
        $totalMapel = $nilai->count();
        $rataNilai = $nilai->count()
            ? round($nilai->avg('nilai_akhir'), 1)
            : 0;
        $nilaiTertinggi = $nilai->max('nilai_akhir') ?? 0;
    @endphp

    {{-- ================= HERO ================= --}}
    <x-hero.page
        title="Nilai Akademik"
        description="Rekapitulasi seluruh nilai akademik pada semester aktif,
                    meliputi nilai tugas, UTS, UAS, dan nilai akhir setiap mata pelajaran.">
        
        <div class="row g-3">
            <div class="col-4">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-primary mb-1">{{ $totalMapel }}</h4>
                    <small class="text-muted">Mapel</small>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-success mb-1">{{ $rataNilai }}</h4>
                    <small class="text-muted">Rata-rata</small>
                </div>
            </div>
            <div class="col-4">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-warning mb-1">{{ $nilaiTertinggi }}</h4>
                    <small class="text-muted">Tertinggi</small>
                </div>
            </div>
        </div>

    </x-hero.page>

    {{-- ================= TABLE ================= --}}
    <x-table.card
        title="Rekap Nilai Semester"
        subtitle="Seluruh nilai mata pelajaran pada semester yang sedang berjalan."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $totalMapel }} Mata Pelajaran
            </span>
        </x-slot:badge>

        @if($nilai->count())
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="70" class="text-center">No</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th class="text-center" width="110">Tugas</th>
                            <th class="text-center" width="110">UTS</th>
                            <th class="text-center" width="110">UAS</th>
                            <th class="text-center" width="140">Nilai Akhir</th>
                            <th class="text-center" width="120">Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nilai as $i => $n)
                            @php
                                $akhir = $n->nilai_akhir;

                                if ($akhir >= 90) {
                                    $predikat = 'A';
                                    $warna = 'success';
                                } elseif ($akhir >= 80) {
                                    $predikat = 'B';
                                    $warna = 'primary';
                                } elseif ($akhir >= 70) {
                                    $predikat = 'C';
                                    $warna = 'warning';
                                } else {
                                    $predikat = 'D';
                                    $warna = 'danger';
                                }
                            @endphp

                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark rounded-circle">
                                        {{ $i + 1 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mapel-icon me-3">
                                            <i class="bi bi-book-fill"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $n->mapel->nama_mapel }}</div>
                                            <small class="text-muted">Mata Pelajaran</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $n->guru->pegawai->nama ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        {{ $n->nilai_tugas }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info rounded-pill px-3 py-2">
                                        {{ $n->nilai_uts }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                        {{ $n->nilai_uas }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $warna }} rounded-pill px-3 py-2">
                                        {{ $akhir }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $warna }} rounded-pill px-3 py-2">
                                        {{ $predikat }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-journal-x display-3 text-secondary"></i>
                <h5 class="fw-bold mt-3">Belum Ada Nilai</h5>
                <p class="text-muted mb-0">
                    Nilai akan muncul setelah guru menyelesaikan proses penilaian.
                </p>
            </div>
        @endif
    </x-table.card>
</div>

<style>
    .mapel-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        background: linear-gradient(135deg, #2563eb, #0f4c81);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    @media (max-width: 768px) {
        .mapel-icon {
            width: 42px;
            height: 42px;
            font-size: 1rem;
        }
    }
</style>

@endsection
