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

    <!-- 🔷 STATUS SISTEM -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">

            <div class="text-muted small">STATUS SISTEM</div>

            @if($tahunAktif)

                <h4 class="mb-1">
                    {{ $tahunAktif->tahun }}
                    <span class="badge bg-info text-dark">
                        {{ $tahunAktif->semester }}
                    </span>
                </h4>

                {{-- ===============================
                     🔵 GANJIL AKTIF
                =============================== --}}
                @if($tahunAktif->status === 'aktif' && $tahunAktif->semester === 'Ganjil')

                    <span class="badge bg-success">AKTIF</span>
                    <div class="text-muted small mb-3">
                        Mode: Semester Ganjil
                    </div>

                    <form method="POST"
                          action="{{ route('tu.tahun.lanjutSemester') }}"
                          onsubmit="return confirm('Lanjut ke semester genap?')">
                        @csrf
                        <button class="btn btn-warning">
                            Lanjut ke Semester Genap
                        </button>
                    </form>

                {{-- ===============================
                     🟢 GENAP AKTIF
                =============================== --}}
                @elseif($tahunAktif->status === 'aktif' && $tahunAktif->semester === 'Genap')

                    <span class="badge bg-success">AKTIF</span>
                    <div class="text-muted small mb-3">
                        Mode: Operasional (Akhir Tahun)
                    </div>

                    <div class="d-flex gap-2 flex-wrap">

                        <a href="{{ route('tu.penempatan-siswa.naik-kelas.index') }}"
                           class="btn btn-primary">
                            Naik Kelas
                        </a>

                        <form method="POST"
                              action="{{ route('tu.tahun.tutup') }}"
                              onsubmit="return confirm('Tutup tahun ajaran ini?')">
                            @csrf
                            <button class="btn btn-danger">
                                Tutup Tahun Ajaran
                            </button>
                        </form>

                    </div>

                {{-- ===============================
                     ⚫ DRAFT
                =============================== --}}
                @elseif($tahunAktif->status === 'draft')

                    <span class="badge bg-dark">DRAFT</span>
                    <div class="text-muted small mb-3">
                        Belum aktif
                    </div>

                {{-- ===============================
                     ⚪ SELESAI
                =============================== --}}
                @elseif($tahunAktif->status === 'selesai')

                    <span class="badge bg-secondary">SELESAI</span>
                    <div class="text-muted small mb-3">
                        Arsip (read-only)
                    </div>

                @endif

            @else
                <div class="text-danger mb-2">
                    Tidak ada tahun ajaran aktif
                </div>
            @endif

            {{-- ALERT --}}
            @if(session('success'))
                <div class="alert alert-success mt-2">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger mt-2">
                    {{ session('error') }}
                </div>
            @endif

        </div>
    </div>

    <!-- 🔷 AKTIVASI TAHUN -->
    @if(!$tahunAktif || $tahunAktif->status === 'selesai')

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">

            <div class="text-muted small mb-2">
                Aktifkan Tahun Ajaran
            </div>

            <form method="POST" action="" id="formAktifkan">
                @csrf

                <div class="d-flex gap-2">

                    <select name="tahun_id" class="form-select" required>
                        @foreach($tahun as $row)
                            @if($row->status === 'draft')
                                <option value="{{ $row->id }}">
                                    {{ $row->tahun }} - {{ $row->semester }}
                                </option>
                            @endif
                        @endforeach
                    </select>

                    <button class="btn btn-success">
                        Aktifkan
                    </button>

                </div>
            </form>

        </div>
    </div>

    @endif

    <!-- 🔷 TABLE -->
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
                                <td class="fw-semibold">{{ $row->tahun }}</td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $row->semester }}
                                    </span>
                                </td>

                                <td>
                                    {!! match($row->status) {
                                        'aktif'   => '<span class="badge bg-success">Aktif</span>',
                                        'draft'   => '<span class="badge bg-dark">Draft</span>',
                                        'selesai' => '<span class="badge bg-secondary">Selesai</span>',
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
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada data tahun ajaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $tahun->links() }}
            </div>

        </div>
    </div>

</div>

<script>
const form = document.getElementById('formAktifkan');

if (form) {
    form.addEventListener('submit', function () {
        const select = form.querySelector('select');
        const id = select.value;
        form.action = `/tu/tahun/${id}/aktifkan`;
    });
}
</script>

@endsection