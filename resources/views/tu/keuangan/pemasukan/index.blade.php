@extends('layouts.app')

@section('title','Pemasukan Keuangan')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h1>Pemasukan Keuangan</h1>
    <a href="{{ route('tu.keuangan.pemasukan.create') }}" class="btn btn-primary">
        + Tambah Pemasukan
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Sumber Dana</th>
                    <th>Periode Anggaran</th>
                    <th>Nominal</th>
                    <th>Dokumen</th>
                </tr>
            </thead>
            <tbody>
            @forelse($pemasukan as $p)
                <tr>
                    <td>{{ $p->tanggal }}</td>
                    <td>{{ $p->sumber_dana }}</td>
                    <td>{{ $p->periode_anggaran }}</td>
                    <td>Rp {{ number_format($p->nominal,0,',','.') }}</td>
                    <td>
                        @if($p->dokumen)
                            <a href="{{ asset('storage/'.$p->dokumen) }}" target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada data pemasukan
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $pemasukan->links() }}

    </div>
</div>

@endsection
