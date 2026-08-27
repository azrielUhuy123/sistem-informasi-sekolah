@extends('layouts.app')

@section('title', 'Jadwal Resmi')

@section('content')

<div class="container-fluid py-3">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Jadwal Resmi Sekolah</h3>
                    <p class="mb-0 opacity-75">Jadwal resmi sekolah yang telah dipublikasikan dan siap di ekspor PDF.</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
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
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body pt-4">
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
                            class="btn btn-outline-primary rounded-3 ms-3 me-2"
                            id="btnSelectAll"
                        >
                            <i class="bi bi-check2-square me-1"></i>
                            <span class="desc-add">Pilih</span>Semua
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-secondary rounded-3 me-2"
                            id="btnClearAll"
                        >
                            <i class="bi bi-x-square me-1"></i>
                            <span class="desc-add">Kosongkan</span>
                        </button>
                        <button
                            class="btn btn-danger rounded-3"
                            type="submit"
                        >
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                            <span class="desc-add">Export</span>PDF
                        </button>
                    </div>
                </div>
            </div>
            {{-- ================= TAB TINGKAT ================= --}}
            <div class="card-body py-3">
                <ul
                    class="nav nav-pills justify-content-center gap-2"
                    id="tingkatTab"
                    role="tablist"
                >
                    @foreach($kelasByTingkat as $tingkat => $listKelas)
                        @php
                            $tingkatKey = \Illuminate\Support\Str::slug((string) $tingkat);
                        @endphp
                        <li class="nav-item me-2">
                            <button
                                class="nav-link rounded-3 fw-semibold {{ $loop->first ? 'active' : '' }}"
                                data-bs-toggle="pill"
                                data-bs-target="#tingkat{{ $tingkatKey }}"
                                type="button"
                            >
                                <span class="desc-add">Kelas</span> {{ $tingkat }}
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
                @php
                    $tingkatKey = \Illuminate\Support\Str::slug((string) $tingkat);
                @endphp
                <div
                    class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                    id="tingkat{{ $tingkatKey }}">
                    <div
                        class="jadwal-kelas-list"
                        id="accordionTingkat{{ $tingkatKey }}">
                        @foreach($listKelas as $k)
                            <div class="jadwal-kelas-item">
                                <div class="d-flex align-items-center gap-3 p-3">
                                    {{-- CHECKBOX --}}
                                    <div class="form-check mb-0">
                                        <input
                                            class="form-check-input kelas-checkbox"
                                            type="checkbox"
                                            name="kelas_ids[]"
                                            value="{{ $k->id }}">
                                    </div>

                                    {{-- BUTTON --}}
                                    <button
                                        class="btn flex-grow-1 text-start rounded-3 d-flex align-items-center justify-content-between gap-3 p-3 jadwal-kelas-trigger"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#kelas{{ $k->id }}"
                                        aria-controls="kelas{{ $k->id }}"
                                        aria-expanded="false"
                                        type="button">
                                        <span class="d-flex align-items-center gap-3 min-w-0">
                                            <span class="d-block fw-semibold text-truncate mb-1">
                                                Kelas {{ $k->nama_kelas_label }}
                                            </span>
                                            <small class="d-block text-muted text-truncate">
                                                <span class="desc-add">Jadwal resmi kelas {{ $k->nama_kelas_label }}</span>
                                            </small>
                                        </span>
                                        <span class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge-status verifikasi">
                                                <i class="bi bi-send-check-fill me-2"></i>
                                                Published
                                            </span>
                                            <i class="bi bi-chevron-down"></i>
                                        </span>
                                    </button>
                                </div>

                                <div
                                    id="kelas{{ $k->id }}"
                                    class="accordion-collapse collapse"
                                    data-bs-parent="#accordionTingkat{{ $tingkatKey }}"
                                >
                                    <div class="border-top p-3">
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
                                                                        ->where('status', 'published')
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

<style>

.jadwal-kelas-list {
    width: 100%;
}

.jadwal-kelas-item {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: .75rem;
    overflow: hidden;
    margin-bottom: .75rem;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.jadwal-kelas-item:has(.collapse.show) {
    border-color: #b8d4f5!important;
    box-shadow: 0 .25rem .75rem rgba(15, 76, 129, .06);
}

.jadwal-kelas-trigger {
    border: 0;
    color: #212529;
    transition: background-color .2s ease;
}

.jadwal-kelas-trigger:hover,
.jadwal-kelas-trigger:focus-visible {
    background-color: #f8fbff;
}

.jadwal-kelas-trigger:focus-visible {
    box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .15);
}

.jadwal-kelas-count {
    white-space: nowrap;
}

.jadwal-kelas-chevron {
    transition: transform .2s ease;
}

@media (max-width: 575.98px) {
    .jadwal-kelas-trigger {
        padding: .75rem!important;
    }

    .jadwal-kelas-trigger > .d-flex:first-child {
        gap: .6rem!important;
    }

    .jadwal-kelas-count {
        font-size: .75rem;
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