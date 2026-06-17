@extends('layouts.app')

@section('title','Penempatan Kelas')

@section('content')

<div class="container-fluid py-4">

    <div class="card">

        <div class="card-header">

            Penempatan Siswa Baru

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

                        <th>No</th>

                        <th>NIS</th>

                        <th>Nama</th>

                        <th>Kelas Saat Ini</th>

                        <th>Penempatan</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($data as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->siswa?->nis }}
                            </td>

                            <td>
                                {{ $item->nama }}
                            </td>

                            <td>
                                {{ $item->siswa?->kelas?->nama_kelas ?? '-' }}
                            </td>

                            <td>

                                <form
                                    method="POST"
                                    action="{{ route('tu.ppdb.penempatan-kelas.simpan',$item) }}"
                                >

                                    @csrf

                                    <div class="d-flex gap-2">

                                        <select
                                            name="kelas_id"
                                            class="form-select"
                                            required
                                        >

                                            <option value="">

                                                Pilih Kelas

                                            </option>

                                            @foreach($kelas as $k)

                                                <option
                                                    value="{{ $k->id }}"
                                                >

                                                    {{ $k->nama_kelas }}

                                                </option>

                                            @endforeach

                                        </select>

                                        <button
                                            class="btn btn-primary"
                                        >

                                            Simpan

                                        </button>

                                    </div>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            {{ $data->links() }}

        </div>

    </div>

</div>

@endsection