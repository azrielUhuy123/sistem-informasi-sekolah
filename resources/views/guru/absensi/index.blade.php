@extends('layouts.app')

@section('title', 'Absensi')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Absensi Kelas {{ $kelas->nama_kelas }}

        </h4>

    </div>

    <form
        action="{{ route('guru.absensi.store') }}"
        method="POST"
    >

        @csrf

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <label class="form-label">

                            Tanggal

                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="{{ date('Y-m-d') }}"
                            required
                        >

                    </div>

                </div>
                <div class="mt-4">

                    <button class="btn btn-primary">

                        Simpan Absensi

                    </button>

                </div>
            </div>


        </div>

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="70">
                                    No
                                </th>

                                <th>
                                    Nama Siswa
                                </th>

                                <th width="220">
                                    Status
                                </th>

                                <th>
                                    Keterangan
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
                                            name="absensi[{{ $p->siswa->id }}][status]"
                                            class="form-select"
                                        >

                                            <option value="hadir">
                                                Hadir
                                            </option>

                                            <option value="izin">
                                                Izin
                                            </option>

                                            <option value="sakit">
                                                Sakit
                                            </option>

                                            <option value="alpha">
                                                Alpha
                                            </option>

                                        </select>

                                    </td>

                                    <td>

                                        <input
                                            type="text"
                                            name="absensi[{{ $p->siswa->id }}][keterangan]"
                                            class="form-control"
                                            placeholder="Opsional"
                                        >

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>



    </form>

</div>

@endsection