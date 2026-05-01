@extends('layouts.tu')

@section('title', 'Tambah Pegawai')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Tambah Data Pegawai</h4>
        <a href="{{ route('tu.datakepegawaian.index') }}" class="btn btn-secondary">Kembali</a>
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

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('tu.datakepegawaian.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- IDENTITAS --}}
                    <h5 class="mb-3">Identitas</h5>

                    <div class="col-md-6 mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>NIP</label>
                        <input type="text" name="nip" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>NUPTK</label>
                        <input type="text" name="nuptk" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Agama</label>
                        <input type="text" name="agama" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">-- pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Status K/TK</label>
                        <select name="status_kawin" class="form-control">
                            <option value="">-- pilih --</option>
                            <option value="K">Kawin</option>
                            <option value="TK">Tidak Kawin</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control">
                    </div>


                    {{-- STATUS --}}
                    <h5 class="mb-3 mt-4">Status & Ijazah</h5>

                    <div class="col-md-6 mb-3">
                        <label>Status Kepegawaian</label>
                        <select name="status_kepegawaian" class="form-control">
                            <option value="">-- pilih --</option>
                            <option value="PNS">PNS</option>
                            <option value="NON_PNS">Non PNS</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Pangkat/Gol SK Terakhir</label>
                        <input type="text" name="sk_terakhir_pangkat" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Pangkat/Gol SK Berkala</label>
                        <input type="text" name="sk_berkala_pangkat" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Ijazah Terakhir</label>
                        <input type="text" name="ijazah_terakhir" class="form-control">
                    </div>


                    {{-- SK --}}
                    <h5 class="mb-3 mt-4">SK</h5>

                    <div class="col-md-6 mb-3">
                        <label>No SK Terakhir</label>
                        <input type="text" name="sk_terakhir_no" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>TMT SK Terakhir</label>
                        <input type="date" name="sk_terakhir_tmt" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>No SK Berkala</label>
                        <input type="text" name="sk_berkala_no" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>TMT SK Berkala</label>
                        <input type="date" name="sk_berkala_tmt" class="form-control">
                    </div>


                    {{-- PEKERJAAN --}}
                    <h5 class="mb-3 mt-4">Pekerjaan</h5>

                    <div class="col-md-6 mb-3">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Mulai Kerja</label>
                        <input type="date" name="mulai_kerja" class="form-control">
                    </div>

                    <h5 class="mt-4">Masa Kerja Golongan</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tahun (TH)</label>
                                <input type="number" name="masa_kerja_gol_th" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Bulan (BLN)</label>
                                <input type="number" name="masa_kerja_gol_bln" class="form-control" max="11">
                            </div>
                        </div>

                        <h5 class="mt-3">Masa Kerja Keseluruhan</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tahun (TH)</label>
                                <input type="number" name="masa_kerja_total_th" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Bulan (BLN)</label>
                                <input type="number" name="masa_kerja_total_bln" class="form-control" max="11">
                            </div>
                        </div>


                    {{-- MENGAJAR --}}
                    <h5 class="mb-3 mt-4">Mengajar</h5>

                    <div class="col-md-6 mb-3">
                        <label>Mengajar di Kelas</label>
                        <input type="text" name="mengajar_kelas" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jam Mengajar</label>
                        <input type="text" name="jam_mengajar" class="form-control">
                    </div>


                    {{-- GAJI --}}
                    <h5 class="mb-3 mt-4">Gaji</h5>

                    <div class="col-md-6 mb-3">
                        <label>Gaji Pokok</label>
                        <input type="text" name="gaji_pokok" class="form-control" placeholder="contoh: 4000000">
                    </div>


                    {{-- TAMBAHAN --}}
                    <h5 class="mb-3 mt-4">Tambahan</h5>

                    <div class="col-md-6 mb-3">
                        <label>Tugas Tambahan</label>
                        <input type="text" name="tugas_tambahan" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nomor Sertifikasi</label>
                        <input type="text" name="nomor_sertifikasi" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tahun Sertifikasi</label>
                        <input type="number" name="tahun_sertifikasi" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>

                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection