@extends('layouts.app')
@section('title', 'Monitoring Jadwal Kelas ' . $kelas->nama_kelas_label)
@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Monitoring Jadwal</h3>
                    <p class="mb-0 opacity-75">Monitoring, validasi, persetujuan, dan publikasi jadwal pelajaran kelas
                        <strong>{{ $kelas->nama_kelas_label }}</strong>.
                    </p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>
    
    {{-- TOOLBAR --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center gap-3">                                               
                {{-- RIGHT --}}
                <div class="d-flex flex-wrap gap-2">
                    <div class="badge-status aktif">
                        <div class="me-2">
                            <span class="desc-add">Total Jadwal</span> Aman : 
                        </div>{{ $totalAman }}
                    </div> 
                    <div class="badge-status nonaktif">
                        <div class="me-2">
                            <span class="desc-add">Total Jadwal</span> Bentrok : 
                        </div>{{ $totalConflict }}
                    </div>                          
                </div>
                
                <div class="d-flex flex-wrap justify-content-center gap-2">                    
                    {{-- REJECT --}}
                    <button
                        class="btn btn-danger btn-sm rounded-3"
                        data-bs-toggle="modal"
                        data-bs-target="#rejectModal"
                    >
                        <i class="bi bi-x-circle me-1"></i>
                        Tolak
                    </button>
                    {{-- APPROVE --}}
                    <form
                        method="POST"
                        action="{{ route('superadmin.monitoring-jadwal.approve', $kelas->id) }}"
                    >
                        @csrf
                        <button
                            class="btn btn-success btn-sm rounded-3 btn-confirm"
                            data-title="SETUJUI SELURUH JADWAL INI?"
                            data-message="Jadwal yang sudah disetujui tidak akan bisa diubah kembali"
                            data-button="btn-danger">                            
                            <i class="bi bi-check-circle me-1"></i>
                            Setujui
                        </button>
                    </form>                
                    @if ($jadwals->isNotEmpty() && $jadwals->every(fn ($jadwal) => $jadwal->status === 'approved'))
                        {{-- PUBLISH --}}
                        <form
                            method="POST"
                            action="{{ route('superadmin.monitoring-jadwal.publish', $kelas->id) }}"
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                class="btn btn-primary btn-sm rounded-3 px-3">
                                <i class="bi bi-send-check-fill me-1"></i>
                                Publish
                            </button>
                        </form>
                    @else
                        <button 
                            type="button" 
                            class="btn btn-secondary btn-sm rounded-3 px-3" 
                            disabled
                        >
                                <i class="bi bi-send-check-fill me-1"></i>
                                <span class="desc-add">Publish</span>
                        </button> 
                    @endif       
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center w-100">  
        <div class="card border-0 rounded-top-5 rounded-bottom-0 col-md-4 col-5 pt-2 mb-0 mx-auto">
            <div class="d-flex flex-wrap justify-content-center gap-2">                    
                <a
                    href="{{ route(
                        'superadmin.monitoring-jadwal.show',
                        [
                            'kelas' => $kelas->id,
                            'view' => 'grid',
                        ]
                    ) }}"
                    class="btn btn-sm rounded-3
                    {{ $view=='grid'
                        ? 'btn-primary'
                        : 'btn-outline-primary'
                    }}"
                >
                    <i class="bi bi-grid-fill"></i>
                    <span class="ms-1 desc-add">Tampilan Grid</span>
                </a>
                <a
                    href="{{ route(
                        'superadmin.monitoring-jadwal.show',
                        [
                            'kelas' => $kelas->id,
                            'view' => 'table',
                        ]
                    ) }}"
                    class="btn btn-sm rounded-3
                    {{ $view=='table'
                        ? 'btn-primary'
                        : 'btn-outline-primary'
                    }}"
                >
                    <i class="bi bi-table"></i>
                    <span class="me-1 desc-add">Tampilan Tabel</span>
                </a>
            </div> 
        </div> 
    </div>
    {{-- GRID VIEW --}}
    @if ($view == 'grid')
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body">
            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center modern-grid-table mb-0">
                    <thead>
                        <tr class="table-header">
                            <th>
                                Jam
                            </th>
                            @foreach ($haris as $hari)
                                <th>
                                    {{ $hari->nama }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jams as $jam)
                            <tr>
                                {{-- JAM --}}
                                <td class="bg-light fw-semibold">
                                    <strong>
                                        Jam {{ $jam->jam_ke }}
                                    </strong>
                                    <br>
                                    <small>
                                        {{ substr($jam->mulai, 0, 5) }}
                                        -
                                        {{ substr($jam->selesai, 0, 5) }}
                                    </small>
                                </td>
                                {{-- SLOT --}}
                                @foreach ($haris as $hari)
                                    @php
                                        $item = $formattedJadwal[$hari->id][$jam->id] ?? null;
                                        $bgColor = '#198754'; // aman
                                        if ($item && ($conflicts[$item->id] ?? false)) {
                                            $bgColor = '#dc3545'; // bentrok
                                        }
                                    @endphp
                                    <td style="min-width: 140px;">
                                        @if ($item)
                                            <div class="text-white rounded-4 py-2 px-4"
                                                style="background: {{ $bgColor }};"
                                            >
                                                <div class="fw-bold">
                                                    {{ $item->mapel->nama_mapel ?? '-' }}
                                                </div>
                                                <div class="small opacity-90">
                                                    <i class="bi bi-person-fill me-1"></i>
                                                    {{ $item->guru->pegawai->nama ?? '-' }}
                                                </div>
                                                <br>
                                                @if ($conflicts[$item->id] ?? false)
                                                    <div class="mt-0">
                                                        <span class="badge bg-light text-danger">
                                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                            Bentrok
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="mt-0">
                                                        <span class="badge bg-light text-success">
                                                            <i class="bi bi-check-circle-fill me-1"></i>
                                                            Aman
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-center py-4 text-secondary">
                                                <i class="bi bi-dash-circle display-6"></i>
                                                <div class="small">
                                                    Tidak Ada Jadwal
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
    <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
        <div class="d-flex justify-content-between align-items-center">                
            <a href="{{ route('superadmin.monitoring-jadwal.index') }}" class="btn btn-outline-secondary rounded-3 ml-4">
                <i class="bi bi-chevron-left me-1"></i> Kembali
            </a>                
        </div>
    </div>
    @endif
    {{-- TABLE VIEW --}}
    @if ($view == 'table')
        <x-table.card
            title="Daftar Jadwal Pelajaran"
            subtitle="Monitoring seluruh jadwal kelas {{ $kelas->nama_kelas_label }}"
        >
            <x-slot:badge>
                <span class="badge bg-primary rounded-pill px-2 py-1">
                    {{ $jadwals->count() }}
                    <span class="text-badge">Jadwal</span>
                </span>
            </x-slot:badge>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="120">
                                Hari
                            </th>
                            <th width="170">
                                Jam
                            </th>
                            <th>
                                Guru
                            </th>
                            <th>
                                Mata Pelajaran
                            </th>
                            <th width="140">
                                Status
                            </th>
                            <th width="140">
                                Monitoring
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $item)
                            <tr>
                                {{-- HARI --}}
                                <td>
                                    <span class="fw-semibold small text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $item->hari->nama ?? '-' }}
                                    </span>
                                </td>
                                {{-- JAM --}}
                                <td>
                                    <div class="fw-semibold">
                                        Jam {{ $item->jamPelajaran->jam_ke }}
                                    </div>
                                    <small class="text-muted">
                                        {{ substr($item->jamPelajaran->mulai, 0, 5) }}
                                        -
                                        {{ substr($item->jamPelajaran->selesai, 0, 5) }}
                                    </small>
                                </td>
                                {{-- GURU --}}
                                <td>
                                    <div class="d-flex align-items-center">                                        
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $item->guru->pegawai->nama ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{-- MATA PELAJARAN --}}
                                <td>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-book-half text-primary me-2"></i>
                                        <span>
                                            {{ $item->mapel->nama_mapel ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                {{-- STATUS --}}
                                <td>
                                    @if ($item->status == 'submitted')
                                        <span class="badge-status pending">
                                            <i class="bi bi-hourglass-split me-2"></i>
                                            Pending
                                        </span>
                                    @elseif ($item->status == 'approved')
                                        <span class="badge-status aktif">
                                            <i class="bi bi-check-circle-fill me-2"></i>
                                            Disetujui
                                        </span>
                                    @elseif ($item->status == 'rejected')
                                        <span class="badge-status nonaktif">
                                            <i class="bi bi-x-circle-fill me-2"></i>
                                            Ditolak
                                        </span>
                                    @elseif ($item->status == 'published')
                                        <span class="badge-status verifikasi">
                                            <i class="bi bi-send-check-fill me-2"></i>
                                            Publish
                                        </span>
                                    @else
                                        <span class="badge-status draft">
                                            Belum Mengajukan
                                        </span>
                                    @endif
                                </td>
                                {{-- MONITORING --}}
                                <td>
                                    @if ($item->status == 'submitted')
                                        @if ($conflicts[$item->id] ?? false)
                                            <span class="text-danger">
                                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                Bentrok
                                            </span>
                                        @else
                                            <span class="text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                Aman
                                            </span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-calendar-x display-5 text-secondary"></i>
                                    <p class="mt-3 mb-0 text-muted">
                                        Jadwal pelajaran untuk kelas ini belum tersedia.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-table.card>
        <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
            <div class="d-flex justify-content-between align-items-center">                
                <a href="{{ route('superadmin.monitoring-jadwal.index') }}" class="btn btn-outline-secondary rounded-3 ml-4">
                    <i class="bi bi-chevron-left me-1"></i> Kembali
                </a>                
            </div>
        </div>
    @endif
</div>
<div
    class="modal fade"
    id="rejectModal"
    tabindex="-1"
>
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form
                method="POST"
                action="{{ route(
                    'superadmin.monitoring-jadwal.reject',
                    $kelas->id
                ) }}"
            >
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">
                        Reject Jadwal
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>
                </div>
                <div class="modal-body">
                    {{-- ALASAN --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Alasan Reject
                        </label>
                        <select
                            id="reject-alasan"
                            name="alasan"
                            class="form-control"
                            required
                        >
                            <option value="">
                                -- Pilih Alasan --
                            </option>
                            <option value="Bentrok Jadwal">
                                Bentrok Jadwal
                            </option>
                            <option value="Jam Terlalu Padat">
                                Jam Terlalu Padat
                            </option>
                            <option value="Slot Tidak Sesuai">
                                Slot Tidak Sesuai
                            </option>
                            <option value="Distribusi Tidak Merata">
                                Distribusi Tidak Merata
                            </option>
                            <option value="Perlu Revisi Manual">
                                Perlu Revisi Manual
                            </option>
                        </select>
                        <div class="invalid-feedback">
                            Alasan penolakan wajib dipilih.
                        </div>
                    </div>
                    {{-- HARI --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Hari Bermasalah
                        </label>
                        <select
                            id="reject-hari"
                            name="hari"
                            class="form-control"
                            required
                        >
                            <option value="">
                                -- Pilih Hari --
                            </option>
                            @foreach ($haris as $hari)
                                <option value="{{ $hari->id }}">
                                    {{ $hari->nama ?? $hari->nama_hari }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Hari bermasalah wajib dipilih.
                        </div>
                    </div>
                    {{-- JAM --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Jam Bermasalah
                        </label>
                        <select
                            id="reject-jam"
                            name="jam"
                            class="form-control"
                            required
                        >
                            <option value="">
                                -- Pilih Jam --
                            </option>
                            @foreach ($jams as $jam)
                                <option
                                    value="{{ $jam->id }}"
                                >
                                    Jam {{ $jam->jam_ke }}
                                    -
                                    {{ substr($jam->mulai,0,5) }}
                                    -
                                    {{ substr($jam->selesai,0,5) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            Jam bermasalah wajib dipilih.
                        </div>
                    </div>
                    {{-- CATATAN --}}
                    <div>
                        <label class="form-label fw-semibold">
                            Catatan Tambahan
                        </label>
                        <textarea
                            name="catatan_tambahan"
                            class="form-control"
                            rows="3"
                            placeholder="Opsional..."
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light border rounded-3"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="btn btn-danger rounded-3 btn-confirm"
                    >
                        <i class="bi bi-x-circle-fill me-2"></i>
                        Tolak Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('#rejectModal form');
    const alasan = form?.querySelector('#reject-alasan');
    const hari = form?.querySelector('#reject-hari');
    const jam = form?.querySelector('#reject-jam');
    const confirmButton = form?.querySelector('.btn-confirm');

    if (!form || !alasan || !hari || !jam || !confirmButton) {
        return;
    }

    confirmButton.addEventListener('click', function (event) {
        const fields = [
            { element: alasan, valid: alasan.value.trim() },
            { element: hari, valid: hari.value },
            { element: jam, valid: jam.value },
        ];

        const invalidField = fields.find((field) => !field.valid);

        if (invalidField) {
            event.preventDefault();
            event.stopImmediatePropagation();

            invalidField.element.classList.add('is-invalid');
            invalidField.element.focus();

            return;
        }

        fields.forEach((field) => field.element.classList.remove('is-invalid'));
    }, true);

    [alasan, hari, jam].forEach((field) => {
        field.addEventListener('change', function () {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
});
</script>
@endsection