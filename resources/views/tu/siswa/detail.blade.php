@extends('layouts.tu')

@section('title','Detail Siswa')

@section('content')

<div class="container-fluid">

<div class="card shadow-sm border-0">

<div class="card-body">

<div class="row align-items-start">

<div class="col-md-4 pe-4 border-end">

<div class="d-flex align-items-center gap-3">

<img
src="{{ $siswa->foto
    ? asset('storage/foto_siswa/'.$siswa->foto)
    : 'https://ui-avatars.com/api/?name='.urlencode($siswa->nama).'&background=E9ECEF&color=495057&size=120' }}"
class="rounded-circle"
width="90"
height="90"
style="object-fit:cover;"
>

<div>

<h5 class="fw-bold mb-1">{{ $siswa->nama }}</h5>

<div class="text-muted small">
NIS {{ $siswa->nis }}
</div>

<div class="text-muted small">
NISN {{ $siswa->nisn ?? '-' }}
</div>

</div>

</div>

</div>


<div class="col-md-8 ps-4">

<h6 class="fw-semibold border-bottom pb-2 mb-3">Informasi Akademik</h6>

<table class="table table-sm">

<tr class="{{ $siswa->penempatanAktif ? 'table-success' : '' }}">
<th width="200">Kelas</th>
<td>{{ $siswa->penempatanAktif?->kelas?->nama_kelas ?? '-' }}
@if($siswa->penempatanAktif?->kelas?->kelasGrup?->angkatan)
    <small class="text-muted">
        ({{ $siswa->penempatanAktif->kelas->kelasGrup->angkatan->tahun }})
    </small>
@endif
</td>
</tr>

<tr>
<th>Jenis Kelamin</th>
<td>
@if($siswa->jenis_kelamin == 'L')
<span class="badge bg-primary">Laki-laki</span>
@else
<span class="badge bg-danger">Perempuan</span>
@endif
</td>
</tr>

<tr>
<th>Tanggal Lahir</th>
<td>
{{ $siswa->tanggal_lahir
? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y')
: '-' }}
</td>
</tr>

</table>


<h6 class="fw-semibold mt-4 mb-3">Data Wali</h6>

<table class="table table-sm">

<tr>
<th width="200">Nama Wali</th>
<td>{{ $siswa->nama_wali ?? '-' }}</td>
</tr>

<tr>
<th>Telepon Wali</th>
<td>{{ $siswa->telepon_wali ?? '-' }}</td>
</tr>

<tr>
<th>Alamat</th>
<td>{{ $siswa->alamat ?? '-' }}</td>
</tr>

</table>
<h6 class="fw-semibold mt-4 mb-3">Riwayat Kelas</h6>

<div class="card shadow-sm border-0">
<div class="card-body p-0">

<table class="table table-sm table-hover mb-0">
<thead class="table-light">
<tr>
<th width="160">Tahun Ajaran</th>
<th width="120">Kelas</th>
<th width="140">Tanggal Masuk</th>
<th width="140">Tanggal Keluar</th>
<th width="100">Status</th>
</tr>
</thead>

<tbody>

@forelse($siswa->penempatan as $item)

<tr>

<td>
{{ $item->kelas?->kelasGrup?->angkatan?->tahun ?? '-' }}
</td>

<td>
<span class="badge bg-secondary">
{{ $item->kelas?->nama_kelas ?? '-' }}
</span>
</td>

<td>
{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d M Y') }}
</td>

<td>
@if($item->tanggal_keluar)
{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d M Y') }}
@else
-
@endif
</td>

<td>

@if($item->tanggal_keluar === null)

<span class="badge bg-success">
Aktif
</span>

@else

<span class="badge bg-secondary">
Selesai
</span>

@endif

</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center text-muted py-3">
Belum ada riwayat kelas
</td>
</tr>

@endforelse

</tbody>
</table>

</div>
</div>
<div class="mt-3">

<a href="{{ route('tu.siswa.edit',$siswa->id) }}"
class="btn btn-warning btn-sm">
Edit Data
</a>

<a href="{{ url()->previous() }}"
class="btn btn-secondary btn-sm">
Kembali
</a>

</div>

</div>

</div>

</div>
</div>

</div>

@endsection
