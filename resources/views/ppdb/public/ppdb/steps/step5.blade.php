<div id="step5" class="wizard-step d-none">
    <h4 class="mb-4">Data Periodik</h4>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label>Tinggi Badan</label>
            <input type="number" step="0.01" name="tinggi_badan" class="form-control">
        </div>

        <div class="col-md-3 mb-3">
            <label>Berat Badan</label>
            <input type="number" step="0.01" name="berat_badan" class="form-control">
        </div>

        <div class="col-md-3 mb-3">
            <label>Anak Ke</label>
            <input type="number" name="anak_ke" class="form-control">
        </div>

        <div class="col-md-3 mb-3">
            <label>Jumlah Saudara</label>
            <input type="number" name="jumlah_saudara" class="form-control">
        </div>
    </div>

    <hr class="my-4">

    <h4 class="mb-4">Upload Berkas</h4>

    <div class="mb-3">
        <label>Kartu Keluarga</label>
        <input type="file" name="kartu_keluarga" class="form-control">
    </div>

    <div class="mb-3">
        <label>Akta Kelahiran</label>
        <input type="file" name="akta_lahir" class="form-control">
    </div>

    <div class="mb-3">
        <label>Pas Foto</label>
        <input type="file" name="foto" class="form-control">
    </div>

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-secondary" onclick="showStep(4)">
            Sebelumnya
        </button>

        <button type="submit" class="btn btn-success btn-lg">
            Daftar Sekarang
        </button>
    </div>
</div>