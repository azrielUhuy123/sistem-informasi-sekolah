@extends('layouts.tu')

@section('title', 'Mapping Naik Kelas')

@section('content')

<div class="container-fluid">

    <h4 class="mb-3">
        Mapping Naik Kelas
    </h4>

    <div class="mb-4 text-muted">
        Tingkat {{ $tingkatAsal }} → Tingkat {{ $tingkatTujuan }}
    </div>

    {{-- PROTEKSI STATUS --}}
    @if($tahunAktif->status !== 'transisi')
        <div class="alert alert-warning">
            Proses naik kelas hanya dapat dilakukan saat masa <b>transisi</b>.
        </div>
    @endif

    <form method="POST"
          action="{{ route('tu.penempatan-siswa.naik-kelas') }}">
        @csrf

        <input type="hidden" name="tingkat_asal"
               value="{{ $tingkatAsal }}">

        {{-- LOOP ROMBEL --}}
        @foreach($rombelsAsal as $rombel)

        <div class="card shadow-sm mb-4">
            <div class="card-body">

                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0">
                            {{ $rombel->nama_kelas }}
                        </h5>
                        <small class="text-muted">
                            Rombel asal
                        </small>
                    </div>

                    <span class="badge bg-primary">
                        {{ $rombel->siswa_count ?? 0 }} siswa
                    </span>
                </div>

                {{-- PILIH TUJUAN --}}
                <div class="mb-3">
                    <label class="form-label">Rombel Tujuan</label>
                    <select name="mapping[{{ $rombel->id }}]"
                            class="form-select"
                            {{ $tahunAktif->status !== 'transisi' ? 'disabled' : '' }}>
                        <option value="">-- Lulus / Tidak naik --</option>

                        @foreach($rombelsTujuan as $tujuan)
                            <option value="{{ $tujuan->id }}">
                                {{ $tujuan->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TOOLBAR SISWA --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">
                        Pilih siswa yang akan diproses
                    </small>

                    <button type="button"
                            class="btn btn-sm btn-outline-secondary"
                            onclick="toggleAll('{{ $rombel->id }}')">
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
                                           {{ $tahunAktif->status !== 'transisi' ? 'disabled' : '' }}>
                                </td>
                                <td>{{ $s->siswa->nama }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"
                                    class="text-center text-muted">
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
        <div class="d-flex justify-content-between align-items-center">

            <a href="{{ route('tu.penempatan-siswa.naik-kelas.index') }}"
               class="btn btn-secondary">
                Kembali
            </a>

            <button class="btn btn-success"
                {{ $tahunAktif->status !== 'transisi' ? 'disabled' : '' }}>
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