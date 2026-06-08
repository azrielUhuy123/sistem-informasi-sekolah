@extends('layouts.app')

@section('title','Pengaturan PPDB')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Pengaturan PPDB
            </h5>

        </div>

        <div class="card-body">

            <form
                action="{{ route('tu.ppdb.pengaturan.store') }}"
                method="POST"
            >

                @csrf

                <div class="mb-3">

                    <label class="form-label">

                        Tahun Ajaran

                    </label>

                    <select
                        name="tahun_ajaran_id"
                        class="form-select"
                        required
                    >

                        @foreach($tahunAjaran as $tahun)

                            <option
                                value="{{ $tahun->id }}"
                                @selected(
                                    optional($setting)->tahun_ajaran_id == $tahun->id
                                )
                            >

                                {{ $tahun->tahun }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Tanggal Mulai

                    </label>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        class="form-control"
                        value="{{ optional($setting)->tanggal_mulai }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Tanggal Selesai

                    </label>

                    <input
                        type="date"
                        name="tanggal_selesai"
                        class="form-control"
                        value="{{ optional($setting)->tanggal_selesai }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Kuota

                    </label>

                    <input
                        type="number"
                        name="kuota"
                        class="form-control"
                        value="{{ optional($setting)->kuota }}"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Status PPDB

                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option
                            value="dibuka"
                            @selected(
                                optional($setting)->status == 'dibuka'
                            )
                        >
                            Dibuka
                        </option>

                        <option
                            value="ditutup"
                            @selected(
                                optional($setting)->status == 'ditutup'
                            )
                        >
                            Ditutup
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    Simpan Pengaturan

                </button>

            </form>

        </div>

    </div>

</div>

@endsection