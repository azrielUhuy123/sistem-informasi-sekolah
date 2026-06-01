@extends('layouts.app')

@section('title', 'Jadwal Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Jadwal Ekskul

        </h3>

        <p class="text-muted">

            {{ $pembina->ekskul->nama }}

        </p>

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