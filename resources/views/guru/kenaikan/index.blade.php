@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

@section('content')

<div class="container-fluid py-3">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Kenaikan Kelas</h3>
                    <p class="mb-0 opacity-75">Kelola keputusan kenaikan kelas siswa</p>
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

    <div class="card border-0 shadow-sm">

            <form
                action="{{ route('guru.kenaikan.store') }}"
                method="POST"
            >

                @csrf

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="80">

                                    No

                                </th>

                                <th>

                                    Nama Siswa

                                </th>

                                <th width="220">

                                    Status

                                </th>

                                <th>

                                    Catatan

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($penempatan as $i => $p)

                                <tr>

                                    <td>

                                        {{ $i + 1 }}

                                    </td>

                                    <td>

                                        {{ $p->siswa->nama }}

                                    </td>

                                    <td>

                                        <select
                                            name="keputusan[{{ $p->siswa->id }}][status]"
                                            class="form-select"
                                        >

                                            <option
                                                value="naik"
                                                {{
                                                    optional($p->keputusan)->status == 'naik'
                                                    ? 'selected'
                                                    : ''
                                                }}
                                            >

                                                Naik Kelas

                                            </option>

                                            <option
                                                value="tinggal"
                                                {{
                                                    optional($p->keputusan)->status == 'tinggal'
                                                    ? 'selected'
                                                    : ''
                                                }}
                                            >

                                                Tinggal Kelas

                                            </option>

                                        </select>

                                    </td>

                                    <td>

                                        <input
                                            type="text"
                                            name="keputusan[{{ $p->siswa->id }}][catatan]"
                                            class="form-control"
                                            value="{{ optional($p->keputusan)->catatan }}"
                                        >

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="mt-4 d-flex justify-content-end">

                    <button class="btn btn-success px-4">

                        Simpan Keputusan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection