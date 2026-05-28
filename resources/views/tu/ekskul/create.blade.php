@extends('layouts.app')

@section('title', 'Tambah Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Tambah Ekskul

        </h3>

        <p class="text-muted mb-0">

            Tambahkan kegiatan ekstrakurikuler baru

        </p>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('tu.ekskul.store') }}"
                method="POST"
            >

                @csrf

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

                            <option value="">

                                -- Pilih Guru --

                            </option>

                            @foreach($guru as $g)

                                <option
                                    value="{{ $g->id }}"
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

                            <option>Senin</option>
                            <option>Selasa</option>
                            <option>Rabu</option>
                            <option>Kamis</option>
                            <option>Jumat</option>
                            <option>Sabtu</option>

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
                            value="0"
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

                            <option value="aktif">

                                Aktif

                            </option>

                            <option value="nonaktif">

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
                        ></textarea>

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