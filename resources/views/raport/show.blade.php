@extends('layouts.app')

@section('title', 'Raport Siswa')

@push('styles')
<style>
    .raport-page {
        --raport-ink: #263238;
        --raport-muted: #66727a;
        --raport-line: #d8dee2;
        --raport-accent: #2f5964;
        color: var(--raport-ink);
        max-width: 1120px;
        margin: 0 auto;
    }

    .raport-header {
        background: #fff;               
        padding: 1.25rem 1.5rem;
    }

    .raport-kicker,
    .raport-field-label {
        color: var(--raport-muted);
        font-size: .78rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .raport-title { font-size: clamp(1.35rem, 2.5vw, 2rem); margin: .25rem 0; }
    .raport-meta { color: var(--raport-muted); font-size: .92rem; }
    .raport-section { background: #fff; border: 1px solid var(--raport-line); margin-top: 1.25rem; }
    .raport-section-heading { border-bottom: 1px solid var(--raport-line); font-size: .86rem; font-weight: 700; letter-spacing: .1em; margin: 0; padding: .85rem 1rem; text-transform: uppercase; }
    .raport-section-body { padding: 1rem; }
    .raport-identity { display: grid; gap: .85rem 2rem; grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .raport-field-label { display: block; font-size: .75rem; letter-spacing: .04em; margin-bottom: .2rem; }
    .raport-field-value { font-weight: 600; overflow-wrap: anywhere; }
    .raport-section,
    .raport-section-body,
    .raport-section p,
    .raport-table td,
    .raport-table th { overflow-wrap: anywhere; }
    .raport-empty { color: var(--raport-muted); font-style: italic; margin: 0; }
    .raport-signature { margin-top: 2rem; }
    .raport-signature-grid { display: grid; gap: 2rem; grid-template-columns: repeat(2, minmax(0, 1fr)); margin-top: 1.5rem; text-align: center; }
    .raport-signature-space { height: 5rem; }
    .raport-signature-line { border-bottom: 1px solid var(--raport-ink); display: inline-block; min-width: 12rem; }

    @media (max-width: 767.98px) {
        .raport-identity, .raport-signature-grid { grid-template-columns: 1fr; }
        .raport-header { padding: 1rem; }
        .raport-title { font-size: 1.35rem; line-height: 1.25; }
        .raport-meta { line-height: 1.5; }
        .raport-section-heading { font-size: .78rem; letter-spacing: .06em; }
        .raport-section-body { padding: .75rem; }
        .raport-signature-line { min-width: 10rem; }
    }

    @media (max-width: 575.98px) {
        .raport-header > .d-flex { width: 100%; }
        .raport-header > .d-flex .btn { flex: 1 1 100%; }
    }
</style>
@endpush

@section('content')
<main class="container-fluid py-3 raport-page">
    <header class="raport-header border-0 shadow-sm rounded-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>            
            <h1 class="fw-bold text-primary raport-title">LAPORAN HASIL BELAJAR</h1>
            <div class="raport-meta">Peserta Didik / Tahun Pelajaran {{ current_tahun()?->tahun }} / Semester {{ current_tahun()?->semester }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('guru.raport.pdf', $siswa) }}" class="btn btn-outline-secondary">Download PDF</a>
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary">Print Raport</button>
        </div>
    </header>

    <section class="raport-section rounded-3 border-0 shadow-sm" aria-labelledby="raport-identitas">
        <h2 id="raport-identitas" class="raport-section-heading">Identitas Peserta Didik</h2>
        <div class="raport-section-body raport-identity">
            <div><span class="raport-field-label">Nama</span><div class="raport-field-value">{{ $siswa->nama }}</div></div>
            <div><span class="raport-field-label">NIS</span><div class="raport-field-value">{{ $siswa->nis }}</div></div>
            <div><span class="raport-field-label">NISN</span><div class="raport-field-value">{{ $siswa->nisn ?? '-' }}</div></div>
            <div><span class="raport-field-label">Kelas</span><div class="raport-field-value">{{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}</div></div>
        </div>
    </section>

    <div class="mt-4">
        <x-table.card
            title="Hasil Belajar"
            subtitle="Rekap seluruh nilai mata pelajaran siswa."
        >
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="70">No</th>
                            <th>Mata Pelajaran</th>
                            <th width="150">Nilai Akhir</th>
                            <th width="150">Tugas</th>
                            <th width="150">UTS</th>
                            <th width="150">UAS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nilai as $n)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $n->mapel?->nama_mapel ?? '-' }}</td>
                                <td class="fw-bold">{{ $n->nilai_akhir }}</td>
                                <td>{{ $n->nilai_tugas }}</td>
                                <td>{{ $n->nilai_uts }}</td>
                                <td>{{ $n->nilai_uas }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5">
                                        <i class="bi bi-journal-x display-3 text-secondary"></i>
                                        <h5 class="fw-bold mt-3">Belum Ada Nilai</h5>
                                        <p class="text-muted mb-0">Nilai akademik siswa belum tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">Rata-rata Nilai</th>
                            <th>{{ $rataRata }}</th>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-table.card>
    </div>

    <div class="mt-4">
        <x-table.card
            title="Kehadiran"
            subtitle="Rekap kehadiran siswa pada tahun ajaran berjalan."
        >
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr><th>Keterangan</th><th>Jumlah</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Sakit</td><td>{{ $sakit }} hari</td></tr>
                        <tr><td>Izin</td><td>{{ $izin }} hari</td></tr>
                        <tr><td>Tanpa Keterangan</td><td>{{ $alpha }} hari</td></tr>
                    </tbody>
                </table>
            </div>
        </x-table.card>
    </div>

    <div class="mt-4">
        <x-table.card
            title="Perkembangan Peserta Didik"
            subtitle="Catatan perkembangan karakter dan perilaku siswa."
        >
            @if($perkembangan)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr><th>Aspek Perkembangan</th><th>Catatan</th></tr>
                        </thead>
                        <tbody>
                            <tr><th>Karakter</th><td>{{ $perkembangan->karakter ?: '-' }}</td></tr>
                            <tr><th>Sosial</th><td>{{ $perkembangan->sosial ?: '-' }}</td></tr>
                            <tr><th>Disiplin</th><td>{{ $perkembangan->disiplin ?: '-' }}</td></tr>
                            <tr><th>Kebersihan</th><td>{{ $perkembangan->kebersihan ?: '-' }}</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4"><h3 class="h6 fw-bold mb-2">Catatan Wali Kelas</h3><p class="mb-0">{{ $perkembangan->catatan ?: '-' }}</p></div>
            @else
                <p class="raport-empty">Belum ada catatan perkembangan peserta didik.</p>
            @endif
        </x-table.card>
    </div>
</main>
@endsection
