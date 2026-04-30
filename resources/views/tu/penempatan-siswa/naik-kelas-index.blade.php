@extends('layouts.tu')

@section('title', 'Naik Kelas')

@section('content')

<div class="container-fluid">

    {{-- 🔷 HEADER --}}
    <div class="mb-4">
        <h4 class="mb-1">Naik Kelas</h4>

        <small class="text-muted">
            Tahun Ajaran {{ $tahunAktif->tahun }}

            {!! match($tahunAktif->status) {
                'aktif'    => '<span class="badge bg-success ms-2">AKTIF</span>',
                'transisi' => '<span class="badge bg-warning text-dark ms-2">TRANSISI</span>',
                'selesai'  => '<span class="badge bg-secondary ms-2">SELESAI</span>',
            } !!}
        </small>
    </div>

    {{-- 🔒 WARNING --}}
    @if($tahunAktif->status !== 'transisi')
        <div class="alert alert-warning">
            Naik kelas hanya bisa dilakukan saat <b>masa transisi</b>.
        </div>
    @endif

    {{-- 🔷 FORM PILIH TINGKAT --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">

            <form method="GET"
                  action="{{ route('tu.penempatan-siswa.naik-kelas.index') }}"
                  {{ $tahunAktif->status !== 'transisi' ? 'onsubmit=return false;' : '' }}>

                <div class="row align-items-end">

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pilih Tingkat</label>

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

    {{-- 🔷 LIST ROMBEL --}}
    @if(isset($rombels))

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        Rombel Tingkat {{ request('tingkat') }}
                    </h5>

                    @if($rombels->count() && $tahunAktif->status === 'transisi')
                        <a href="{{ route('tu.penempatan-siswa.naik-kelas.mapping', [
                            'tingkat' => request('tingkat')
                        ]) }}"
                        class="btn btn-success btn-sm">
                            Lanjut ke Mapping
                        </a>
                    @endif
                </div>

                {{-- EMPTY STATE --}}
                @if(!$rombels->count())
                    <div class="text-center text-muted py-4">
                        Tidak ada rombel pada tingkat ini
                    </div>
                @else

                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Rombel</th>
                            <th width="200">Jumlah Siswa Aktif</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($rombels as $rombel)
                            <tr>
                                <td>{{ $rombel->nama_kelas }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $rombel->siswa_count }} siswa
                                    </span>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                @endif

            </div>
        </div>

    @endif

</div>

@endsection