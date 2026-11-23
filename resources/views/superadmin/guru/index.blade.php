@extends('layouts.app')

@section('title', 'Guru & Wali Kelas')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-person-workspace"
        eyebrow="Data Akademik / Guru"
        title="Guru & Wali Kelas"
        description="Kelola data guru dan wali kelas sekolah."
    />

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Guru"
        subtitle="Daftar seluruh guru pada SDN Mekar Jaya"
        :count="$gurus->count()"
    >

        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari guru"
                search-placeholder="Cari guru..."
            />
        </x-slot:toolbar>

        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <caption class="visually-hidden">Daftar seluruh guru</caption>

                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading>Nama Guru</x-table.heading>
                        <x-table.heading width="200px">Status Akademik</x-table.heading>
                        <x-table.heading width="500px">Mata Pelajaran</x-table.heading>
                        <x-table.heading width="100px">Total Kelas</x-table.heading>
                        <x-table.heading width="130px">Aksi</x-table.heading>
                    </tr>
                </thead>

                <tbody id="tableGuru">

                    @forelse($gurus as $guru)

                        <tr>

                            {{-- NO --}}
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- NAMA GURU --}}
                            <td>
                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width: 48px; height: 48px;"
                                    >
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

                            {{-- STATUS AKADEMIK --}}
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

                            {{-- MATA PELAJARAN --}}
                            <td>
                                <div class="fw-semibold small mb-1">
                                    {{ $guru->waliKelas ? 4 : $guru->pengampuMapel->filter(fn ($pengampu) => $pengampu->mapel)->unique('mapel_id')->count() }} Mapel
                                </div>                                
                            </td>
                            
                            {{-- KELAS --}}
                            <td>
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
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <a
                                    href="{{ route('superadmin.guru.show', $guru) }}"
                                    class="btn btn-primary btn-sm rounded-pill px-3 py-0"
                                >
                                    Detail
                                    <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </td>

                        </tr>

                    @empty

                        <x-table.empty
                            :colspan="6"
                            title="Belum ada data guru."
                            description=""
                        />

                    @endforelse

                </tbody>

            </table>
        </div>

    </x-table.card>

</div>

{{-- SEARCH --}}
<script>

    const searchInput = document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword = this.value.toLowerCase();

        const rows = document.querySelectorAll('#tableGuru tr');

        rows.forEach(row => {

            const text = row.innerText.toLowerCase();

            row.style.display = text.includes(keyword)
                ? ''
                : 'none';

        });

    });

</script>

@endsection