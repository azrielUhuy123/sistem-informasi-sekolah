@extends('layouts.app')

@section('title', 'Mapping Naik Kelas')

@section('content')
<div class="container-fluid">

    {{-- 🔷 HEADER --}}
    <div class="mb-4">
        <h4 class="mb-1">Mapping Naik Kelas</h4>

        <small class="text-muted">
            Tingkat {{ $tingkatAsal }} → {{ $tingkatTujuan }}

            {!! match($tahunAktif->status) {
                'aktif'   => '<span class="badge bg-success ms-2">AKTIF</span>',
                'draft'   => '<span class="badge bg-dark ms-2">DRAFT</span>',
                'selesai' => '<span class="badge bg-secondary ms-2">SELESAI</span>',
            } !!}
        </small>
    </div>

    {{-- 🔒 WARNING --}}
    @if($tahunAktif->status !== 'aktif')
        <div class="alert alert-warning">
            Proses naik kelas hanya dapat dilakukan saat <b>tahun ajaran aktif</b>.
        </div>
    @endif

    <form method="POST"
          action="{{ route('tu.penempatan-siswa.naik-kelas') }}"
          {{ $tahunAktif->status !== 'aktif' ? 'onsubmit=return false;' : '' }}>
        @csrf

        <input type="hidden" name="tingkat_asal" value="{{ $tingkatAsal }}">

        {{-- 🔁 LOOP ROMBEL --}}
        @foreach($rombelsAsal as $rombel)

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">

                {{-- HEADER ROMBEL --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0">{{ $rombel->nama_kelas }}</h5>
                        <small class="text-muted">Rombel asal</small>
                    </div>

                    <span class="badge bg-primary">
                        {{ $rombel->siswa_count ?? $rombel->siswaAktif->count() ?? 0 }} siswa
                    </span>
                </div>

                {{-- SELECT TUJUAN --}}
                <div class="mb-3">
                    <div class="alert alert-info mb-3">
                        Sistem akan otomatis memindahkan:
                        <b>{{ $tingkatAsal }} → {{ $tingkatTujuan }}</b>
                    </div>
                </div>

                {{-- TOOLBAR --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        Pilih siswa yang akan diproses
                    </small>

                    <button type="button"
                            class="btn btn-sm btn-outline-secondary"
                            onclick="toggleAll('{{ $rombel->id }}')"
                            {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}>
                        Pilih Semua
                    </button>
                </div>

                {{-- LIST SISWA --}}
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Nama Siswa</th>
                            </tr>
                        </thead>
                        <tbody>

                        @forelse($rombel->siswaAktif as $s)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           class="checkbox-{{ $rombel->id }}"
                                           name="siswa[{{ $s->id }}]"
                                           checked
                                           {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}>
                                </td>
                                <td>{{ $s->siswa->nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    Tidak ada siswa
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        @endforeach

        {{-- ACTION --}}
        <div class="d-flex justify-content-between align-items-center mt-4">

            <a href="{{ route('tu.penempatan-siswa.naik-kelas.index') }}"
               class="btn btn-secondary">
                ← Kembali
            </a>

            <button class="btn btn-success px-4"
                {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}>
                Proses Naik Kelas
            </button>

        </div>

    </form>

</div>

{{-- SCRIPT --}}
<script>
function toggleAll(id) {
    const checkboxes = document.querySelectorAll('.checkbox-' + id);
    const allChecked = [...checkboxes].every(cb => cb.checked);

    checkboxes.forEach(cb => cb.checked = !allChecked);
}
</script>

@endsection