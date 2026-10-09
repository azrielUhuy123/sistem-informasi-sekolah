<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Hasil Belajar - {{ $siswa->nama }}</title>
    <style>
        @page { size: A4; margin: 15mm; }
        * { box-sizing: border-box; }
        body { background: #fff; color: #111; font-family: DejaVu Sans, sans-serif; font-size: 10pt; line-height: 1.45; margin: 0; }
        .pdf-raport { width: 100%; }
        .pdf-raport-header { border-bottom: 1.5px solid #222; padding-bottom: 12px; text-align: center; }
        .pdf-raport-kicker { font-size: 8pt; letter-spacing: 1.5px; text-transform: uppercase; }
        .pdf-raport-title { font-size: 16pt; letter-spacing: .5px; margin: 4px 0; }
        .pdf-raport-subtitle { font-size: 10pt; margin: 0; }
        .pdf-raport-section { margin-top: 15px; page-break-inside: avoid; break-inside: avoid; }
        .pdf-raport-heading { border-bottom: 1px solid #333; font-size: 10pt; letter-spacing: .8px; margin: 0 0 8px; padding-bottom: 4px; text-transform: uppercase; }
        .pdf-raport-identity { border-collapse: collapse; width: 100%; }
        .pdf-raport-identity td { padding: 2px 0; vertical-align: top; }
        .pdf-raport-identity td:first-child { font-weight: bold; width: 25%; }
        .pdf-raport-table { border-collapse: collapse; margin: 0; page-break-inside: auto; width: 100%; }
        .pdf-raport-table th, .pdf-raport-table td { border: .6px solid #777; padding: 6px 7px; }
        .pdf-raport-table thead { display: table-header-group; }
        .pdf-raport-table thead th { background: #ededed; font-size: 8.5pt; text-align: center; }
        .pdf-raport-table tbody tr { page-break-inside: avoid; break-inside: avoid; }
        .pdf-raport-number, .pdf-raport-score { text-align: center; }
        .pdf-raport-table tfoot th, .pdf-raport-table tfoot td { background: #f6f6f6; font-weight: bold; }
        .pdf-raport-note { border: .6px solid #777; min-height: 42px; padding: 8px; }
        .pdf-raport-empty { font-style: italic; }
        .pdf-raport-signature { margin-top: 24px; page-break-inside: avoid; break-inside: avoid; }
        .pdf-raport-signature-date { margin-bottom: 12px; text-align: right; }
        .pdf-raport-signature-grid { border-collapse: collapse; width: 100%; }
        .pdf-raport-signature-grid td { text-align: center; vertical-align: top; width: 50%; }
        .pdf-raport-signature-space { height: 65px; }
        .pdf-raport-signature-line { border-bottom: 1px solid #111; display: inline-block; min-width: 145px; }
    </style>
</head>
<body>
<div class="pdf-raport">
    <header class="pdf-raport-header">
        <div class="pdf-raport-kicker">Dokumen Akademik</div>
        <h1 class="pdf-raport-title">LAPORAN HASIL BELAJAR</h1>
        <p class="pdf-raport-subtitle">PESERTA DIDIK</p>
        <p class="pdf-raport-subtitle">Tahun Pelajaran {{ current_tahun()?->tahun }} / Semester {{ current_tahun()?->semester }}</p>
    </header>

    <section class="pdf-raport-section">
        <h2 class="pdf-raport-heading">Identitas Peserta Didik</h2>
        <table class="pdf-raport-identity">
            <tr><td>Nama Peserta Didik</td><td>: {{ $siswa->nama }}</td></tr>
            <tr><td>NIS</td><td>: {{ $siswa->nis }}</td></tr>
            <tr><td>NISN</td><td>: {{ $siswa->nisn ?? '-' }}</td></tr>
            <tr><td>Kelas</td><td>: {{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}</td></tr>
        </table>
    </section>

    <section class="pdf-raport-section">
        <h2 class="pdf-raport-heading">Hasil Belajar</h2>
        <table class="pdf-raport-table">
            <thead><tr><th style="width: 8%;">No</th><th>Mata Pelajaran</th><th style="width: 18%;">Nilai Akhir</th></tr></thead>
            <tbody>
                @forelse($nilai as $n)
                    <tr><td class="pdf-raport-number">{{ $loop->iteration }}</td><td>{{ $n->mapel?->nama_mapel ?? '-' }}</td><td class="pdf-raport-score">{{ $n->nilai_akhir }}</td></tr>
                @empty
                    <tr><td colspan="3" class="pdf-raport-empty">Belum ada data nilai akademik.</td></tr>
                @endforelse
            </tbody>
            <tfoot><tr><th colspan="2" style="text-align: right;">Rata-rata Nilai</th><th class="pdf-raport-score">{{ $rataRata }}</th></tr></tfoot>
        </table>
    </section>

    <section class="pdf-raport-section">
        <h2 class="pdf-raport-heading">Kehadiran</h2>
        <table class="pdf-raport-table">
            <thead><tr><th>Keterangan</th><th style="width: 28%;">Jumlah</th></tr></thead>
            <tbody><tr><td>Sakit</td><td class="pdf-raport-score">{{ $sakit }} hari</td></tr><tr><td>Izin</td><td class="pdf-raport-score">{{ $izin }} hari</td></tr><tr><td>Tanpa Keterangan</td><td class="pdf-raport-score">{{ $alpha }} hari</td></tr><tr><td>Hadir</td><td class="pdf-raport-score">{{ $hadir }} hari</td></tr></tbody>
        </table>
    </section>

    <section class="pdf-raport-section">
        <h2 class="pdf-raport-heading">Perkembangan Peserta Didik</h2>
        @if($perkembangan)
            <table class="pdf-raport-table">
                <thead><tr><th style="width: 28%;">Aspek</th><th>Catatan</th></tr></thead>
                <tbody><tr><th>Karakter</th><td>{{ $perkembangan->karakter ?: '-' }}</td></tr><tr><th>Sosial</th><td>{{ $perkembangan->sosial ?: '-' }}</td></tr><tr><th>Disiplin</th><td>{{ $perkembangan->disiplin ?: '-' }}</td></tr><tr><th>Kebersihan</th><td>{{ $perkembangan->kebersihan ?: '-' }}</td></tr></tbody>
            </table>
            <h3 class="pdf-raport-heading" style="margin-top: 12px;">Catatan Wali Kelas</h3>
            <div class="pdf-raport-note">{{ $perkembangan->catatan ?: '-' }}</div>
        @else
            <div class="pdf-raport-note pdf-raport-empty">Belum ada catatan perkembangan peserta didik.</div>
        @endif
    </section>

    <section class="pdf-raport-section pdf-raport-signature">
        <h2 class="pdf-raport-heading">Pengesahan</h2>
        <div class="pdf-raport-signature-date">...................., .................... 20....</div>
        <div>Mengetahui,</div>
        <table class="pdf-raport-signature-grid"><tr>
            <td>Kepala Sekolah<div class="pdf-raport-signature-space"></div><span class="pdf-raport-signature-line">&nbsp;</span></td>
            <td>Wali Kelas<div class="pdf-raport-signature-space"></div><span class="pdf-raport-signature-line">&nbsp;</span></td>
        </tr></table>
    </section>
</div>
</body>
</html>
