@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<div class="container-fluid py-3">

    <h4 class="fw-bold mb-4">
        Data Siswa
    </h4>

    {{-- FILTER --}}
    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('superadmin.siswa.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- ANGKATAN --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Angkatan
                        </label>

                        <select name="angkatan"
                                class="form-select">

                            <option value="">
                                Semua Angkatan
                            </option>

                            @foreach ($angkatan as $a)

                                <option value="{{ $a->id }}"
                                    {{ request('angkatan') == $a->id ? 'selected' : '' }}>

                                    {{ $a->tahun }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- GRUP KELAS --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Grup Kelas
                        </label>

                        <select name="kelas_grup"
                                class="form-select">

                            <option value="">
                                Semua
                            </option>

                            @foreach($kelasGrup as $g)

                                <option value="{{ $g->id }}"
                                    {{ request('kelas_grup') == $g->id ? 'selected' : '' }}>

                                    Kelas {{ $g->kelasMaster->tingkat }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- ROMBEL --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Rombel
                        </label>

                        <select name="kelas"
                                class="form-select">

                            <option value="">
                                Semua
                            </option>

                            @foreach($kelas as $k)

                                <option value="{{ $k->id }}"
                                    {{ request('kelas') == $k->id ? 'selected' : '' }}>

                                    {{ $k->nama_kelas }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- SEARCH --}}
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Cari Siswa
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Nama / NIS"
                               value="{{ request('search') }}">

                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                Semua
                            </option>

                            <option value="aktif"
                                {{ request('status') == 'aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="nonaktif"
                                {{ request('status') == 'nonaktif' ? 'selected' : '' }}>
                                Nonaktif
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-primary w-100">
                            Filter
                        </button>

                    </div>

                    <div class="col-md-2">

                        <a href="{{ route('superadmin.siswa.index') }}"
                           class="btn btn-secondary w-100">
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABEL --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>

                        <th width="60">#</th>
                        <th>Nama</th>
                        <th width="120">NIS</th>
                        <th width="120">NISN</th>
                        <th width="120">Rombel</th>
                        <th width="120">Angkatan</th>
                        <th width="100">Status</th>
                        <th width="100">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($siswa as $row)

                        <tr>

                            <td>
                                {{ $loop->iteration + ($siswa->currentPage()-1)*$siswa->perPage() }}
                            </td>

                            <td>
                                {{ $row->nama }}
                            </td>

                            <td>
                                {{ $row->nis }}
                            </td>

                            <td>
                                {{ $row->nisn ?? '-' }}
                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $row->penempatanAktif?->kelas?->nama_kelas ?? '-' }}

                                </span>

                            </td>

                            <td>

                                {{ $row->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}

                            </td>

                            <td>

                                @if($row->trashed())

                                    <span class="badge bg-secondary">
                                        Nonaktif
                                    </span>

                                @else

                                    <span class="badge bg-success">
                                        Aktif
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a href="{{ route('superadmin.siswa.show', $row->id) }}"
                                   class="btn btn-sm btn-info">

                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center text-muted">

                                Tidak ada data siswa

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            <div class="mt-3">

                {{ $siswa->links() }}

            </div>

        </div>

    </div>

</div>

@endsection