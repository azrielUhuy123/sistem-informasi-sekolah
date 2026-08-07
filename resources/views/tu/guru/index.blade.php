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
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
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
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $gurus->count() }} Guru
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <div class="d-flex flex-column align-items-end gap-2">
                <input
                    type="text"
                    id="searchInput"
                    class="form-control rounded-3"
                    placeholder="Cari guru..."
                    style="width: 320px;"
                >
            </div>

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
                            <td class="text-center">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>

                                    <div>
                                        <div class="fw-bold">
                                            {{ $guru->pegawai->nama }}
                                        </div>
 
                                        <small class="text-muted">
                                            NIP : {{ $guru->pegawai->nip ?? '-' }}
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($guru->waliKelas)
                                    <span class="badge bg-gold rounded-pill px-3 py-2">
                                        Wali Kelas
                                    </span>
                                @else
                                    <span class="badge bg-teal rounded-pill px-3 py-2">
                                        Guru Bidang
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($guru->waliKelas)
                                        @php
                                            $mapelInti = [
                                                'Bahasa Indonesia',
                                                'Matematika',
                                                'Pendidikan Pancasila',
                                                'Ilmu Pengetahuan Alam dan Sosial',
                                            ];
                                        @endphp
                                        @foreach($mapelInti as $mapel)
                                            <span class="badge bg-light text-dark border">
                                                {{ $mapel }}
                                            </span>
                                        @endforeach
                                    @else
                                        @php
                                            $mapels = $guru->pengampuMapel->unique('mapel_id');
                                        @endphp

                                        @forelse($mapels as $pengampu)
                                            <span class="badge bg-light text-dark border" title="{{ $pengampu->mapel->nama_mapel }}">
                                                {{ Str::limit($pengampu->mapel->nama_mapel, 22) }}
                                            </span>
                                        @empty
                                            <span class="text-muted">
                                                Belum ada mapel
                                            </span>
                                        @endforelse
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $kelasWali = $guru->waliKelas?->kelas;
                                        $kelasMengajar = $guru->pengampuMapel
                                            ->pluck('kelas')
                                            ->filter()
                                            ->unique('id');
                                    @endphp
                                    {{-- Kelas Wali --}}
                                    @if($kelasWali)
                                        <span class="badge bg-info text-dark fw-semibold rounded-pill px-3 py-2">
                                            {{ $kelasWali->nama_kelas_label }}
                                        </span>
                                    @endif
                                    {{-- Kelas Mengajar --}}    
                                    @foreach($kelasMengajar as $kelas)
                                        @if(!$kelasWali || $kelas->id != $kelasWali->id)
                                            <span class="badge bg-info text-dark fw-semibold rounded-pill px-3 py-2">
                                                {{ $kelas->nama_kelas_label }}
                                            </span>
                                        @endif
                                    @endforeach
                                    @if($kelasMengajar->isEmpty() && !$kelasWali)
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('tu.guru.show', $guru) }}"
                                class="btn btn-primary btn-sm rounded-pill d-inline-flex align-items-center gap-2 px-3">
                                    <i class="bi bi-eye-fill"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-person-x display-5 text-secondary"></i>
                                <p class="mt-3 mb-0 text-muted">
                                    Belum ada data guru.
                                </p>
                            </td>
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