<div id="step2" class="wizard-step d-none">
    <h4 class="mb-4">Data Alamat</h4>

    <div class="row">
        <div class="col-12 mb-3">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Mawar No. 15, Perumahan ABC" required></textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 mb-3">
            <label>RT</label>
            <input type="text" name="rt" class="form-control">
        </div>

        <div class="col-md-2 mb-3">
            <label>RW</label>
            <input type="text" name="rw" class="form-control">
        </div>

        <div class="col-md-4 mb-3">
            <label>Desa</label>
            <input type="text" name="desa" class="form-control" required>
        </div>

        <div class="col-md-4 mb-3">
            <label>Kecamatan</label>
            <input type="text" name="kecamatan" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Kabupaten</label>
            <input type="text" name="kabupaten" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>Provinsi</label>
            <input type="text" name="provinsi" class="form-control" required>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary" onclick="showStep(1)">
                <i class="bi bi-arrow-left me-1"></i>
                Sebelumnya
            </button>

            <button type="button" class="btn btn-primary" onclick="showStep(3)">
                
                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>
</div>