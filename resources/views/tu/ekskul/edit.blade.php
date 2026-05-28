@extends('layouts.app')

@section('title', 'Edit Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Edit Ekskul

        </h3>

        <p class="text-muted mb-0">

            Perbarui data kegiatan ekstrakurikuler

        </p>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('tu.ekskul.update', $ekskul->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- NAMA --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Nama Ekskul

                        </label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            value="{{ $ekskul->nama }}"
                            required
                        >

                    </div>

                    {{-- PEMBINA --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Guru Pembina

                        </label>

                        <select
                            name="guru_id"
                            class="form-select"
                        >

                            @foreach($guru as $g)

                                <option
                                    value="{{ $g->id }}"
                                    {{ $ekskul->guru_id == $g->id ? 'selected' : '' }}
                                >

                                    {{ $g->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- HARI --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Hari

                        </label>

                        <select
                            name="hari"
                            class="form-select"
                        >

                            <option
                                value="Senin"
                                {{ $ekskul->hari == 'Senin' ? 'selected' : '' }}
                            >

                                Senin

                            </option>
                            <option
                                value="Selasa"
                                {{ $ekskul->hari == 'Selasa' ? 'selected' : '' }}
                            >

                                Selasa

                            </option>
                            <option
                                value="Rabu"
                                {{ $ekskul->hari == 'Rabu' ? 'selected' : '' }}
                            >

                                Rabu

                            </option>
                            <option
                                value="Kamis"
                                {{ $ekskul->hari == 'Kamis' ? 'selected' : '' }}
                            >

                                Kamis

                            </option>
                            <option
                                value="Jumat"
                                {{ $ekskul->hari == 'Jumat' ? 'selected' : '' }}
                            >

                                Jumat

                            </option>
                            <option
                                value="Sabtu"
                                {{ $ekskul->hari == 'Sabtu' ? 'selected' : '' }}
                            >

                                Sabtu

                            </option>

                        </select>

                    </div>

                    {{-- JAM MULAI --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Jam Mulai

                        </label>

                        <input
                            type="time"
                            name="jam_mulai"
                            class="form-control"
                            value="{{ $ekskul->jam_mulai }}"
                        >

                    </div>

                    {{-- JAM SELESAI --}}
                    <div class="col-md-4">

                        <label class="form-label">

                            Jam Selesai

                        </label>

                        <input
                            type="time"
                            name="jam_selesai"
                            class="form-control"
                            value="{{ $ekskul->jam_selesai }}"
                        >

                    </div>

                    {{-- KUOTA --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Kuota

                        </label>

                        <input
                            type="number"
                            name="kuota"
                            class="form-control"
                            value="{{ $ekskul->kuota }}"
                        >

                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6">

                        <label class="form-label">

                            Status

                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option
                                value="aktif"
                                {{ $ekskul->status == 'aktif' ? 'selected' : '' }}
                            >

                                Aktif

                            </option>

                            <option
                                value="nonaktif"
                                {{ $ekskul->status == 'nonaktif' ? 'selected' : '' }}
                            >

                                Nonaktif

                            </option>

                        </select>

                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="col-12">

                        <label class="form-label">

                            Deskripsi

                        </label>

                        <textarea
                            name="deskripsi"
                            rows="5"
                            class="form-control"
                        >{{ $ekskul->deskripsi }}</textarea>

                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="mt-4 d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('tu.ekskul.index') }}"
                        class="btn btn-light"
                    >

                        Kembali

                    </a>

                    <button class="btn btn-dark">

                        Simpan Ekskul

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection