@extends('layouts.app')

@section('title', 'Assign Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h2 class="fw-bold mb-2">

                        Assign Wali Kelas

                    </h2>

                    <p class="mb-0 opacity-75">

                        Tentukan guru sebagai wali kelas pada rombel aktif.

                    </p>

                </div>

                <a href="{{ route('superadmin.wali-kelas.index') }}"
                    class="btn btn-light rounded-pill px-4 mt-3 mt-lg-0">

                    <i class="bi bi-arrow-left me-2"></i>

                    Kembali

                </a>

            </div>

        </div>

    </div>

    {{-- ================= FORM ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 px-4">

            <h5 class="fw-bold mb-1">

                <i class="bi bi-person-plus-fill text-primary me-2"></i>

                Form Penugasan Wali Kelas

            </h5>

            <small class="text-muted">

                Pilih guru dan rombel yang akan menjadi tanggung jawabnya.

            </small>

        </div>

        <div class="card-body p-4">

            <form
                action="{{ route('superadmin.wali-kelas.store') }}"
                method="POST">

                @csrf

                <div class="row g-4">

                    {{-- Guru --}}
                    <div class="col-lg-6">

                        <label class="form-label fw-semibold">

                            Guru

                        </label>

                        <select
                            name="guru_id"
                            class="form-select"
                            required>

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

                    {{-- Kelas --}}
                    <div class="col-lg-6">

                        <label class="form-label fw-semibold">

                            Kelas / Rombel

                        </label>

                        <select
                            name="kelas_id"
                            class="form-select"
                            required>

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

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('superadmin.wali-kelas.index') }}"
                        class="btn btn-light border rounded-pill px-4">

                        <i class="bi bi-x-circle me-2"></i>

                        Batal

                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4">

                        <i class="bi bi-check-circle-fill me-2"></i>

                        Simpan Penugasan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<style>

.form-select{

    border-radius:12px;
    min-height:48px;
    border:1px solid #dbe4f0;

}

.form-select:focus{

    border-color:#2563eb;
    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 15px 40px rgba(15,76,129,.08)!important;

}

.btn-primary{

    border:none;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

}

.btn-primary:hover{

    background:linear-gradient(135deg,#1d4ed8,#1e40af);

}

</style>

@endsection