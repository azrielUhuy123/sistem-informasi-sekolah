<div id="step1" class="wizard-step">
    <h4 class="mb-4">Data Pribadi</h4>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select" required>
                <option value="L">Laki-Laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label>NIK</label>
            <input type="text" name="nik" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Nomor KK</label>
            <input type="text" name="nomor_kk" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Nomor Akta</label>
            <input type="text" name="nomor_akta" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Agama</label>
            <input type="text" name="agama" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Kewarganegaraan</label>
            <select name="kewarganegaraan" class="form-select">
                <option value="Indonesia">Indonesia</option>
                <option value="WNA">Warga Negara Asing</option>
            </select>
        </div>

        <div class="text-end mt-4">
            <button type="button" class="btn btn-primary" onclick="showStep(2)">
                Selanjutnya
            </button>
        </div>
    </div>
</div>