@extends('layouts.tu')

@section('title', 'Mapping Naik Kelas')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">
        Mapping Naik Kelas Tingkat {{ $tingkatAsal }}
        → Tingkat {{ $tingkatTujuan }}
    </h4>

    <form method="POST"
          action="{{ route('tu.penempatan-siswa.naik-kelas') }}">
        @csrf

        <input type="hidden" name="tingkat_asal"
               value="{{ $tingkatAsal }}">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Rombel Asal</th>
                    <th>Jumlah Siswa</th>
                    <th>Rombel Tujuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rombelsAsal as $rombel)
                    <tr>
                        <td>{{ $rombel->nama_kelas }}</td>
                        <td>{{ $rombel->siswa_count }}</td>
                        <td>
                            <select name="mapping[{{ $rombel->id }}]"
                                    class="form-select"
                                    required>
                                <option value="">-- Pilih --</option>
                                @foreach ($rombelsTujuan as $tujuan)
                                    <option value="{{ $tujuan->id }}">
                                        {{ $tujuan->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-success">
            Preview & Proses
        </button>

    </form>

</div>

@endsection
