@extends('layouts.tu')

@section('title', 'Tahun Ajaran')

@section('content')

<div class="container-fluid">

    <!-- 🔶 HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Tahun Ajaran</h4>
            <small class="text-muted">Manajemen siklus akademik sistem</small>
        </div>

        <a href="{{ route('tu.tahun.create') }}" class="btn btn-primary">
            + Tambah Tahun
        </a>
    </div>

    <!-- 🔷 STATUS GLOBAL -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <div class="text-muted small">STATUS SISTEM</div>

                @if($tahunAktif)
                    <h3 class="mb-1">
                        {{ $tahunAktif->tahun }}
                        <span class="badge bg-info text-dark">
                            {{ $tahunAktif->semester }}
                        </span>
                    </h3>

                    {!! match($tahunAktif->status) {
                        'aktif'    => '<span class="badge bg-success">AKTIF</span>',
                        'transisi' => '<span class="badge bg-warning text-dark">TRANSISI</span>',
                        'selesai'  => '<span class="badge bg-secondary">SELESAI</span>',
                    } !!}
                @else
                    <div class="text-danger">Tidak ada tahun aktif</div>
                @endif
            </div>

            <!-- 🔴 ACTION -->
            <div class="text-end">

                @if($tahunAktif?->status === 'aktif')
                    <form method="POST" action="{{ route('tu.tahun.transisi') }}">
                        @csrf
                        <button class="btn btn-warning">
                            Masuk Masa Transisi
                        </button>
                    </form>
                @endif

                @if($tahunAktif?->status === 'transisi')
                    <form method="POST" action="{{ route('tu.tahun.tutup') }}"
                          onsubmit="return confirm('Tutup tahun ajaran ini?')">
                        @csrf
                        <button class="btn btn-danger">
                            Tutup Tahun & Buat Baru
                        </button>
                    </form>
                @endif

            </div>

        </div>
    </div>

    <!-- 🔷 TABLE HISTORY -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Riwayat Tahun Ajaran</h6>

                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Cari tahun..." value="{{ request('q') }}">

                    <button class="btn btn-sm btn-outline-secondary">
                        Cari
                    </button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Tahun</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tahun as $row)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $row->tahun }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $row->semester }}
                                    </span>
                                </td>

                                <td>
                                    {!! match($row->status) {
                                        'aktif'    => '<span class="badge bg-success">Aktif</span>',
                                        'transisi' => '<span class="badge bg-warning text-dark">Transisi</span>',
                                        'selesai'  => '<span class="badge bg-secondary">Selesai</span>',
                                    } !!}
                                </td>

                                <td>
                                    <a href="{{ route('tu.tahun.detail', $row->id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="text-muted">
                                        Belum ada data tahun ajaran
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- 🔻 PAGINATION -->
            <div class="mt-3">
                {{ $tahun->links() }}
            </div>

        </div>
    </div>

</div>

@endsection