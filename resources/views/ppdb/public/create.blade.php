@extends('layouts.public')

@section('title','Form Pendaftaran PPDB')

@section('content')

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header">

            <h3 class="mb-0">
                Form Pendaftaran PPDB
            </h3>

        </div>

        <div class="card-body">

            <form
                action="{{ route('ppdb.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <h5 class="mb-3">Data Pribadi</h5>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text"
                            name="nama"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jenis Kelamin</label>
                        <select
                            name="jenis_kelamin"
                            class="form-select"
                            required>

                            <option value="L">
                                Laki-Laki
                            </option>

                            <option value="P">
                                Perempuan
                            </option>

                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>NIK</label>
                        <input type="text"
                            name="nik"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nomor KK</label>
                        <input type="text"
                            name="nomor_kk"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tempat Lahir</label>
                        <input type="text"
                            name="tempat_lahir"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Tanggal Lahir</label>
                        <input type="date"
                            name="tanggal_lahir"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Nomor Akta</label>
                        <input type="text"
                            name="nomor_akta"
                            class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Agama</label>
                        <input type="text"
                            name="agama"
                            class="form-control"
                            required>
                    </div>

                </div>

                <hr>

                    <h5 class="mb-3">
                        Alamat
                    </h5>

                    <div class="mb-3">

                        <label>Alamat Lengkap</label>

                        <textarea
                            name="alamat"
                            class="form-control"
                            rows="3"
                            required></textarea>

                    </div>

                    <div class="row">

                        <div class="col-md-2 mb-3">
                            <label>RT</label>
                            <input type="text"
                                name="rt"
                                class="form-control">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>RW</label>
                            <input type="text"
                                name="rw"
                                class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Desa</label>
                            <input type="text"
                                name="desa"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Kecamatan</label>
                            <input type="text"
                                name="kecamatan"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Kabupaten</label>
                            <input type="text"
                                name="kabupaten"
                                class="form-control"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Provinsi</label>
                            <input type="text"
                                name="provinsi"
                                class="form-control"
                                required>
                        </div>

                    </div>

                    <hr>

                        <h5>
                            Data Ayah
                        </h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Nama Ayah</label>
                                <input type="text"
                                    name="nama_ayah"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>NIK Ayah</label>
                                <input type="text"
                                    name="nik_ayah"
                                    class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Pendidikan Ayah</label>
                                <input type="text"
                                    name="pendidikan_ayah"
                                    class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Pekerjaan Ayah</label>
                                <input type="text"
                                    name="pekerjaan_ayah"
                                    class="form-control">
                            </div>

                        </div>

                        <hr>

                            <h5>
                                Data Ibu
                            </h5>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Nama Ibu</label>
                                    <input type="text"
                                        name="nama_ibu"
                                        class="form-control"
                                        required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>NIK Ibu</label>
                                    <input type="text"
                                        name="nik_ibu"
                                        class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Pendidikan Ibu</label>
                                    <input type="text"
                                        name="pendidikan_ibu"
                                        class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Pekerjaan Ibu</label>
                                    <input type="text"
                                        name="pekerjaan_ibu"
                                        class="form-control">
                                </div>

                            </div>

                            <hr>

                                <h5>Kontak</h5>

                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label>Telepon Rumah</label>
                                        <input type="text"
                                            name="telepon_rumah"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>HP Ayah</label>
                                        <input type="text"
                                            name="hp_ayah"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label>HP Ibu</label>
                                        <input type="text"
                                            name="hp_ibu"
                                            class="form-control">
                                    </div>

                                </div>

                                <hr>

                                    <h5>Data Periodik</h5>

                                    <div class="row">

                                        <div class="col-md-3 mb-3">
                                            <label>Tinggi Badan</label>
                                            <input type="number"
                                                step="0.01"
                                                name="tinggi_badan"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Berat Badan</label>
                                            <input type="number"
                                                step="0.01"
                                                name="berat_badan"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Anak Ke</label>
                                            <input type="number"
                                                name="anak_ke"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-3 mb-3">
                                            <label>Jumlah Saudara</label>
                                            <input type="number"
                                                name="jumlah_saudara"
                                                class="form-control">
                                        </div>

                                    </div>

                                    <hr>

                                        <h5>Upload Berkas</h5>

                                        <div class="mb-3">

                                            <label>Kartu Keluarga</label>

                                            <input
                                                type="file"
                                                name="kartu_keluarga"
                                                class="form-control">

                                        </div>

                                        <div class="mb-3">

                                            <label>Akta Kelahiran</label>

                                            <input
                                                type="file"
                                                name="akta_lahir"
                                                class="form-control">

                                        </div>

                                        <div class="mb-3">

                                            <label>Pas Foto</label>

                                            <input
                                                type="file"
                                                name="foto"
                                                class="form-control">

                                        </div>

                                        <div class="mt-4">

                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-lg"
                                            >

                                                Daftar Sekarang

                                            </button>

                                        </div>

                                        </form>

                                        </div>
                                        </div>
                                        </div>

                                        @endsection