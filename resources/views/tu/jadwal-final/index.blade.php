@extends('layouts.app')

@section('title', 'Jadwal Final')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Jadwal Final</h3>
                    <p class="mb-0 opacity-75">Melihat jadwal final pelajaran sekolah</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>


    <form
        action="{{ route('tu.jadwal-final.export') }}"
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
            role="tablist"
        >

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
                    id="tingkat{{ $tingkat }}"
                >

                    <div
                        class="accordion"
                        id="accordionTingkat{{ $tingkat }}"
                    >

                        @foreach($listKelas as $k)

                            <div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden">

                                <h2 class="accordion-header">

                                    <div class="d-flex align-items-center bg-white px-3">

                                        {{-- CHECKBOX --}}
                                        <div class="form-check me-3">

                                            <input
                                                class="form-check-input kelas-checkbox"
                                                type="checkbox"
                                                name="kelas_ids[]"
                                                value="{{ $k->id }}"
                                            >

                                        </div>

                                        {{-- ACCORDION BUTTON --}}
                                        <button
                                            class="accordion-button collapsed fw-bold shadow-none"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#kelas{{ $k->id }}"
                                        >

                                            {{ $k->nama_kelas }}

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

@endsection