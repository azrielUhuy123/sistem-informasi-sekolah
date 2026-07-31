@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Edit Data Pegawai</h4>
                <p class="text-muted mb-0">Perbarui data pegawai dengan form yang sama seperti tambah data</p>
            </div>
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

            <div class="row g-4">

                {{-- IDENTITAS --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                    <i class="bi bi-person-badge fs-5 text-primary"></i>
                                </div>
                                <h5 class="mb-0">Identitas Pegawai</h5>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" class="form-control rounded-3" value="{{ old('nama', $pegawai->nama) }}" required>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">NIP</label>
                                    <input type="text" name="nip" class="form-control rounded-3" value="{{ old('nip', $pegawai->nip) }}">
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">NUPTK</label>
                                    <input type="text" name="nuptk" class="form-control rounded-3" value="{{ old('nuptk', $pegawai->nuptk) }}">
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control rounded-3" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}">
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_lahir" class="form-control rounded-3" value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir ? $pegawai->tanggal_lahir->format('Y-m-d') : '') }}" required>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Agama</label>
                                    <select name="agama" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="Islam" {{ old('agama', $pegawai->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('agama', $pegawai->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('agama', $pegawai->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('agama', $pegawai->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('agama', $pegawai->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('agama', $pegawai->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Status Kawin</label>
                                    <select name="status_kawin" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="K" {{ old('status_kawin', $pegawai->status_kawin) == 'K' ? 'selected' : '' }}>Kawin</option>
                                        <option value="TK" {{ old('status_kawin', $pegawai->status_kawin) == 'TK' ? 'selected' : '' }}>Tidak Kawin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KEPEGAWAIAN --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                    <i class="bi bi-briefcase fs-5 text-info"></i>
                                </div>
                                <h5 class="mb-0">Kepegawaian</h5>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Jabatan</label>
                                    <select name="jabatan" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="Kepala Sekolah" {{ old('jabatan', $pegawai->jabatan) == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                        <option value="Operator Sekolah" {{ old('jabatan', $pegawai->jabatan) == 'Operator Sekolah' ? 'selected' : '' }}>Operator Sekolah</option>
                                        <option value="Guru" {{ old('jabatan', $pegawai->jabatan) == 'Guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="Penjaga Sekolah" {{ old('jabatan', $pegawai->jabatan) == 'Penjaga Sekolah' ? 'selected' : '' }}>Penjaga Sekolah</option>
                                        <option value="Satpam" {{ old('jabatan', $pegawai->jabatan) == 'Satpam' ? 'selected' : '' }}>Satpam</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Status Kepegawaian</label>
                                    <select name="status_kepegawaian" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="PNS" {{ old('status_kepegawaian', $pegawai->status_kepegawaian) == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="NON_PNS" {{ old('status_kepegawaian', $pegawai->status_kepegawaian) == 'NON_PNS' ? 'selected' : '' }}>Non PNS</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Mulai Kerja</label>
                                    <input type="date" name="mulai_kerja" class="form-control rounded-3" value="{{ old('mulai_kerja', $pegawai->mulai_kerja ? $pegawai->mulai_kerja->format('Y-m-d') : '') }}">
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label class="form-label">Ijazah Terakhir</label>
                                    <input type="text" name="ijazah_terakhir" class="form-control rounded-3" value="{{ old('ijazah_terakhir', $pegawai->ijazah_terakhir) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIWAYAT SK --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                    <i class="bi bi-file-earmark-text fs-5 text-secondary"></i>
                                </div>
                                <h5 class="mb-0">Riwayat SK</h5>
                            </div>

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-semibold mb-3">SK Terakhir</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Pangkat/Gol</label>
                                                <input type="text" name="sk_terakhir_pangkat" class="form-control rounded-3" value="{{ old('sk_terakhir_pangkat', $pegawai->sk_terakhir_pangkat) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nomor SK</label>
                                                <input type="text" name="sk_terakhir_no" class="form-control rounded-3" value="{{ old('sk_terakhir_no', $pegawai->sk_terakhir_no) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">TMT</label>
                                                <input type="date" name="sk_terakhir_tmt" class="form-control rounded-3" value="{{ old('sk_terakhir_tmt', $pegawai->sk_terakhir_tmt ? $pegawai->sk_terakhir_tmt->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-semibold mb-3">SK Berkala</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Pangkat/Gol</label>
                                                <input type="text" name="sk_berkala_pangkat" class="form-control rounded-3" value="{{ old('sk_berkala_pangkat', $pegawai->sk_berkala_pangkat) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Nomor SK</label>
                                                <input type="text" name="sk_berkala_no" class="form-control rounded-3" value="{{ old('sk_berkala_no', $pegawai->sk_berkala_no) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">TMT</label>
                                                <input type="date" name="sk_berkala_tmt" class="form-control rounded-3" value="{{ old('sk_berkala_tmt', $pegawai->sk_berkala_tmt ? $pegawai->sk_berkala_tmt->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MASA KERJA --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-2">
                                    <i class="bi bi-calendar-week fs-5 text-warning"></i>
                                </div>
                                <h5 class="mb-0">Masa Kerja</h5>
                            </div>

                            <div class="row g-3">
                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-semibold mb-3">Masa Kerja Golongan</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Tahun</label>
                                                <div class="input-group">
                                                    <input type="number" name="masa_kerja_gol_th" class="form-control rounded-3" min="0" value="{{ old('masa_kerja_gol_th', $pegawai->masa_kerja_gol_th) }}">
                                                    <span class="input-group-text">Tahun</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Bulan</label>
                                                <div class="input-group">
                                                    <input type="number" name="masa_kerja_gol_bln" class="form-control rounded-3" min="0" max="11" value="{{ old('masa_kerja_gol_bln', $pegawai->masa_kerja_gol_bln) }}">
                                                    <span class="input-group-text">Bln</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-semibold mb-3">Masa Kerja Keseluruhan</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Tahun</label>
                                                <div class="input-group">
                                                    <input type="number" name="masa_kerja_total_th" class="form-control rounded-3" min="0" value="{{ old('masa_kerja_total_th', $pegawai->masa_kerja_total_th) }}">
                                                    <span class="input-group-text">Tahun</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Bulan</label>
                                                <div class="input-group">
                                                    <input type="number" name="masa_kerja_total_bln" class="form-control rounded-3" min="0" max="11" value="{{ old('masa_kerja_total_bln', $pegawai->masa_kerja_total_bln) }}">
                                                    <span class="input-group-text">Bln</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SERTIFIKASI --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-2">
                                    <i class="bi bi-award fs-5 text-danger"></i>
                                </div>
                                <h5 class="mb-0">Sertifikasi</h5>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Sertifikasi</label>
                                    <input type="text" name="nomor_sertifikasi" class="form-control rounded-3" value="{{ old('nomor_sertifikasi', $pegawai->nomor_sertifikasi) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tahun Sertifikasi</label>
                                    <input type="number" name="tahun_sertifikasi" class="form-control rounded-3" min="2000" value="{{ old('tahun_sertifikasi', $pegawai->tahun_sertifikasi) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KEUANGAN --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-purple bg-opacity-10 p-2 me-2">
                                    <i class="bi bi-cash-stack fs-5 text-purple"></i>
                                </div>
                                <h5 class="mb-0">Keuangan</h5>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 col-lg-4">
                                    <label class="form-label">Gaji Pokok</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" name="gaji_pokok" class="form-control rounded-3" placeholder="contoh: 4000000" inputmode="numeric" value="{{ old('gaji_pokok', $pegawai->gaji_pokok ? number_format($pegawai->gaji_pokok, 0, '', '') : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KETERANGAN --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                    <i class="bi bi-chat-left-text fs-5 text-primary"></i>
                                </div>
                                <h5 class="mb-0">Keterangan</h5>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Catatan Tambahan</label>
                                <textarea name="keterangan" class="form-control rounded-3" rows="4">{{ old('keterangan', $pegawai->keterangan) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="alert alert-info mt-4">
                <div class="fw-semibold mb-2">Info</div>
                <ul class="mb-0 ps-3">
                    <li>Status pegawai akan menjadi Pending setelah data disimpan.</li>
                    <li>Data harus disetujui Kepala Sekolah sebelum diproses lebih lanjut.</li>
                    <li>Setelah disetujui, Operator dapat membuat akun pegawai.</li>
                    <li>Akun pegawai harus disetujui kembali oleh Kepala Sekolah sebelum dapat digunakan.</li>
                </ul>
            </div>

            <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('tu.datakepegawaian.index') }}" class="btn btn-outline-secondary ml-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary mr-4 px-4">
                        <i class="bi bi-save me-1"></i> Update Data
                    </button>
                </div>
            </div>
        </form>

    </div>

@endsection
