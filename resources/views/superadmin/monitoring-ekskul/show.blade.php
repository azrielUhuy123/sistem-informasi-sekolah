@extends('layouts.app')

@section('title', 'Monitoring Jadwal Ekskul')

@section('content')

<div class="container-fluid py-4">

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);"
        >

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Monitoring Jadwal Ekskul

                    </h2>

                    <p class="mb-0 opacity-75">

                        Monitoring jadwal kegiatan ekstrakurikuler
                        <strong>{{ $ekskul->nama }}</strong>,
                        termasuk proses validasi, approval, dan publikasi jadwal.

                    </p>

                </div>

            </div>

        </div>

    </div>

    @php

        $totalJadwal = $jadwal->count();

        $totalAman = $jadwal
            ->where('bentrok', 'aman')
            ->count();

        $totalBentrok = $totalJadwal - $totalAman;

        $statusJadwal = $jadwal
            ->pluck('status')
            ->unique()
            ->implode(', ');

    @endphp

    <div class="alert alert-info border mb-4">

        <strong>Total Jadwal:</strong>
        {{ $totalJadwal }}

        |

        <strong>Aman:</strong>
        {{ $totalAman }}

        |

        <strong>Bentrok:</strong>
        {{ $totalBentrok }}

        |

        <strong>Status:</strong>
        {{ strtoupper($statusJadwal ?: '-') }}

    </div>

    @if($totalBentrok > 0)

        <div class="alert alert-danger">

            <strong>
                Terdapat jadwal yang bentrok.
            </strong>

            <br>

            Jadwal tidak dapat di-approve maupun dipublish
            sebelum diperbaiki oleh Pembina Ekskul.

        </div>

    @endif

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif

    <div class="card border-0 shadow-sm rounded-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

            <a
                href="{{ route('superadmin.monitoring-ekskul.index') }}"
                class="btn btn-outline-secondary rounded-pill px-4"
            >

                <i class="bi bi-arrow-left me-2"></i>

                Kembali

            </a>

            <div class="d-flex gap-2">

                {{-- APPROVE --}}
                <form
                    method="POST"
                    action="{{ route('superadmin.monitoring-ekskul.approve', $ekskul->id) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success rounded-pill px-4"
                        {{ !$bisaApprove ? 'disabled' : '' }}
                    >

                        <i class="bi bi-check-circle-fill me-2"></i>

                        Approve

                    </button>

                </form>

                {{-- REJECT --}}
                <button
                    type="button"
                    class="btn btn-danger rounded-pill px-4"
                    data-bs-toggle="modal"
                    data-bs-target="#modalReject"
                >

                    <i class="bi bi-x-circle-fill me-2"></i>

                    Reject

                </button>

                {{-- PUBLISH --}}
                <form
                    method="POST"
                    action="{{ route('superadmin.monitoring-ekskul.publish', $ekskul->id) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4"
                        {{ !$bisaApprove ? 'disabled' : '' }}
                    >

                        <i class="bi bi-send-check-fill me-2"></i>

                        Publish

                    </button>

                </form>

            </div>

        </div>

            {{-- TABLE --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Hari
                            </th>

                            <th>
                                Jam Mulai
                            </th>

                            <th>
                                Jam Selesai
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Bentrok
                            </th>

                            <th>
                                Catatan
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($jadwal as $item)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    {{ $item->hari->nama }}

                                </td>

                                <td>

                                    {{ substr($item->jam_mulai, 0, 5) }}

                                </td>

                                <td>

                                    {{ substr($item->jam_selesai, 0, 5) }}

                                </td>

                                <td>

                                    @if($item->status == 'draft')

                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>

                                    @elseif($item->status == 'submitted')

                                        <span class="badge bg-warning text-dark">
                                            Submitted
                                        </span>

                                    @elseif($item->status == 'approved')

                                        <span class="badge bg-success">
                                            Approved
                                        </span>

                                    @elseif($item->status == 'published')

                                        <span class="badge bg-primary">
                                            Published
                                        </span>

                                    @elseif($item->status == 'rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($item->bentrok == 'aman')

                                        <span class="badge bg-success">

                                            Aman

                                        </span>

                                    @elseif($item->bentrok == 'akademik')

                                        <span class="badge bg-danger">

                                            Bentrok Akademik

                                        </span>

                                    @elseif($item->bentrok == 'ekskul')

                                        <span class="badge bg-warning text-dark">

                                            Bentrok Ekskul

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{ $item->catatan ?? '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada pengajuan jadwal

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- MODAL REJECT --}}
<div
    class="modal fade"
    id="modalReject"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('superadmin.monitoring-ekskul.reject', $ekskul->id) }}"
            >

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        Tolak Jadwal Ekskul

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <label class="form-label">

                        Alasan Penolakan

                    </label>

                    <textarea
                        name="catatan"
                        rows="5"
                        class="form-control"
                        required
                    ></textarea>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        Tolak Jadwal

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection