@extends('layouts.tu')

@section('title', 'Manajemen Jadwal')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h1>Manajemen Jadwal</h1>
    <a href="{{ route('tu.jadwal.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
<a href="{{ route('tu.jadwal.export.pdf', $kelas_id) }}" class="btn btn-danger btn-sm">
    Export PDF
</a>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($jadwal as $j)
                <tr>
                    <td>{{ $j->kelas->nama_kelas }}</td>
                    <td>{{ $j->mapel }}</td>
                    <td>{{ $j->guru->nama }}</td>
                    <td>{{ $j->hari }}</td>
                    <td>{{ $j->jam_mulai }} - {{ $j->jam_selesai }}</td>
                    <td>
                        <a href="{{ route('tu.jadwal.detail', $j->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('tu.jadwal.edit', $j->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('tu.jadwal.destroy', $j->id) }}"
                              method="POST"
                              class="d-inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus jadwal ini?')" 
                                    class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>

        {{ $jadwal->links() }}

    </div>
</div>

@endsection
