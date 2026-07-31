@extends('layouts.app')

@section('title', 'Detail Rombel')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Kelas {{ $rombel->nama_kelas }}</h3>
                    <p class="mb-0 opacity-75">Angkatan {{ $rombel->kelasGrup->angkatan->tahun }}</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    <x-table.card
        title="Daftar Siswa {{ $rombel->nama_kelas }}"
        subtitle="{{ $totalLaki }} Pria - {{ $totalPerempuan }} Perempuan"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $totalSiswa }} Siswa
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <a href="{{ route('tu.penempatan-siswa.create', ['kelas_id' => $rombel->id]) }}"
            class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Tambah Siswa ke Rombel
            </a>
        </x-slot:actions>
        <div class="table-responsive">
            @if($siswa->count())
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="70">Absen</th>
                            <th>Nama Siswa</th>
                            <th width="150">NISN</th>
                            <th width="150">Jenis Kelamin</th>
                            <th width="150">Tanggal Lahir</th>
                            <th width="180">Wali</th>
                            <th width="90">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $item)
                            <tr>
                                <td>
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
                                                {{ $item->siswa->nama ?? '-' }}
                                            </div>
                                            <small class="text-muted">
                                                NIS : {{ $item->siswa->nis ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->siswa->nisn ?? '-' }}</td>
                                <td>
                                    @if(optional($item->siswa)->jenis_kelamin == 'L')
                                        <span class="badge bg-teal rounded-pill px-3 py-2">Pria</span>
                                    @else
                                        <span class="badge bg-rose rounded-pill px-3 py-2">Perempuan</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light rounded-pill text-dark border px-3 py-2 d-flex gap-2">
                                        <i class="bi bi-calendar"></i>
                                        {{ optional($item->siswa)->tanggal_lahir
                                        ? \Carbon\Carbon::parse($item->siswa->tanggal_lahir)->format('d/m/Y')
                                        : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold">
                                                {{ $item->siswa->nama_wali ?? '-' }}
                                            </div>
                                            <div class="mt-1">
                                                <span class="badge bg-emerald rounded-pill d-flex gap-2">
                                                    <i class="bi bi-telephone-fill"></i>
                                                    {{ $item->siswa->telepon_wali ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="bi bi-gear-fill"></i>
                                            <i class="bi bi-caret-down-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('tu.siswa.show', $item->siswa->id) }}">
                                                    <i class="bi bi-eye me-2"></i> Detail
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('tu.siswa.edit', $item->siswa->id) }}">
                                                    <i class="bi bi-pencil-square me-2"></i> Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form
                                                    action="{{ route('tu.penempatan-siswa.keluarkan', $item->id) }}"
                                                    method="POST">

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger btn-confirm"
                                                        data-title="Keluarkan Siswa"
                                                        data-message="Siswa akan dikeluarkan dari rombel ini."
                                                        data-button="btn-danger">

                                                        <i class="bi bi-box-arrow-right me-2"></i>
                                                        Keluarkan

                                                    </button>

                                                </form>                                            
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            {{ $siswa->links() }}
            @else
                <div class="text-muted text-center py-4">
                    Belum ada siswa di rombel ini
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('tu.kelas.rombel.index', $rombel->kelas_grup_id) }}" 
                class="btn btn-outline-secondary rounded-pill px-4">
                    Kembali
                </a>    
            </div>
        </div>
    </x-table.card>
</div>
@endsection
