@extends('layouts.app')

@section('title', 'Jadwal Final')

@section('content')

<div class="container-fluid py-3">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Jadwal Final

                    </h2>

                    <p class="mb-0 opacity-75">

                        Jadwal resmi yang telah dipublikasikan dan siap diekspor ke PDF.

                    </p>

                </div>

                <div class="mt-3 mt-lg-0">

                    <div class="bg-white rounded-4 px-4 py-3 text-center">

                        <h3
                            id="selectedCountCard"
                            class="fw-bold text-primary mb-0"
                        >

                            0

                        </h3>

                        <small class="text-muted">

                            Kelas Dipilih

                        </small>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <form
        action="{{ route('superadmin.jadwal-final.export') }}"
        method="POST"
        id="formExport"
    >

        @csrf

        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <h5 class="fw-bold mb-1">

                            Pilih Jadwal yang Akan Diekspor

                        </h5>

                        <div
                            id="selectedCount"
                            class="text-muted"
                        >

                            Belum ada kelas dipilih

                        </div>

                    </div>

                    <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">

                        <button
                            type="button"
                            class="btn btn-outline-primary rounded-pill me-2"
                            id="btnSelectAll"
                        >

                            <i class="bi bi-check2-square me-1"></i>

                            Pilih Semua

                        </button>

                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-pill me-2"
                            id="btnClearAll"
                        >

                            <i class="bi bi-x-square me-1"></i>

                            Kosongkan

                        </button>

                        <button
                            class="btn btn-danger rounded-pill px-4"
                            type="submit"
                        >

                            <i class="bi bi-file-earmark-pdf-fill me-2"></i>

                            Export PDF

                        </button>

                    </div>

                </div>

            </div>

        </div>

        {{-- ================= TAB TINGKAT ================= --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body py-3">

                <ul
                    class="nav nav-pills justify-content-center gap-2"
                    id="tingkatTab"
                    role="tablist"
                >

                    @foreach($kelasByTingkat as $tingkat => $listKelas)

                        <li class="nav-item">

                            <button
                                class="nav-link rounded-pill px-4 py-2 fw-semibold {{ $loop->first ? 'active' : '' }}"
                                data-bs-toggle="pill"
                                data-bs-target="#tingkat{{ $tingkat }}"
                                type="button"
                            >

                                <i class="bi bi-mortarboard-fill me-2"></i>

                                Kelas {{ $tingkat }}

                                <span class="badge bg-light text-dark ms-2">

                                    {{ count($listKelas) }}

                                </span>

                            </button>

                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

        {{-- CONTENT TAB --}}
        <div class="tab-content">

            @foreach($kelasByTingkat as $tingkat => $listKelas)

                <div
                    class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                    id="tingkat{{ $tingkat }}">

                    <div
                        class="accordion modern-accordion"
                        id="accordionTingkat{{ $tingkat }}">

                        @foreach($listKelas as $k)

                        @php

                            $totalJadwal = $k->pengajuanJadwal->count();

                        @endphp

                        <div class="accordion-item border-0 shadow rounded-4 mb-4 overflow-hidden kelas-card">

                            <h2 class="accordion-header">

                                 <div class="d-flex align-items-center justify-content-between bg-white px-4 py-3">

                                    {{-- CHECKBOX --}}
                                    <div class="form-check me-4">

                                        <input
                                            class="form-check-input kelas-checkbox"
                                            type="checkbox"
                                            name="kelas_ids[]"
                                            value="{{ $k->id }}">

                                    </div>

                                    {{-- BUTTON --}}
                                    <button
                                        class="accordion-button collapsed shadow-none bg-transparent p-0"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#kelas{{ $k->id }}"
                                        type="button">

                                        <div class="w-100">

                                            <div class="d-flex justify-content-between align-items-center flex-wrap">

                                                <div>

                                                    <h5 class="fw-bold mb-1">

                                                        {{ $k->nama_kelas }}

                                                    </h5>

                                                    <small class="text-muted">

                                                        Jadwal resmi kelas {{ $k->nama_kelas }}

                                                    </small>

                                                </div>

                                                <div class="d-flex gap-2">

                                                    <span class="badge bg-success rounded-pill px-3 py-2">

                                                        {{ $totalJadwal }}

                                                        Jadwal

                                                    </span>

                                                    <span class="badge bg-primary rounded-pill px-3 py-2">

                                                        Published

                                                    </span>

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </div>

                            </h2>

                                <div
                                    id="kelas{{ $k->id }}"
                                    class="accordion-collapse collapse"
                                    data-bs-parent="#accordionTingkat{{ $tingkat }}"
                                >

                                    <div class="accordion-body bg-white">

                                        <div class="table-responsive">

                                            <table class="table table-hover align-middle text-center mb-0">

                                                <thead>

                                                    <tr>

                                                        <th class="text-center" width="150">
                                                            Jam
                                                        </th>

                                                        @foreach($hariList as $hari)

                                                            <th>
                                                                {{ $hari->nama }}
                                                            </th>

                                                        @endforeach

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    @foreach($jamList as $jamPelajaran)

                                                        <tr>

                                                            <td class="fw-bold text-primary">

                                                                {{ substr($jamPelajaran->mulai,0,5) }}
                                                                -
                                                                {{ substr($jamPelajaran->selesai,0,5) }}

                                                            </td>

                                                            @foreach($hariList as $hari)

                                                                @php

                                                                    $jadwal = $k->pengajuanJadwal
                                                                        ->where('hari_id', $hari->id)
                                                                        ->where('jam_pelajaran_id', $jamPelajaran->id)
                                                                        ->first();

                                                                @endphp

                                                                <td style="min-width:220px;">

                                                                    @if($jadwal)

                                                                        <div class="schedule-card">

                                                                            <div class="schedule-mapel">

                                                                                {{ $jadwal->mapel->nama_mapel }}

                                                                            </div>

                                                                            <div class="schedule-guru">

                                                                                <i class="bi bi-person-fill me-1"></i>

                                                                                {{ $jadwal->guru->pegawai->nama }}

                                                                            </div>

                                                                            <div class="schedule-time">

                                                                                <i class="bi bi-clock-fill me-1"></i>

                                                                                {{ substr($jamPelajaran->mulai,0,5) }}
                                                                                -
                                                                                {{ substr($jamPelajaran->selesai,0,5) }}

                                                                            </div>

                                                                        </div>

                                                                    @else

                                                                        <div class="schedule-empty">

                                                                            <i class="bi bi-dash-circle"></i>

                                                                            <div>

                                                                                Belum Ada Jadwal

                                                                            </div>

                                                                        </div>

                                                                    @endif

                                                                </td>

                                                            @endforeach

                                                        </tr>

                                                    @endforeach

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endforeach

        </div>

    </form>

</div>
</div>

<style>

.schedule-card{

    background:linear-gradient(135deg,#eff6ff,#ffffff);

    border:1px solid #dbeafe;

    border-radius:16px;

    padding:16px;

    transition:.25s;

    min-height:120px;

}

.schedule-card:hover{

    transform:translateY(-3px);

    box-shadow:0 14px 35px rgba(37,99,235,.12);

}

.schedule-mapel{

    font-weight:700;

    color:#0f4c81;

    margin-bottom:12px;

}

.schedule-guru{

    color:#475569;

    font-size:.92rem;

}

.schedule-time{

    margin-top:12px;

    color:#2563eb;

    font-size:.86rem;

    font-weight:600;

}

.schedule-empty{

    border:2px dashed #d1d5db;

    border-radius:16px;

    padding:30px 10px;

    color:#94a3b8;

    transition:.25s;

}

.schedule-empty:hover{

    background:#f8fafc;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    border:0;

    font-weight:700;

    text-transform:uppercase;

    letter-spacing:.5px;

}

.table tbody td{

    vertical-align:middle;

    border-color:#eef2f7;

}

/* ================= CARD ================= */

.card{
    transition:.25s;
}

.card:hover{
    box-shadow:0 16px 45px rgba(15,76,129,.08)!important;
}

/* ================= NAV PILLS ================= */

.nav-pills .nav-link{

    border-radius:999px;
    font-weight:600;
    padding:10px 22px;
    color:#64748b;
    transition:.25s;

}

.nav-pills .nav-link:hover{

    background:#eef6ff;
    color:#2563eb;

}

.nav-pills .nav-link.active{

    background:linear-gradient(135deg,#2563eb,#0f4c81);
    color:#fff;

}

/* ================= ACCORDION ================= */

.modern-accordion .accordion-item{

    border-radius:18px!important;
    overflow:hidden;

}

.modern-accordion .accordion-button{

    box-shadow:none!important;
    background:white!important;

}

.modern-accordion .accordion-button:not(.collapsed){

    background:#f8fbff!important;

}

.modern-accordion .accordion-button::after{

    transition:.25s;

}

/* ================= TABLE ================= */

.table{

    margin-bottom:0;

}

.table thead th{

    border:0;
    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;
    letter-spacing:.6px;

    font-size:.82rem;
    font-weight:700;

    padding:18px;

}

.table tbody td{

    vertical-align:middle;
    padding:14px;

}

.table-bordered>:not(caption)>*>*{

    border-color:#edf2f7;

}

/* ================= JADWAL CARD ================= */

.schedule-card{

    border-radius:14px;

    background:linear-gradient(
        135deg,
        #eff6ff,
        #ffffff
    );

    border:1px solid #dbeafe;

    padding:14px;

    transition:.25s;

    height:100%;

}

.schedule-card:hover{

    transform:translateY(-3px);

    box-shadow:0 10px 30px rgba(37,99,235,.15);

}

.schedule-mapel{

    font-weight:700;
    color:#0f4c81;
    margin-bottom:10px;

}

.schedule-guru{

    font-size:.92rem;
    color:#475569;

}

.schedule-time{

    margin-top:10px;

    color:#2563eb;

    font-size:.85rem;

    font-weight:600;

}

/* ================= EMPTY ================= */

.schedule-empty{

    border:2px dashed #d1d5db;

    border-radius:14px;

    color:#94a3b8;

    padding:26px 10px;

    text-align:center;

    transition:.25s;

    font-size:.9rem;

}

.schedule-empty i{

    font-size:1.5rem;

    margin-bottom:6px;

}

.schedule-empty:hover{

    background:#f8fafc;

}

/* ================= BADGE ================= */

.badge{

    font-weight:600;
    letter-spacing:.3px;

}

/* ================= BUTTON ================= */

.btn{

    transition:.2s;

}

.btn:hover{

    transform:translateY(-2px);

}

.btn-danger{

    background:linear-gradient(135deg,#dc2626,#b91c1c);
    border:none;

}

.btn-primary{

    background:linear-gradient(135deg,#2563eb,#0f4c81);
    border:none;

}

.btn-outline-primary:hover{

    background:#2563eb;
    color:#fff;

}

/* ================= CHECKBOX ================= */

.form-check-input{

    width:20px;
    height:20px;

    cursor:pointer;

}

.form-check-input:checked{

    background-color:#2563eb;
    border-color:#2563eb;

}

.kelas-card{

    transition:.25s;

}

.kelas-card:hover{

    transform:translateY(-3px);

    box-shadow:0 18px 45px rgba(37,99,235,.10)!important;

}

.accordion-button::after{

    margin-left:18px;

}

.form-check-input{

    width:22px;
    height:22px;

    cursor:pointer;

}

.form-check-input:checked{

    background:#2563eb;
    border-color:#2563eb;

}

.badge{

    font-weight:600;

}

/* ================= RESPONSIVE ================= */

@media(max-width:992px){

    .schedule-card{

        min-height:auto;

    }

    .table{

        font-size:.9rem;

    }

}

</style>


<script>

const checkboxes = document.querySelectorAll('.kelas-checkbox');
const countText = document.getElementById('selectedCount');
const countCard = document.getElementById('selectedCountCard');

function updateCount(){

    const total = document.querySelectorAll('.kelas-checkbox:checked').length;

    countText.innerHTML = total + " Kelas Dipilih";

    countCard.innerHTML = total;

}

document.addEventListener('change',function(e){

    if(e.target.classList.contains('kelas-checkbox')){

        updateCount();

    }

});

document.getElementById('btnSelectAll').onclick=function(){

    checkboxes.forEach(cb=>cb.checked=true);

    updateCount();

}

document.getElementById('btnClearAll').onclick=function(){

    checkboxes.forEach(cb=>cb.checked=false);

    updateCount();

}

</script>
@endsection