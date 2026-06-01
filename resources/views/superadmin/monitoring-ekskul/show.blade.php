@extends('layouts.app')

@section('title', 'Monitoring Jadwal Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Monitoring Jadwal Ekskul
        </h3>

        <p class="text-muted mb-0">
            {{ $ekskul->nama }}
        </p>

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

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            {{-- ACTION --}}
            <div class="d-flex gap-2 mb-4">

                {{-- APPROVE --}}
                <form
                    method="POST"
                    action="{{ route('superadmin.monitoring-ekskul.approve', $ekskul->id) }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                        {{ !$bisaApprove ? 'disabled' : '' }}
                    >

                        <i class="bi bi-check-circle"></i>

                        Approve

                    </button>

                </form>

                {{-- REJECT --}}
                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#modalReject"
                >

                    <i class="bi bi-x-circle"></i>

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
                        class="btn btn-primary"
                        {{ !$bisaApprove ? 'disabled' : '' }}
                    >

                        <i class="bi bi-send-check"></i>

                        Publish

                    </button>

                </form>

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