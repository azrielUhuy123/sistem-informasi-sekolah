@extends('layouts.app')

@section('title', 'Manajemen Angkatan')

@section('content')

<a href="{{ route('tu.angkatan.create') }}" class="btn btn-primary mb-3">
    <i class="bi bi-plus-lg"></i> Tambah Angkatan
</a>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($angkatan as $a)
                    <tr>
                        <td>{{ $a->tahun }}</td>
                        <td>{{ $a->keterangan ?? '-' }}</td>
                        <td>
                            @if($a->aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="d-flex gap-1">
                            <form action="{{ route('tu.angkatan.toggle', $a->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-warning">
                                    Toggle
                                </button>
                            </form>

                            <a href="{{ route('tu.angkatan.edit', $a->id) }}"
                               class="btn btn-sm btn-info">
                                Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $angkatan->links() }}
    </div>
</div>

@endsection
