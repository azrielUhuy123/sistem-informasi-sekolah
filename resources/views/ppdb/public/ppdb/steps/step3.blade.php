<div id="step3" class="wizard-step d-none">
    <h4 class="mb-4">Data Orang Tua</h4>

    <h5 class="mb-3">Data Ayah</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Nama Ayah</label>
            <input type="text" name="nama_ayah" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>NIK Ayah</label>
            <input type="text" name="nik_ayah" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Tempat Lahir Ayah</label>
            <input type="text" name="tempat_lahir_ayah" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Tanggal Lahir Ayah</label>
            <input type="date" name="tanggal_lahir_ayah" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Pendidikan Ayah</label>
            <input type="text" name="pendidikan_ayah" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Pekerjaan Ayah</label>
            <input type="text" name="pekerjaan_ayah" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Penghasilan Ayah</label>
            <select name="penghasilan_ayah" class="form-select">
                <option value="<500 Ribu">< 500 Ribu</option>
                <option value="500 Ribu - 999 Ribu">500 Ribu - 999 Ribu</option>
                <option value="1 Juta - 2 Juta">1 Juta - 2 Juta</option>
                <option value="2 Juta - 4 Juta">2 Juta - 4 Juta</option>
                <option value="> 5 Juta">> 5 Juta</option>
            </select>
        </div>
    </div>

    <hr class="my-4">

    <h5 class="mb-3">Data Ibu</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Nama Ibu</label>
            <input type="text" name="nama_ibu" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label>NIK Ibu</label>
            <input type="text" name="nik_ibu" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Tempat Lahir Ibu</label>
            <input type="text" name="tempat_lahir_ibu" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Tanggal Lahir Ibu</label>
            <input type="date" name="tanggal_lahir_ibu" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Pendidikan Ibu</label>
            <input type="text" name="pendidikan_ibu" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Pekerjaan Ibu</label>
            <input type="text" name="pekerjaan_ibu" class="form-control">
        </div>

        <div class="col-md-6 mb-3">
            <label>Penghasilan Ibu</label>
            <select name="penghasilan_ibu" class="form-select">
                <option value="<500 Ribu">< 500 Ribu</option>
                <option value="500 Ribu - 999 Ribu">500 Ribu - 999 Ribu</option>
                <option value="1 Juta - 2 Juta">1 Juta - 2 Juta</option>
                <option value="2 Juta - 4 Juta">2 Juta - 4 Juta</option>
                <option value="> 5 Juta">> 5 Juta</option>
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-secondary" onclick="showStep(2)">
            Sebelumnya
        </button>

        <button type="button" class="btn btn-primary" onclick="showStep(4)">
            Selanjutnya
        </button>
    </div>
</div>