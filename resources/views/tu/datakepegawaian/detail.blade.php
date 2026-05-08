@extends('layouts.app')

@section('title', 'Detail Pegawai')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="row">

                {{-- KIRI (AVATAR + IDENTITAS) --}}
                <div class="col-md-4 d-flex align-items-center gap-3">

                    {{-- AVATAR --}}
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                         style="width:120px;height:120px;font-size:28px;">
                        {{ strtoupper(substr($pegawai->nama,0,2)) }}
                    </div>

                    <div>
                        <h5 class="mb-0 fw-bold">{{ $pegawai->nama }}</h5>
                        <small class="text-muted">NIP {{ $pegawai->nip ?? '-' }}</small><br>
                        <small class="text-muted">NUPTK {{ $pegawai->nuptk ?? '-' }}</small>
                    </div>

                </div>

                {{-- KANAN (INFO RINGKAS) --}}
                <div class="col-md-8">

                    <h6 class="fw-bold mb-3">Biodata</h6>

                    <table class="table table-sm align-middle">

                        <tr>
                            <th>Tempat, Tanggal Lahir</th>
                            <td>
                                {{ $pegawai->tempat_lahir ?? '-' }},
                                {{ $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('d-m-Y') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Agama</th>
                            <td>{{ $pegawai->agama ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Status (K/TK)</th>
                            <td>
                                @if($pegawai->status_kawin == 'K')
                                    Kawin
                                @elseif($pegawai->status_kawin == 'TK')
                                    Tidak Kawin
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th width="200">Jenis Kelamin</th>
                            <td>
                                @if($pegawai->jenis_kelamin == 'L')
                                    <span class="badge bg-primary">Laki-laki</span>
                                @elseif($pegawai->jenis_kelamin == 'P')
                                    <span class="badge bg-danger">Perempuan</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Status (PNS/NON)</th>
                            <td>{{ $pegawai->status_kepegawaian ?? '-' }}</td>
                        </tr>
                    </table>

                    {{-- BUTTON --}}
                    <div class="mt-3">
                        <a href="{{ route('tu.datakepegawaian.edit', $pegawai->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit Data
                        </a>

                        <a href="{{ route('tu.datakepegawaian.index') }}"
                           class="btn btn-secondary btn-sm">
                            Kembali
                        </a>
                    </div>

                </div>

            </div>

            {{-- DETAIL LENGKAP --}}
            <hr class="my-4">

            <div class="row">

                <div class="col-md-6">

                    <h6 class="fw-bold mb-3">Informasi Utama</h6>
                    <table class="table table-sm">
                    <tr>
                        <th>Ijazah</th>
                            <td>{{ $pegawai->ijazah_terakhir ?? '-' }}</td>
                        </tr>
                    </table>
                    <h6 class="fw-bold mt-4 mb-3">SK</h6>
                    <table class="table table-sm">
                        

                        <tr>
                            <th>SK Terakhir</th>
                            <td>
                                Pangkat/Gol: {{ $pegawai->sk_terakhir_pangkat ?? '-' }} <br>
                                No: {{ $pegawai->sk_terakhir_no ?? '-' }} <br>
                                TMT: {{ $pegawai->sk_terakhir_tmt ? $pegawai->sk_terakhir_tmt->format('d-m-Y') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>SK Berkala</th>
                            <td>
                                Pangkat/Gol: {{ $pegawai->sk_berkala_pangkat ?? '-' }} <br>
                                No: {{ $pegawai->sk_berkala_no ?? '-' }} <br>
                                TMT: {{ $pegawai->sk_berkala_tmt ? $pegawai->sk_berkala_tmt->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Gaji Pokok</th>
                            <td>
                                {{ $pegawai->gaji_pokok ? 'Rp ' . number_format($pegawai->gaji_pokok, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold mb-3"></h6>
                    <table class="table table-sm">
                        <tr>
                            <th>Mulai Bekerja Di Sekolah Ini</th>
                            <td>{{ $pegawai->mulai_kerja ? $pegawai->mulai_kerja->format('d-m-Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Masa Kerja Gol</th>
                            <td>
                                {{ $pegawai->masa_kerja_gol_th ?? 0 }} Th
                                {{ $pegawai->masa_kerja_gol_bln ?? 0 }} Bln
                            </td>
                        </tr>

                        <tr>
                            <th>Masa Kerja Keseluruhan</th>
                            <td>
                                {{ $pegawai->masa_kerja_total_th ?? 0 }} Th
                                {{ $pegawai->masa_kerja_total_bln ?? 0 }} Bln
                            </td>
                        </tr>
                    </table>

                    
                    <table class="table table-sm">
                        <tr>
                            <th>Mengajar Di Kelas</th>
                            <td>{{ $pegawai->mengajar_kelas ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Jam Mengajar</th>
                            <td>{{ $pegawai->jam_mengajar ?? '-' }} jam</td>
                        </tr>
                        <tr>
                            <th>Tugas Tambahan</th>
                            <td>{{ $pegawai->tugas_tambahan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Sertifikasi</th>
                            <td>
                                {{ $pegawai->nomor_sertifikasi ?? '-' }}
                                @if($pegawai->tahun_sertifikasi)
                                    (Th. {{ $pegawai->tahun_sertifikasi }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $pegawai->keterangan ?? '-' }}</td>
                        </tr>
                    </table>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection