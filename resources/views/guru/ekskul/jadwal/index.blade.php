@extends('layouts.app')

@section('title', 'Jadwal Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Jadwal</h3>
                    <p class="mb-0 opacity-75">Kelola jadwal kegiatan ekskul</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

        </div>

    @endif

        
    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                action="{{ route('guru.ekskul.jadwal.store') }}"
                method="POST"
            >

                @csrf

                <div class="row g-3">

                    <div class="col-md-4">

                        <label class="form-label">

                            Hari

                        </label>

                        <select
                            name="hari_id"
                            class="form-select"
                            required
                        >

                            @foreach($hari as $h)

                                <option
                                    value="{{ $h->id }}"
                                >

                                    {{ $h->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">

                            Jam Mulai

                        </label>

                        <input
                            type="time"
                            name="jam_mulai"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">

                            Jam Selesai

                        </label>

                        <input
                            type="time"
                            name="jam_selesai"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">

                            &nbsp;

                        </label>

                        <button
                            class="btn btn-dark w-100"
                        >

                            Simpan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <table class="table">

                <thead>

                    <tr>

                        <th>Hari</th>

                        <th>Jam</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($jadwal as $j)

                        <tr>

                            <td>

                                {{ $j->hari->nama }}

                            </td>

                            <td>

                                {{ $j->jam_mulai }}
                                -
                                {{ $j->jam_selesai }}

                            </td>

                            <td>

                                {{ strtoupper($j->status) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="3"
                                class="text-center"
                            >

                                Belum ada jadwal

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection