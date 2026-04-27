@extends('layouts.tu')

@section('title', 'Naik Kelas')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">
        Naik Kelas - Tahun Ajaran {{ $tahunAktif->tahun }}
    </h4>

    {{-- STATUS WARNING --}}
    @if($tahunAktif->status !== 'transisi')
        <div class="alert alert-warning">
            Naik kelas hanya bisa dilakukan saat masa <b>transisi</b>.
        </div>
    @endif

    {{-- FORM PILIH TINGKAT --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('tu.penempatan-siswa.naik-kelas.index') }}">

                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Tingkat</label>
                        <select name="tingkat"
                                class="form-select"
                                {{ $tahunAktif->status !== 'transisi' ? 'disabled' : '' }}
                                required>
                            <option value="">-- Pilih Tingkat --</option>
                            @foreach ($tingkatList as $tingkat)
                                <option value="{{ $tingkat }}"
                                    {{ request('tingkat') == $tingkat ? 'selected' : '' }}>
                                    Tingkat {{ $tingkat }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100"
                            {{ $tahunAktif->status !== 'transisi' ? 'disabled' : '' }}>
                            Lanjut
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    {{-- LIST ROMBEL --}}
    @if(isset($rombels))

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        Rombel Tingkat {{ request('tingkat') }}
                    </h5>

                    @if($rombels->count())
                        <a href="{{ route('tu.penempatan-siswa.naik-kelas.mapping', [
                            'tingkat' => request('tingkat')
                        ]) }}"
                        class="btn btn-success btn-sm"
                        {{ $tahunAktif->status !== 'transisi' ? 'disabled' : '' }}>
                            Lanjut ke Mapping
                        </a>
                    @endif
                </div>

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Rombel</th>
                            <th width="200">Jumlah Siswa Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rombels as $rombel)
                            <tr>
                                <td>{{ $rombel->nama_kelas }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $rombel->siswa_count }} siswa
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"
                                    class="text-center text-muted">
                                    Tidak ada rombel
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    @endif

</div>

@endsection