@extends('layouts.app')

@section('title', 'Jadwal Final')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Jadwal Final
        </h4>   

        <small class="text-muted">
            Jadwal resmi yang sudah dipublish
        </small>

    </div>

    <form
        action="{{ route('superadmin.jadwal-final.export') }}"
        method="POST"
        id="formExport"
    >

        @csrf

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <span
                    id="selectedCount"
                    class="fw-semibold"
                >

                    0 kelas dipilih

                </span>

            </div>

            <button
                class="btn btn-danger"
                type="submit"
            >

                Export PDF

            </button>

        </div>

        {{-- TAB TINGKAT --}}

        <ul
    class="nav nav-pills mb-4"
    id="tingkatTab"
    role="tablist">

            @foreach($kelasByTingkat as $tingkat => $listKelas)

                <li class="nav-item me-2">

                    <button
                        type="button"
                        class="nav-link {{ $loop->first ? 'active' : '' }}"
                        data-bs-toggle="pill"
                        data-bs-target="#tingkat{{ $tingkat }}"
                        type="button"
                    >

                        Kelas {{ $tingkat }}

                    </button>

                </li>

            @endforeach

        </ul>

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

                        <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden mb-4">

                            <h2 class="accordion-header">

                                <div class="d-flex align-items-center px-4 py-3 bg-white">

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

                                            <table class="table table-bordered align-middle text-center">

                                                <thead class="table-light">

                                                    <tr>

                                                        <th width="140">
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

                                                            <td class="fw-semibold">

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

                                                                <td style="min-width: 180px;">

                                                                    @if($jadwal)

                                                                        <div class="fw-semibold">

                                                                            {{ $jadwal->mapel->nama_mapel }}

                                                                        </div>

                                                                        <small class="text-muted d-block mt-1">

                                                                            {{ $jadwal->guru->pegawai->nama }}

                                                                        </small>

                                                                    @else

                                                                        -

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
@endsection

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
