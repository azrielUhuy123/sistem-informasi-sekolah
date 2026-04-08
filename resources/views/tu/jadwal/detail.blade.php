@extends('layouts.tu')

@section('title', 'Rombel Jadwal')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">
                Kelas {{ $grup->kelasMaster->tingkat }}
            </h4>
            <small class="text-muted">
                {{ $grup->tahunAjaran->tahun }} ({{ $grup->tahunAjaran->semester }})
            </small>
        </div>

        <a href="{{ route('tu.jadwal.index') }}" class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>

    {{-- BUTTON TAMBAH --}}
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('tu.jadwal.rombel.create', $grup->id) }}"
   class="btn btn-primary btn-sm">
    + Tambah Rombel
</a>
    </div>

    {{-- TABLE --}}
    <div class="card">
        <div class="card-body">

            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Rombel</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($grup->rombel as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->nama_rombel }}</td>
                        <td>
                            <span class="text-muted">-</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Belum ada rombel
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection