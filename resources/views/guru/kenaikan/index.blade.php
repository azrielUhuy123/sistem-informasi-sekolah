@extends('layouts.app')

@section('title', 'Kenaikan Kelas')

@section('content')

<div class="container-fluid py-3">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <h4 class="fw-bold mb-4">

                Keputusan Kenaikan Kelas

            </h4>

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