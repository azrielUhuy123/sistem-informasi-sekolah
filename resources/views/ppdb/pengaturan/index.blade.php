@extends('layouts.app')

@section('title','Pengaturan PPDB')

@section('content')

<div class="container-fluid py-4">

{{-- HEADER --}}        

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    <div class="card border-0 shadow-sm">

        <div class="card-body">
            <div class="mb-4">
                <h3 class="text-primary fw-bold text-center mb-1">
                    Pengaturan PPDB
                </h3>
                <p class="text-muted text-center"></p>
            </div>
            {{-- ALERT --}}            
 
            <x-alert.flash key="error" type="danger"/>
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
                        class="form-control"
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

                <div class="mb-5">

                    <label class="form-label">

                        Status PPDB

                    </label>

                    <select
                        name="status"
                        class="form-control"
                    >
                        <option
                            value="dibuka"
                            @selected(old('status', optional($setting)->status) == 'dibuka')
                        >
                            Dibuka
                        </option>

                        <option
                            value="ditutup"
                            @selected(old('status', optional($setting)->status) == 'ditutup')
                        >
                            Ditutup
                        </option>

                    </select>

                </div>
                <div class="text-end mb-4">
                    <button
                        type="submit"
                        class="btn btn-primary rounded-3">

                        Simpan <i class="bi bi-save ms-1"></i>

                    </button>
                </div>
            </form>

        </div>

    </div>

</div>

@endsection