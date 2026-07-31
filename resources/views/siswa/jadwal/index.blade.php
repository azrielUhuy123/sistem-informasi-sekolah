@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
    <div class="container-fluid py-3">
        @php
            $totalHari = $hari->count();
            $totalJam = $jam->count();
            $totalJadwal = collect($grid)
                ->flatten()
                ->filter()
                ->count();
        @endphp

        {{-- ================= HERO ================= --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div
                class="card-body p-4 p-lg-5 text-white"
                style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
            >
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">Jadwal Pelajaran</h2>
                        <p class="mb-0 opacity-75">
                            Jadwal resmi pembelajaran untuk
                            <strong>{{ $penempatan->kelas->nama_kelas ?? '-' }}</strong>
                            selama satu minggu.
                        </p>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="bg-white rounded-4 p-3 text-center">
                                    <h4 class="fw-bold text-primary mb-1">{{ $totalHari }}</h4>
                                    <small class="text-muted">Hari</small>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="bg-white rounded-4 p-3 text-center">
                                    <h4 class="fw-bold text-success mb-1">{{ $totalJam }}</h4>
                                    <small class="text-muted">Jam</small>
                                </div>
                            </div>

                            <div class="col-4">
                                <div class="bg-white rounded-4 p-3 text-center">
                                    <h4 class="fw-bold text-warning mb-1">{{ $totalJadwal }}</h4>
                                    <small class="text-muted">Mapel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= CARD ================= --}}
        <x-table.card
            title="Jadwal Mingguan"
            subtitle="Seluruh mata pelajaran berdasarkan hari dan jam pelajaran."
        >
            <x-slot:badge>
                <span class="badge bg-primary rounded-pill px-3 py-2">
                    {{ $penempatan->kelas->nama_kelas ?? '-' }}
                </span>
            </x-slot:badge>

            <div class="alert alert-light border rounded-4 mb-4">
                <div class="row text-center">
                    <div class="col-md-4">
                            <i class="bi bi-book-fill text-primary me-2"></i>
                        Mata Pelajaran
                    </div>
                    <div class="col-md-4">
                        <i class="bi bi-person-video3 text-success me-2"></i>
                        Guru Pengampu
                    </div>
                    <div class="col-md-4">
                        <i class="bi bi-clock-fill text-warning me-2"></i>
                        Jam Pelajaran
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center jadwal-table mb-0">
                    <thead>
                        <tr>
                            <th width="140">
                                <i class="bi bi-clock-fill me-2"></i>
                                Jam
                            </th>
                            @foreach ($hari as $h)
                                <th>{{ $h->nama }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody>
                        @foreach ($jam as $jp)
                            <tr>
                                {{-- JAM --}}
                                <td class="bg-light fw-bold">
                                    <div class="d-flex flex-column">
                                        <span>{{ substr($jp->mulai, 0, 5) }}</span>
                                        <small class="text-muted">s/d</small>
                                        <span>{{ substr($jp->selesai, 0, 5) }}</span>
                                    </div>
                                </td>
                                @foreach ($hari as $h)
                                    @php
                                        $jadwal = $grid[$jp->id][$h->id] ?? null;
                                    @endphp

                                        <td style="min-width:190px;">
                                        @if ($jadwal)
                                            <div class="jadwal-item">
                                                <div class="jadwal-mapel">
                                                    {{ $jadwal->mapel->nama_mapel }}
                                                </div>
                                                <div class="jadwal-guru">
                                                    <i class="bi bi-person-fill me-1"></i>
                                                    {{ $jadwal->guru->pegawai->nama ?? '-' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="jadwal-kosong">
                                                <i class="bi bi-dash-circle"></i>
                                                <br>
                                                Tidak Ada
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-table.card>
       
    </div>

    <style>

        .jadwal-item {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 16px;
            padding: 14px;
            transition: .2s;
            min-height: 90px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .jadwal-item:hover {
            background: #dbeafe;
            transform: translateY(-2px);
        }

        .jadwal-mapel {
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 6px;
        }

        .jadwal-guru {
            color: #475569;
            font-size: .9rem;
        }

        .jadwal-kosong {
            color: #94a3b8;
            padding: 18px 8px;
            font-size: .9rem;
        }

        .alert {
            background: #f8fbff;
        }

        @media (max-width: 992px) {
            .jadwal-item {
                min-width: 170px;
            }
        }
    </style>
@endsection