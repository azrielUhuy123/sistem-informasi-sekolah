@extends('layouts.tu')

@section('title', 'Naik Kelas')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">
        Naik Kelas - Tahun Ajaran {{ $tahunAktif->tahun }}
    </h4>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('tu.penempatan-siswa.naik-kelas.index') }}">

                <div class="mb-3">
                    <label class="form-label">Pilih Tingkat</label>
                    <select name="tingkat"
                            class="form-select"
                            required>
                        <option value="">-- Pilih Tingkat --</option>
                        @foreach ($tingkatList as $tingkat)
                            <option value="{{ $tingkat }}">
                                Tingkat {{ $tingkat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-primary">
                    Lanjut
                </button>

            </form>

        </div>
    </div>

    @if(isset($rombels))

        <hr>

        <h5 class="mt-4">Rombel Tingkat {{ request('tingkat') }}</h5>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Rombel</th>
                    <th>Jumlah Siswa Aktif</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rombels as $rombel)
                    <tr>
                        <td>{{ $rombel->nama_kelas }}</td>
                        <td>{{ $rombel->siswa_count }}</td>
                        <td>
                            @if($rombel->siswa_count > 0)
                                <a href="{{ route('tu.penempatan-siswa.naik-kelas.mapping', [
                                        'tingkat' => request('tingkat')
                                    ]) }}"
                                class="btn btn-sm btn-primary">
                                    Mapping
                                </a>
                            @else
                                <span class="text-muted">Kosong</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3"
                            class="text-center text-muted">
                            Tidak ada rombel
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @endif

</div>

@endsection
