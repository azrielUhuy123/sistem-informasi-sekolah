@extends('layouts.app')

@section('title', 'Pembina Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Pembina Ekskul

        </h3>

        <p class="text-muted mb-0">

            Assign guru pembina ekstrakurikuler

        </p>

    </div>

    {{-- FORM --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                action="{{ route('superadmin.pembina-ekskul.store') }}"
                method="POST"
            >

                @csrf

                <div class="row g-3 align-items-end">

                    <div class="col-md-5">

                        <label class="form-label">

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

                            @foreach($guru as $g)

                                <option value="{{ $g->id }}">

                                    {{ $g->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-5">

                        <label class="form-label">

                            Ekskul

                        </label>

                        <select
                            name="ekskul_id"
                            class="form-select"
                            required
                        >

                            <option value="">

                                -- Pilih Ekskul --

                            </option>

                            @foreach($ekskul as $e)

                                <option value="{{ $e->id }}">

                                    {{ $e->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-primary w-100">

                            Simpan

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>No</th>
                            <th>Guru</th>
                            <th>Ekskul</th>
                            <th width="120">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($pembina as $i => $p)

                            <tr>

                                <td>

                                    {{ $i + 1 }}

                                </td>

                                <td>

                                    {{ $p->guru->pegawai->nama }}

                                </td>

                                <td>

                                    {{ $p->ekskul->nama }}

                                </td>

                                <td>

                                    <form
                                        action="{{ route('superadmin.pembina-ekskul.destroy', $p->id) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada pembina ekskul

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection