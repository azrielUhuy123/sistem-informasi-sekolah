@extends('layouts.tu')

@section('title', 'Manajemen Wali Kelas')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h1>Manajemen Wali Kelas</h1>
    <a href="{{ route('tu.walikelas.create') }}" class="btn btn-primary">+ Tambah Wali Kelas</a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Tahun Ajaran</th>
                    <th style="width:150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
@forelse($wali as $w)
<tr>
    <td>{{ $w->kelas->nama_kelas ?? '-' }}</td>
    <td>{{ $w->guru->nama ?? '-' }}</td>
    <td>
    @if($w->tahun)
        {{ $w->tahun->tahun }} ({{ $w->tahun->semester }})
    @else
        -
    @endif
</td>

    <td>
        <form action="{{ route('tu.walikelas.destroy', $w->id) }}"
              method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Hapus wali kelas ini?')"
                    class="btn btn-danger btn-sm">
                Hapus
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center text-muted">
        Belum ada data wali kelas
    </td>
</tr>
@endforelse
</tbody>

        </table>

        <div class="mt-3">
            {{ $wali->links() }}
        </div>

    </div>
</div>

@endsection
x