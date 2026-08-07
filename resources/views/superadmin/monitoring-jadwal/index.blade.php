@extends('layouts.app')

@section('title','Monitoring Jadwal')

@section('content')

@php
use App\Models\PengajuanJadwal;
use Illuminate\Support\Str;

$tahunId = current_tahun_id();

$submittedCount = PengajuanJadwal::where('tahun_ajaran_id',$tahunId)
->where('status','submitted')
->count();

$approvedCount = PengajuanJadwal::where('tahun_ajaran_id',$tahunId)
->where('status','approved')
->count();

$rejectedCount = PengajuanJadwal::where('tahun_ajaran_id',$tahunId)
->where('status','rejected')
->count();

$publishedCount = PengajuanJadwal::where('tahun_ajaran_id',$tahunId)
->where('status','published')
->count();
@endphp

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body text-white p-4 p-lg-5"
            style="background:linear-gradient(135deg,#0f4c81,#2563eb);">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Monitoring Jadwal

                    </h2>

                    <p class="mb-0 opacity-75">

                        Monitoring seluruh pengajuan jadwal pembelajaran pada setiap tingkat kelas.

                    </p>

                </div>

                <div class="bg-white rounded-4 text-center px-4 py-3 mt-3 mt-lg-0">

                    <h2 class="fw-bold text-primary mb-0">

                        {{ $publishedCount }}

                    </h2>

                    <small class="text-muted">

                        Jadwal Publish

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ================= STATISTIK ================= --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Submitted

                    </small>

                    <h2 class="fw-bold text-warning mb-0">

                        {{ $submittedCount }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Approved

                    </small>

                    <h2 class="fw-bold text-success mb-0">

                        {{ $approvedCount }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Rejected

                    </small>

                    <h2 class="fw-bold text-danger mb-0">

                        {{ $rejectedCount }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Published

                    </small>

                    <h2 class="fw-bold text-primary mb-0">

                        {{ $publishedCount }}

                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- ================= ACCORDION ================= --}}

    <div class="accordion" id="accordionTingkat">

@foreach($kelasPerTingkat as $tingkat => $kelasList)

@php
$slug = Str::slug((string)$tingkat);

$collapseId="tingkat-$slug";

$headingId="heading-$slug";
@endphp

<div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-4">

    <h2 class="accordion-header" id="{{ $headingId }}">

        <button class="accordion-button collapsed fw-bold"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#{{ $collapseId }}">

            <i class="bi bi-mortarboard-fill text-primary me-2"></i>

            Tingkat {{ $tingkat }}

        </button>

    </h2>

<div id="{{ $collapseId }}"
class="accordion-collapse collapse"
data-bs-parent="#accordionTingkat">

<div class="accordion-body">

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead>

<tr>

<th>Kelas</th>

<th width="180">

Status

</th>

<th width="140">

Jumlah Jadwal

</th>

<th width="120" class="text-center">

Aksi

</th>

</tr>

</thead>

<tbody>

@forelse($kelasList as $itemKelas)

@php

$submitted = $itemKelas->pengajuanJadwal
->where('status','submitted')
->count();

$approved = $itemKelas->pengajuanJadwal
->where('status','approved')
->count();

$rejected = $itemKelas->pengajuanJadwal
->where('status','rejected')
->count();

$published = $itemKelas->pengajuanJadwal
->where('status','published')
->count();

$total = $itemKelas->pengajuanJadwal->count();

if($published>0){

$status='published';

}elseif($submitted>0){

$status='submitted';

}elseif($approved>0){

$status='approved';

}elseif($rejected>0){

$status='rejected';

}else{

$status='draft';

}

@endphp

<tr>

<td>

<div class="fw-semibold">

{{ $itemKelas->nama_kelas_label }}

</div>

</td>

<td>

@if($status=='published')

<span class="badge bg-primary-subtle text-primary px-3 py-2">

Published

</span>

@elseif($status=='submitted')

<span class="badge bg-warning-subtle text-warning px-3 py-2">

Submitted

</span>

@elseif($status=='approved')

<span class="badge bg-success-subtle text-success px-3 py-2">

Approved

</span>

@elseif($status=='rejected')

<span class="badge bg-danger-subtle text-danger px-3 py-2">

Rejected

</span>

@else

<span class="badge bg-secondary">

Draft

</span>

@endif

</td>

<td>

{{ $total }}

</td>

<td class="text-center">

<a href="{{ route('superadmin.monitoring-jadwal.show',$itemKelas->id) }}"
class="btn btn-primary btn-sm rounded-pill px-3">

<i class="bi bi-eye-fill me-1"></i>

Lihat

</a>

</td>

</tr>

@empty

<tr>

<td colspan="4">

<div class="empty-state">

<i class="bi bi-calendar-x display-5 text-secondary"></i>

<h5 class="mt-3">

Belum Ada Jadwal

</h5>

<p class="text-muted mb-0">

Belum terdapat pengajuan jadwal pada tingkat ini.

</p>

</div>

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>

</div>

@endforeach

</div>

</div>

<style>

.table thead th{

border:0;
color:#64748b;
font-size:.82rem;
font-weight:700;
text-transform:uppercase;
letter-spacing:.5px;
padding:16px;

}

.table tbody td{

padding:18px 16px;
vertical-align:middle;

}

.table-hover tbody tr:hover{

background:#f8fbff;

}

.badge{

font-weight:600;

}

.accordion-button{

background:#fff;
font-weight:700;

}

.accordion-button:not(.collapsed){

background:#eef5ff;
color:#2563eb;

}

.empty-state{

padding:60px 20px;
text-align:center;

}

.card{

transition:.25s;

}

.card:hover{

box-shadow:0 15px 40px rgba(15,76,129,.08)!important;

}

</style>

@endsection