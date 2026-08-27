@extends('layouts.app')

@section('title', 'Assign Wali Kelas')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="mb-3">

                <h3 class="text-primary fw-bold text-center mb-1">
                    Assign Wali Kelas
                </h3>

                <p class="text-muted text-center">
                    Tentukan guru sebagai wali kelas pada rombel aktif.
                </p>

            </div>

            {{-- ALERT --}}
            <x-alert.flash key="error" type="danger"/>

            <form
                action="{{ route('superadmin.wali-kelas.store') }}"
                method="POST"
            >

                @csrf

                <div class="row g-4 mb-5">

                    {{-- GURU --}}
                    <div>

                        <label class="form-label">
                            Guru
                        </label>

                        <select
                            name="guru_id"
                            class="form-control"
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
                    <div>

                        <label class="form-label">
                            Kelas / Rombel
                        </label>

                        <select
                            name="kelas_id"
                            class="form-control"
                            required
                        >
                            <option value="">
                                -- Pilih Kelas --
                            </option>

                            @foreach($kelas as $item)

                                <option value="{{ $item->id }}">
                                    {{ $item->nama_kelas_label }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">

                    <a
                        href="{{ route('superadmin.wali-kelas.index') }}"
                        class="btn btn-outline-secondary rounded-3"
                    >
                        <i class="bi bi-chevron-left me-1"></i>
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-3"
                    >
                        Simpan
                        <i class="bi bi-save ms-1"></i>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection