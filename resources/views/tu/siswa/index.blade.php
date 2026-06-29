@extends('layouts.app')

@section('title', 'Manajemen Data Siswa')

@section('content')

<div class="container-fluid">
    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Data Siswa</h3>
                    <p class="mb-0 opacity-75">Kelola data siswa sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
       FILTER
    ========================= --}}
    <div class="card mb-3">
        <div class="card-body">

            <form method="GET" action="{{ route('tu.siswa.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- ANGKATAN --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Angkatan</label>
                        <div class="input-group">

                            <select name="angkatan" class="form-select">

                                <option value="">Semua Angkatan</option>

                                @foreach ($angkatan as $a)
                                    <option value="{{ $a->id }}"
                                        {{ request('angkatan') == $a->id ? 'selected' : '' }}>
                                        {{ $a->tahun }}
                                    </option>
                                @endforeach

                            </select>

                            <a href="{{ route('tu.angkatan.create') }}"
                               class="btn btn-outline-primary">
                                +
                            </a>

                        </div>
                    </div>

                    {{-- KELAS GRUP --}}
                    <div class="col-md-2">

                        <label class="form-label fw-semibold">Grup Kelas</label>

                        <select name="kelas_grup" class="form-select">

                            <option value="">Semua</option>

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

                        <label class="form-label fw-semibold">Rombel</label>

                        <select name="kelas" class="form-select">

                        <option value="">Semua</option>

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
                        <label class="form-label fw-semibold">Cari Siswa</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Nama / NIS"
                            value="{{ request('search') }}">
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>

                        <select name="status" class="form-select">
                            <option value="">Semua</option>

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

                    {{-- BUTTON --}}
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>

                    {{-- RESET --}}
                    <div class="col-md-2">
                        <a href="{{ route('tu.siswa.index') }}"
                           class="btn btn-secondary w-100">
                           Reset
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <form method="POST" action="{{ route('tu.siswa.bulk') }}" id="bulkForm">
    @csrf

    {{-- =========================
       BULK
    ========================= --}}
    <div class="d-flex gap-2 mb-3">

    <select name="action" class="form-select w-auto" required>
    <option value="">Pilih Opsi</option>
    <option value="nonaktifkan">Nonaktifkan</option>
    <option value="aktifkan">Aktifkan</option>
    </select>

    <button class="btn btn-primary btn-sm">
    Terapkan
    </button>

    </div>

    <div class="mb-2 text-muted small">
        <span id="selectedCount">0</span> siswa dipilih
    </div>

    {{-- =========================
       TABEL SISWA
    ========================= --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>NISN</th>
                        <th>Rombel</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($siswa as $row)

                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="row-checkbox">
                            </td>

                            <td>{{ $loop->iteration + ($siswa->currentPage()-1)*$siswa->perPage() }}</td>

                            <td>{{ $row->nama }}</td>

                            <td>{{ $row->nis }}</td>

                            <td>{{ $row->nisn ?? '-' }}</td>

                            {{-- ROMBEL --}}
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $row->penempatanAktif?->kelas?->nama_kelas ?? '-' }}
                                </span>
                            </td>

                            {{-- ANGKATAN --}}
                            <td>
                                {{ $row->penempatanAktif?->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($row->trashed())

                                <span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle">
                                    <i class="bi bi-x-circle me-1"></i> Nonaktif
                                </span>

                                @else

                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>

                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>

                                <div class="dropdown">

                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">

                                        <i class="bi bi-gear-fill"></i>

                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                                        <li>
                                            <a class="dropdown-item" href="{{ route('tu.siswa.show', $row->id) }}">
                                                <i class="bi bi-eye me-2"></i> Detail
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('tu.siswa.edit', $row->id) }}">
                                                <i class="bi bi-pencil-square me-2"></i> Edit
                                            </a>
                                        </li>

                                    </ul>

                                </div>

                                

                                

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Tidak ada data siswa
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{-- PAGINATION --}}
            <div class="mt-3">
                {{ $siswa->links() }}
            </div>

        </div>
    </div>
    </form>

</div>
@section('scripts')
<script>

document.getElementById('selectAll').addEventListener('change', function(){

    const checkboxes = document.querySelectorAll('.row-checkbox');

    checkboxes.forEach(cb => {
        cb.checked = this.checked;
    });

});
document.getElementById('bulkForm').addEventListener('submit', function(e){

    const checked = document.querySelectorAll('.row-checkbox:checked');

    if(checked.length === 0){
        e.preventDefault();
        alert('Pilih minimal satu siswa');
        return;
    }


});

    const boxes = document.querySelectorAll('.row-checkbox');
    const counter = document.getElementById('selectedCount');

    boxes.forEach(box => {

    box.addEventListener('change', function(){

    const checked = document.querySelectorAll('.row-checkbox:checked').length;

    counter.innerText = checked;

    });
});

</script>
@endsection

@endsection