@extends('layouts.app')

@section('title', 'Edit Jadwal Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Edit Jadwal Ekskul

        </h3>

        <p class="text-muted mb-0">

            Perbaiki jadwal yang ditolak oleh Kepala Sekolah

        </p>

    </div>

    @if($jadwal->catatan)

        <div class="alert alert-danger">

            <strong>Catatan Kepala Sekolah:</strong>

            <br>

            {{ $jadwal->catatan }}

        </div>

    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('guru.ekskul.jadwal.update', $jadwal->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

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
                                    {{ $jadwal->hari_id == $h->id ? 'selected' : '' }}
                                >

                                    {{ $h->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Jam Mulai

                        </label>

                        <input
                            type="time"
                            name="jam_mulai"
                            class="form-control"
                            value="{{ substr($jadwal->jam_mulai,0,5) }}"
                            required
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Jam Selesai

                        </label>

                        <input
                            type="time"
                            name="jam_selesai"
                            class="form-control"
                            value="{{ substr($jadwal->jam_selesai,0,5) }}"
                            required
                        >

                    </div>

                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('guru.ekskul.validasi.index') }}"
                        class="btn btn-light"
                    >

                        Kembali

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        Simpan & Kirim Ulang

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection