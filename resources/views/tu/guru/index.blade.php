@extends('layouts.app')

@section('title', 'Guru & Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Guru & Wali Kelas</h3>
                    <p class="mb-0 opacity-75">Kelola data guru dan wali kelas sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Guru"
        subtitle="Daftar seluruh guru pada SDN Mekar Jaya"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $gurus->count() }} <span class="text-badge">Guru</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>        
            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari guru..."                
            >
        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama Guru</th>
                        <th width="200">Status Akademik</th>
                        <th width="200">Mata Pelajaran</th>
                        <th width="150">Kelas</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableGuru">
                    @forelse($gurus as $guru)
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
                                            {{ $guru->pegawai?->nama ?? $guru->nama ?? '-' }}
                                        </div>
 
                                        <small class="text-muted">
                                            NIP : {{ $guru->pegawai?->nip ?? $guru->nip ?? '-' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($guru->waliKelas)
                                    <span class="text-gold fw-semibold">
                                        Wali Kelas
                                    </span>
                                @else
                                    <span class="text-teal fw-semibold">
                                        Guru Bidang
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold small mb-1">
                                    {{ $guru->waliKelas ? 4 : $guru->pengampuMapel->filter(fn ($pengampu) => $pengampu->mapel)->unique('mapel_id')->count() }} Mapel
                                </div>  
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">                                    
                                    {{-- Kelas Wali --}}
                                    @php
                                        $kelasWali = $guru->waliKelas?->kelas;
                                        $kelasMengajar = $guru->pengampuMapel
                                            ->pluck('kelas')
                                            ->filter()
                                            ->unique('id');

                                        $semuaKelas = collect();
                                        if ($kelasWali) {
                                            $semuaKelas->push($kelasWali);
                                        }
                                        foreach ($kelasMengajar as $kelas) {
                                            if (!$kelasWali || $kelas->id != $kelasWali->id) {
                                                $semuaKelas->push($kelas);
                                            }
                                        }
                                        $jumlahKelas = $semuaKelas->count();
                                    @endphp
                                    @if($jumlahKelas == 0)
                                        <span class="badge-status selesai">
                                            0 kelas
                                        </span>
                                    @elseif($jumlahKelas >= 15)
                                        <span class="badge-status nonaktif">
                                            {{ $jumlahKelas }} Kelas
                                        </span>
                                    @elseif($jumlahKelas >= 8)
                                        <span class="badge-status pending">
                                            {{ $jumlahKelas }} Kelas
                                        </span>
                                    @else
                                        <span class="badge-status aktif">
                                            {{ $jumlahKelas }} Kelas
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('tu.guru.show', $guru) }}"
                                class="btn btn-primary btn-sm rounded-pill px-3 py-0">
                                    Detail <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data guru</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>

</div>
<style>
    .guru-icon{
        width:50px;
        height:50px;
        border-radius:16px;
        background:linear-gradient(135deg,#2563eb,#0f4c81);
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.2rem;
        flex-shrink:0;
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
                '#tableGuru tr'
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