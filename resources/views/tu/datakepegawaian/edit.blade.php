@extends('layouts.app')

@section('title', 'Edit Administrasi Pegawai')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <h3 class="text-primary fw-bold text-center mb-1">Administrasi Pegawai</h3>
        <p class="text-muted text-center">
            Data identitas dan pendidikan diperbarui melalui pengajuan Guru, bukan dari form administrasi ini.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tu.datakepegawaian.update', $pegawai->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Data Administratif</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" for="jabatan">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select">
                            <option value="">-- Pilih --</option>
                            @foreach (['Kepala Sekolah', 'Operator Sekolah', 'Guru', 'Penjaga Sekolah', 'Satpam'] as $jabatan)
                                <option value="{{ $jabatan }}" @selected(old('jabatan', $pegawai->jabatan) === $jabatan)>
                                    {{ $jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="status_kepegawaian">Status kepegawaian</label>
                        <select id="status_kepegawaian" name="status_kepegawaian" class="form-select">
                            <option value="">-- Pilih --</option>
                            <option value="PNS" @selected(old('status_kepegawaian', $pegawai->status_kepegawaian) === 'PNS')>PNS</option>
                            <option value="NON_PNS" @selected(old('status_kepegawaian', $pegawai->status_kepegawaian) === 'NON_PNS')>Non PNS</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="mulai_kerja">Mulai kerja</label>
                        <input id="mulai_kerja" type="date" name="mulai_kerja" class="form-control"
                            value="{{ old('mulai_kerja', $pegawai->mulai_kerja?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Riwayat Administratif SK</h5>
                <div class="row g-3">
                    @foreach ([
                        'sk_terakhir_pangkat' => ['Pangkat/Golongan SK terakhir', 'text'],
                        'sk_terakhir_no' => ['Nomor SK terakhir', 'text'],
                        'sk_terakhir_tmt' => ['TMT SK terakhir', 'date'],
                        'sk_berkala_pangkat' => ['Pangkat/Golongan SK berkala', 'text'],
                        'sk_berkala_no' => ['Nomor SK berkala', 'text'],
                        'sk_berkala_tmt' => ['TMT SK berkala', 'date'],
                    ] as $field => [$label, $type])
                        <div class="col-md-4">
                            <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                            <input id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" class="form-control"
                                value="{{ old($field, $type === 'date' && $pegawai->{$field} ? $pegawai->{$field}->format('Y-m-d') : $pegawai->{$field}) }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="mb-3">Masa Kerja dan Penugasan</h5>
                <div class="row g-3">
                    @foreach ([
                        'masa_kerja_gol_th' => ['Masa kerja golongan (tahun)', 0, null],
                        'masa_kerja_gol_bln' => ['Masa kerja golongan (bulan)', 0, 11],
                        'masa_kerja_total_th' => ['Masa kerja total (tahun)', 0, null],
                        'masa_kerja_total_bln' => ['Masa kerja total (bulan)', 0, 11],
                        'jam_mengajar' => ['Jam mengajar', 0, null],
                    ] as $field => [$label, $min, $max])
                        <div class="col-md-4">
                            <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                            <input id="{{ $field }}" type="number" name="{{ $field }}" class="form-control"
                                min="{{ $min }}" @if ($max !== null) max="{{ $max }}" @endif
                                value="{{ old($field, $pegawai->{$field}) }}">
                        </div>
                    @endforeach
                    <div class="col-md-4">
                        <label class="form-label" for="mengajar_kelas">Keterangan kelas mengajar</label>
                        <input id="mengajar_kelas" type="text" name="mengajar_kelas" class="form-control"
                            value="{{ old('mengajar_kelas', $pegawai->mengajar_kelas) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="tugas_tambahan">Tugas tambahan</label>
                        <input id="tugas_tambahan" type="text" name="tugas_tambahan" class="form-control"
                            value="{{ old('tugas_tambahan', $pegawai->tugas_tambahan) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="gaji_pokok">Gaji pokok</label>
                        <input id="gaji_pokok" type="text" name="gaji_pokok" class="form-control" inputmode="numeric"
                            value="{{ old('gaji_pokok', $pegawai->gaji_pokok ? number_format($pegawai->gaji_pokok, 0, '', '') : '') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="keterangan">Keterangan administrasi</label>
                        <textarea id="keterangan" name="keterangan" rows="3" class="form-control">{{ old('keterangan', $pegawai->keterangan) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('tu.datakepegawaian.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Administrasi</button>
        </div>
    </form>
</div>
@endsection
