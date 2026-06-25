@extends('layouts.app')

@section('title', 'Pengampu Mapel')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Pengampu Mapel
            </h4>

            <small class="text-muted">
                Assign guru ke kelas dan mapel
            </small>
        </div>

    </div>

    {{-- FORM --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                action="{{ route('superadmin.pengampu-mapel.store') }}"
                method="POST"
            >

                @csrf

                <div class="row">

                    <div class="col-md-4">

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

                    <div class="col-md-4">

                        <label class="form-label">
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

                            @foreach($kelas as $k)

                                <option value="{{ $k->id }}">
                                    {{ $k->nama_kelas }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Mata Pelajaran
                        </label>

                        <select
                            name="mata_pelajaran_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih Mapel --
                            </option>

                            @foreach($mapel as $m)

                                <option value="{{ $m->id }}">
                                    {{ $m->nama_mapel }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="mt-4">

                    <button class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pengampu as $p)

                        <tr>

                            <td>
                                {{ $p->guru->pegawai->nama }}
                            </td>

                            <td>
                                {{ $p->kelas->nama_kelas }}
                            </td>

                            <td>
                                {{ $p->mapel->nama_mapel }}
                            </td>

                            <td>

                                <form
                                    action="{{ route('superadmin.pengampu-mapel.destroy', $p->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus data?')"
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
                                class="text-center text-muted"
                            >
                                Belum ada pengampu mapel
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection