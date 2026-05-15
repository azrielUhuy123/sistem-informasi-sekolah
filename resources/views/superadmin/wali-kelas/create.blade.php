@extends('layouts.app')

@section('title', 'Assign Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-4">

        <h4 class="fw-bold mb-0">
            Assign Wali Kelas
        </h4>

        <small class="text-muted">
            Tentukan guru sebagai wali kelas
        </small>

    </div>

    {{-- CARD --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <form
                action="{{ route('superadmin.wali-kelas.store') }}"
                method="POST"
            >

                @csrf

                <div class="row g-4">

                    {{-- GURU --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Guru
                        </label>

                        <select
                            name="guru_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih Guru --
                            </option>

                            @foreach($guru as $item)

                                <option value="{{ $item->id }}">

                                    {{ $item->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- KELAS --}}
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Kelas
                        </label>

                        <select
                            name="kelas_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih Kelas --
                            </option>

                            @foreach($kelas as $item)

                                <option value="{{ $item->id }}">

                                    {{ $item->nama_kelas }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                {{-- BUTTON --}}
                <div class="d-flex gap-2 mt-4">

                    <button class="btn btn-primary">

                        Simpan Wali Kelas

                    </button>

                    <a
                        href="{{ route('superadmin.wali-kelas.index') }}"
                        class="btn btn-light border"
                    >

                        Kembali

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection