<div class="row">

    <div class="col-md-6 mb-3">
        <label>Nama Pegawai</label>
        <input type="text" name="nama" class="form-control"
               value="{{ $pegawai->nama ?? '' }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label>NIP</label>
        <input type="text" name="nip" class="form-control"
               value="{{ $pegawai->nip ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control"
               value="{{ $pegawai->tempat_lahir ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control"
               value="{{ $pegawai->tanggal_lahir ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Jabatan</label>
        <input type="text" name="jabatan" class="form-control"
               value="{{ $pegawai->jabatan ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Status Kerja</label>
        <select name="status_kerja" class="form-control">
            <option value="PNS" {{ isset($pegawai) && $pegawai->status_kerja=='PNS' ? 'selected':'' }}>PNS</option>
            <option value="Honorer" {{ isset($pegawai) && $pegawai->status_kerja=='Honorer' ? 'selected':'' }}>Honorer</option>
            <option value="Kontrak" {{ isset($pegawai) && $pegawai->status_kerja=='Kontrak' ? 'selected':'' }}>Kontrak</option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label>Pendidikan Terakhir</label>
        <input type="text" name="pendidikan_terakhir" class="form-control"
               value="{{ $pegawai->pendidikan_terakhir ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Mengajar di Kelas</label>
        <input type="text" name="mengajar_di_kelas" class="form-control"
               value="{{ $pegawai->mengajar_di_kelas ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Pengalaman (tahun)</label>
        <input type="number" name="pengalaman_tahun" class="form-control"
               value="{{ $pegawai->pengalaman_tahun ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Gaji</label>
        <input type="number" name="gaji" class="form-control"
               value="{{ $pegawai->gaji ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Tunjangan</label>
        <input type="number" name="tunjangan" class="form-control"
               value="{{ $pegawai->tunjangan ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Absensi Tahun Ini</label>
        <input type="number" name="absensi_tahun_ini" class="form-control"
               value="{{ $pegawai->absensi_tahun_ini ?? '' }}">
    </div>

    <div class="col-md-6 mb-3">
        <label>Nilai Kinerja</label>
        <input type="text" name="nilai_kinerja" class="form-control"
               value="{{ $pegawai->nilai_kinerja ?? '' }}">
    </div>

    <div class="col-md-12 mb-3">
        <label>Pelatihan / Sertifikasi</label>
        <textarea name="pelatihan" class="form-control">{{ $pegawai->pelatihan ?? '' }}</textarea>
    </div>

</div>
