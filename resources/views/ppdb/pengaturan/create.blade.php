@extends('layouts.app')

@section('content')

<form
action="{{ route('ppdb.pengaturan.store') }}"
method="POST">

@csrf

<div class="card">

<div class="card-body">

<div class="mb-3">

<label>Tahun Ajaran</label>

<select
name="tahun_ajaran_id"
class="form-select">

@foreach($tahunAjaran as $item)

<option value="{{ $item->id }}">
{{ $item->nama }}
</option>

@endforeach

</select>

</div>

<div class="mb-3">
<label>Tanggal Mulai</label>
<input
type="date"
name="tanggal_mulai"
class="form-control">
</div>

<div class="mb-3">
<label>Tanggal Selesai</label>
<input
type="date"
name="tanggal_selesai"
class="form-control">
</div>

<div class="mb-3">
<label>Kuota</label>
<input
type="number"
name="kuota"
class="form-control">
</div>

<div class="mb-3">
<label>Status</label>

<select
name="status"
class="form-select">

<option value="dibuka">
Dibuka
</option>

<option value="ditutup">
Ditutup
</option>

</select>

</div>

<button
class="btn btn-primary">

Simpan

</button>

</div>

</div>

</form>

@endsection