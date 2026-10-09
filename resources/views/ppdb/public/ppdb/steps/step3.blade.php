<div id="step3" class="wizard-step d-none">
    <h4 class="mb-4">Data Asal Sekolah</h4>

    <div class="row g-3">

        {{-- Jenis Pendidikan Asal --}}
        <div class="col-12">
            <label for="jenis_pendidikan_asal" class="form-label">
                Jenis Pendidikan Asal
                <span class="text-danger">*</span>
            </label>

            <select
                id="jenis_pendidikan_asal"
                name="jenis_pendidikan_asal"
                class="form-select"
            >
                <option value="">Pilih Jenis Pendidikan Asal</option>
                <option value="TK">TK (Taman Kanak-Kanak)</option>
                <option value="RA">RA (Raudhatul Athfal)</option>
                <option value="SPS">SPS (Satuan PAUD Sejenis)</option>
                <option value="KB">KB (Kelompok Bermain)</option>
                <option value="TPA">TPA (Taman Penitipan Anak)</option>
                <option value="Lainnya">Lainnya</option>
                <option value="Belum Pernah Sekolah">
                    Belum Pernah Sekolah
                </option>
            </select>

            <div class="invalid-feedback"></div>
        </div>


        {{-- Nama Sekolah Asal --}}
        <div
            class="col-md-6"
            id="namaSekolahAsalWrapper"
        >
            <label for="nama_sekolah_asal" class="form-label">
                Nama Sekolah Asal
                <span class="text-danger">*</span>
            </label>

            <input
                id="nama_sekolah_asal"
                name="nama_sekolah_asal"
                type="text"
                class="form-control"
                placeholder="Masukkan nama sekolah asal"
                maxlength="150"
            >

            <div class="invalid-feedback"></div>
        </div>


        {{-- NISN Sekolah Asal --}}
        <div
            class="col-md-6"
            id="nisnSekolahAsalWrapper"
        >
            <label for="nisn_sekolah_asal" class="form-label">
                NISN Sekolah Asal
                <span class="text-danger">*</span>
            </label>

            <input
                id="nisn_sekolah_asal"
                name="nisn_sekolah_asal"
                type="text"
                class="form-control"
                inputmode="numeric"
                maxlength="10"
                placeholder="Masukkan 10 digit NISN"
            >

            <small class="text-muted">
                NISN terdiri dari 10 digit angka.
            </small>

            <div class="invalid-feedback"></div>
        </div>

    </div>


    <hr class="my-4">


    {{-- Navigasi --}}
    <div class="d-flex justify-content-between align-items-center mt-4">

        <button
            type="button"
            class="btn btn-outline-secondary rounded-3 btn-sm"
            onclick="showStep(2)"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button
            type="button"
            class="btn btn-primary btn-sm"
            onclick="showStep(4)"
        >
            Selanjutnya
            <i class="bi bi-arrow-right ms-1"></i>
        </button>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const jenisPendidikanAsal =
            document.getElementById('jenis_pendidikan_asal');

        const namaSekolahAsal =
            document.getElementById('nama_sekolah_asal');

        const nisnSekolahAsal =
            document.getElementById('nisn_sekolah_asal');

        const namaSekolahWrapper =
            document.getElementById('namaSekolahAsalWrapper');

        const nisnWrapper =
            document.getElementById('nisnSekolahAsalWrapper');


        const setInvalid = (input, message) => {
            input.classList.add('is-invalid');

            const wrapper = input.closest(
                '.col-md-6, .col-12'
            );

            const feedback = wrapper
                ? wrapper.querySelector('.invalid-feedback')
                : null;

            if (feedback) {
                feedback.textContent = message;
            }
        };


        const clearInvalid = (input) => {
            input.classList.remove('is-invalid');

            const wrapper = input.closest(
                '.col-md-6, .col-12'
            );

            const feedback = wrapper
                ? wrapper.querySelector('.invalid-feedback')
                : null;

            if (feedback) {
                feedback.textContent = '';
            }
        };


        /*
        |--------------------------------------------------------------------------
        | Tampilkan / sembunyikan data sekolah asal
        |--------------------------------------------------------------------------
        */

        const toggleSekolahAsal = () => {

            const value =
                jenisPendidikanAsal.value;

            const shouldShow =
                value !== '' &&
                value !== 'Belum Pernah Sekolah';


            if (namaSekolahAsal) {

                namaSekolahAsal.disabled =
                    !shouldShow;

                if (!shouldShow) {
                    namaSekolahAsal.value = '';
                    clearInvalid(namaSekolahAsal);
                }
            }


            if (nisnSekolahAsal) {

                nisnSekolahAsal.disabled =
                    !shouldShow;

                if (!shouldShow) {
                    nisnSekolahAsal.value = '';
                    clearInvalid(nisnSekolahAsal);
                }
            }


            if (namaSekolahWrapper) {
                namaSekolahWrapper.style.display =
                    shouldShow ? 'block' : 'none';
            }

            if (nisnWrapper) {
                nisnWrapper.style.display =
                    shouldShow ? 'block' : 'none';
            }
        };


        /*
        |--------------------------------------------------------------------------
        | Validasi field
        |--------------------------------------------------------------------------
        */

        const validateField = (input) => {

            clearInvalid(input);

            const value =
                input.value
                    ? input.value.trim()
                    : '';


            /*
            | Jenis Pendidikan Asal
            */

            if (
                input.id === 'jenis_pendidikan_asal' &&
                !value
            ) {
                setInvalid(
                    input,
                    'Jenis pendidikan asal wajib dipilih.'
                );

                return false;
            }


            /*
            | Nama Sekolah Asal
            */

            if (
                input.id === 'nama_sekolah_asal'
            ) {

                if (
                    jenisPendidikanAsal.value ===
                    'Belum Pernah Sekolah'
                ) {
                    return true;
                }

                if (
                    jenisPendidikanAsal.value &&
                    !value
                ) {
                    setInvalid(
                        input,
                        'Nama sekolah asal wajib diisi.'
                    );

                    return false;
                }

                return true;
            }


            /*
            | NISN
            */

            if (
                input.id === 'nisn_sekolah_asal'
            ) {

                if (
                    jenisPendidikanAsal.value ===
                    'Belum Pernah Sekolah'
                ) {
                    return true;
                }

                if (
                    jenisPendidikanAsal.value &&
                    !value
                ) {
                    setInvalid(
                        input,
                        'NISN sekolah asal wajib diisi.'
                    );

                    return false;
                }

                if (
                    value &&
                    !/^\d{10}$/.test(value)
                ) {
                    setInvalid(
                        input,
                        'NISN harus terdiri dari 10 digit angka.'
                    );

                    return false;
                }

                return true;
            }


            return true;
        };


        /*
        |--------------------------------------------------------------------------
        | Event jenis pendidikan
        |--------------------------------------------------------------------------
        */

        jenisPendidikanAsal.addEventListener(
            'change',
            function () {

                toggleSekolahAsal();

                validateField(this);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Event nama sekolah
        |--------------------------------------------------------------------------
        */

        namaSekolahAsal.addEventListener(
            'blur',
            function () {

                this.value =
                    this.value
                        .trim()
                        .replace(/\s+/g, ' ');

                validateField(this);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Event NISN
        |--------------------------------------------------------------------------
        */

        nisnSekolahAsal.addEventListener(
            'blur',
            function () {

                validateField(this);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NISN hanya boleh angka
        |--------------------------------------------------------------------------
        */

        nisnSekolahAsal.addEventListener(
            'input',
            function () {

                const sanitized =
                    this.value.replace(/\D/g, '');

                if (sanitized !== this.value) {
                    this.value = sanitized;
                }
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Inisialisasi
        |--------------------------------------------------------------------------
        */

        toggleSekolahAsal();

    });
</script>