@extends('layouts.tu')

@section('title', 'Manajemen Data Siswa')

@section('content')

<div class="container-fluid">

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
    <option value="pindah_rombel">Pindah Rombel</option>
    <option value="nonaktifkan">Nonaktifkan</option>
    </select>

    <button class="btn btn-primary btn-sm">
    Terapkan
    </button>

    </div>

    {{-- =========================
       TABEL SISWA
    ========================= --}}
    <div class="card">
        <div class="card-body">

            <table class="table table-hover table-sm align-middle">

                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th width="60">#</th>
                        <th>Nama</th>
                        <th width="120">NIS</th>
                        <th width="120">NISN</th>
                        <th width="120">Rombel</th>
                        <th width="120">Angkatan</th>
                        <th width="100">Status</th>
                        <th width="160">Aksi</th>
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
                                @if($row->penempatanAktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>

                                <a href="{{ route('tu.siswa.show', $row->id) }}"
                                   class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                <a href="{{ route('tu.siswa.edit', $row->id) }}"
                                   class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                

                                

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
    }

});

</script>
@endsection

@endsection