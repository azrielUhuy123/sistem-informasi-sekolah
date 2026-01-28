@extends('layouts.tu')

@section('title', 'Manajemen Data Siswa')

@section('content')

{{-- =========================
    FILTER ANGKATAN & KELAS
========================= --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">

            {{-- =========================
                ANGKATAN
            ========================= --}}
            <div class="col-md-4">
                <label class="form-label">Angkatan</label>
                <div class="input-group">
                    <select name="angkatan" class="form-select">
    <option value="">-- Pilih Angkatan --</option>

    @foreach($listAngkatan as $a)
        <option value="{{ $a->id }}"
            {{ request('angkatan') == $a->id ? 'selected' : '' }}>
            {{ $a->tahun }}
            @if($a->keterangan)
                ({{ $a->keterangan }})
            @endif
        </option>
    @endforeach
</select>


                    {{-- BUTTON TAMBAH ANGKATAN --}}
                    <a href="{{ route('tu.angkatan.create') }}"
                       class="btn btn-outline-primary"
                       title="Tambah Angkatan Baru">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>

            {{-- =========================
                KELAS
            ========================= --}}
            <div class="col-md-4">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">-- Semua Kelas --</option>

                    @foreach($listKelas as $k)
                        <option value="{{ $k->id }}"
                            {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- =========================
                SUBMIT
            ========================= --}}
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    Tampilkan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- =========================
    EMPTY STATE (BELUM PILIH ANGKATAN)
========================= --}}
@if(!$angkatan)
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Silakan pilih <strong>angkatan</strong> terlebih dahulu untuk menampilkan data siswa.
    </div>
@endif

{{-- =========================
    TABEL SISWA
========================= --}}
@if($angkatan)
    @include('components.crud.index', [

        'title' => "Data Siswa Angkatan $angkatan",

        'rows'  => $siswa,

        'columns' => [

            [
                'key' => 'nama',
                'label' => 'Nama Siswa',
                'sortable' => true,
            ],
            [
                'key' => 'nis',
                'label' => 'NIS',
                'sortable' => true,
            ],

            // =========================
            // KELAS AKTIF
            // =========================
            [
                'key' => '__kelas_aktif',
                'label' => 'Kelas Aktif',
                'value' => fn ($row) =>
                    $row->penempatanAktif
                        ? '<span class="badge bg-primary">'
                            . $row->penempatanAktif->kelas->nama_kelas .
                          '</span>'
                        : '<span class="badge bg-secondary">Belum ditempatkan</span>',
                'raw' => true,
            ],

            // =========================
            // TAHUN AJARAN
            // =========================
            [
                'key' => '__tahun_ajaran',
                'label' => 'Tahun Ajaran',
                'value' => fn ($row) =>
                    $row->penempatanAktif
                        ? $row->penempatanAktif->tahunAjaran->tahun
                        : '-',
            ],

            // =========================
            // STATUS
            // =========================
            [
                'key' => '__status',
                'label' => 'Status',
                'value' => fn ($row) =>
                    $row->penempatanAktif
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-warning text-dark">Belum ditempatkan</span>',
                'raw' => true,
            ],
        ],

        'actions' => [
            'detail' => 'tu.siswa.detail',
            'edit' => 'tu.siswa.edit',
            'delete' => 'tu.siswa.destroy',
            'restore' => 'tu.siswa.restore',
            'forceDelete' => 'tu.siswa.forceDelete',
        ],

        'routeCreate' => route('tu.siswa.create', [
            'angkatan' => $angkatan
        ]),

        'routeBulk' => 'tu.siswa.bulk',

        'extraActions' => view('tu.siswa.extra-actions', [
            'angkatan' => $angkatan,
            'kelasId'  => $kelasId
        ])->render(),
    ])
@endif

@endsection
