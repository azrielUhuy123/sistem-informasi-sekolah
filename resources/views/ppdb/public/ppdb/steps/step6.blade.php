<div id="step6" class="wizard-step d-none">
    <h4 class="mb-4">Data Periodik & Dokumen</h4>

    <div class="row g-3">
        <div class="col-md-4">
            <label for="anak_ke" class="form-label">Anak ke <span class="text-danger">*</span></label>
            <input id="anak_ke" name="anak_ke" type="number" min="1" max="20" class="form-control" placeholder="Contoh: 1">
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-4">
            <label for="jumlah_saudara" class="form-label">Jumlah Saudara <span class="text-danger">*</span></label>
            <input id="jumlah_saudara" name="jumlah_saudara" type="number" min="0" max="20" class="form-control" placeholder="Contoh: 2">
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-2">
            <label for="tinggi_badan" class="form-label">Tinggi Badan <span class="text-danger">*</span></label>
            <input id="tinggi_badan" name="tinggi_badan" type="number" min="30" max="250" step="0.1" class="form-control" placeholder="cm">
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-2">
            <label for="berat_badan" class="form-label">Berat Badan <span class="text-danger">*</span></label>
            <input id="berat_badan" name="berat_badan" type="number" min="5" max="150" step="0.1" class="form-control" placeholder="kg">
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label for="kartu_keluarga" class="form-label">Kartu Keluarga <span class="text-danger">*</span></label>
            <input id="kartu_keluarga" name="kartu_keluarga" type="file" class="form-control modern-input" accept=".pdf,.jpg,.jpeg">
            <small class="text-muted">Format PDF, JPG, atau JPEG. Maksimal 2 MB.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="ktp" class="form-label">KTP Orangtua (Kartu Tanda Penduduk) <span class="text-danger">*</span></label>
            <input id="ktp" name="ktp" type="file" class="form-control modern-input" accept=".pdf,.jpg,.jpeg">
            <small class="text-muted">Format PDF, JPG, atau JPEG. Maksimal 2 MB.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="ijazah" class="form-label">Ijazah Terakhir</label>
            <input id="ijazah" name="ijazah" type="file" class="form-control modern-input" accept=".pdf,.jpg,.jpeg">
            <small class="text-muted">Opsional. Format PDF, JPG, atau JPEG. Maksimal 2 MB.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="akta_lahir" class="form-label">Akta Kelahiran</label>
            <input id="akta_lahir" name="akta_lahir" type="file" class="form-control modern-input" accept=".pdf,.jpg,.jpeg">
            <small class="text-muted">Opsional. Format PDF, JPG, atau JPEG. Maksimal 2 MB.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="kartu_kip" class="form-label">Kartu Indonesia Pintar (KIP)</label>
            <input id="kartu_kip" name="kartu_kip" type="file" class="form-control modern-input" accept=".pdf,.jpg,.jpeg">
            <small class="text-muted">Opsional. Format PDF, JPG, atau JPEG. Maksimal 2 MB.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="foto" class="form-label">Pas Foto <span class="text-danger">*</span></label>
            <input id="foto" name="foto" type="file" class="form-control modern-input" accept=".jpg,.jpeg,.png">
            <small class="text-muted">Format JPG, JPEG, atau PNG. Maksimal 2 MB.</small>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="row g-3 mt-3">
        <div class="col-md-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="declaration" name="declaration">
                <label class="form-check-label" for="declaration">
                    Saya menyatakan bahwa data yang saya berikan adalah benar dan siap mengikuti seluruh prosedur PPDB.
                </label>
                <div class="invalid-feedback d-block"></div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mt-4">
        <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm" onclick="showStep(5)">
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button type="button" class="btn btn-success btn-sm btn-confirm" id="submitPpdbButton">
            Kirim Pendaftaran <i class="bi bi-send-check ms-1"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const step6Fields = {
            anak_ke: document.getElementById('anak_ke'),
            jumlah_saudara: document.getElementById('jumlah_saudara'),
            tinggi_badan: document.getElementById('tinggi_badan'),
            berat_badan: document.getElementById('berat_badan'),

            kartu_keluarga: document.getElementById('kartu_keluarga'),
            ktp: document.getElementById('ktp'),
            ijazah: document.getElementById('ijazah'),
            akta_lahir: document.getElementById('akta_lahir'),
            kartu_kip: document.getElementById('kartu_kip'),
            foto: document.getElementById('foto'),

            declaration: document.getElementById('declaration')
        };


        const setInvalid = (input, message) => {
            input.classList.add('is-invalid');

            const wrapper = input.closest(
                '.col-md-2, .col-md-4, .col-md-6, .col-md-12, .form-check'
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
                '.col-md-2, .col-md-4, .col-md-6, .col-md-12, .form-check'
            );

            const feedback = wrapper
                ? wrapper.querySelector('.invalid-feedback')
                : null;

            if (feedback) {
                feedback.textContent = '';
            }
        };


        const validateField = (input) => {
            clearInvalid(input);

            if (!input) {
                return true;
            }


            /* =========================
               DATA PERIODIK
            ========================= */

            if (
                input.id === 'anak_ke' ||
                input.id === 'jumlah_saudara' ||
                input.id === 'tinggi_badan' ||
                input.id === 'berat_badan'
            ) {
                const value = input.value.trim();

                if (!value) {
                    const labels = {
                        anak_ke: 'Anak ke',
                        jumlah_saudara: 'Jumlah saudara',
                        tinggi_badan: 'Tinggi badan',
                        berat_badan: 'Berat badan'
                    };

                    setInvalid(
                        input,
                        `${labels[input.id]} wajib diisi.`
                    );

                    return false;
                }


                if (input.id === 'anak_ke') {
                    const number = Number(value);

                    if (!Number.isInteger(number) || number < 1 || number > 20) {
                        setInvalid(
                            input,
                            'Anak ke harus berupa angka 1 sampai 20.'
                        );

                        return false;
                    }
                }


                if (input.id === 'jumlah_saudara') {
                    const number = Number(value);

                    if (!Number.isInteger(number) || number < 0 || number > 20) {
                        setInvalid(
                            input,
                            'Jumlah saudara harus berupa angka 0 sampai 20.'
                        );

                        return false;
                    }
                }


                if (input.id === 'tinggi_badan') {
                    const number = Number(value);

                    if (number < 30 || number > 250) {
                        setInvalid(
                            input,
                            'Tinggi badan harus berada antara 30 sampai 250 cm.'
                        );

                        return false;
                    }
                }


                if (input.id === 'berat_badan') {
                    const number = Number(value);

                    if (number < 5 || number > 150) {
                        setInvalid(
                            input,
                            'Berat badan harus berada antara 5 sampai 150 kg.'
                        );

                        return false;
                    }
                }

                return true;
            }


            /* =========================
               FILE DOKUMEN
            ========================= */

            if (
                input.id === 'kartu_keluarga' ||
                input.id === 'ktp' ||
                input.id === 'ijazah' ||
                input.id === 'akta_lahir' ||
                input.id === 'kartu_kip' ||
                input.id === 'foto'
            ) {
                const file = input.files[0];

                const requiredFiles = [
                    'kartu_keluarga',
                    'ktp',
                    'foto'
                ];

                const isRequired = requiredFiles.includes(input.id);


                // File wajib
                if (!file && isRequired) {
                    const labels = {
                        kartu_keluarga: 'Kartu Keluarga',
                        ktp: 'KTP Orangtua',
                        foto: 'Pas Foto'
                    };

                    setInvalid(
                        input,
                        `${labels[input.id]} wajib diunggah.`
                    );

                    return false;
                }


                // File opsional dan tidak diisi
                if (!file && !isRequired) {
                    return true;
                }


                // Maksimal 2 MB
                const maxSize = 2 * 1024 * 1024;

                if (file.size > maxSize) {
                    setInvalid(
                        input,
                        'Ukuran file maksimal 2 MB.'
                    );

                    return false;
                }


                // Format file
                const extension = file.name
                    .split('.')
                    .pop()
                    .toLowerCase();


                const documentExtensions = [
                    'pdf',
                    'jpg',
                    'jpeg'
                ];

                const photoExtensions = [
                    'jpg',
                    'jpeg',
                    'png'
                ];


                if (input.id === 'foto') {
                    if (!photoExtensions.includes(extension)) {
                        setInvalid(
                            input,
                            'Pas Foto harus berformat JPG, JPEG, atau PNG.'
                        );

                        return false;
                    }
                } else {
                    if (!documentExtensions.includes(extension)) {
                        setInvalid(
                            input,
                            'File harus berformat PDF, JPG, atau JPEG.'
                        );

                        return false;
                    }
                }

                return true;
            }


            /* =========================
               PERNYATAAN
            ========================= */

            if (input.id === 'declaration') {
                if (!input.checked) {
                    setInvalid(
                        input,
                        'Pernyataan wajib disetujui.'
                    );

                    return false;
                }

                return true;
            }


            return true;
        };


        /* =========================
           FORMAT & SANITASI
        ========================= */

        const handleInputFormatting = (input) => {

            if (
                input.type === 'number'
            ) {
                input.value = input.value.replace(/[^\d.]/g, '');
            }

        };


        /* =========================
           URUTAN FIELD
        ========================= */

        const fieldOrder = [
            step6Fields.anak_ke,
            step6Fields.jumlah_saudara,
            step6Fields.tinggi_badan,
            step6Fields.berat_badan,

            step6Fields.kartu_keluarga,
            step6Fields.ktp,
            step6Fields.ijazah,
            step6Fields.akta_lahir,
            step6Fields.kartu_kip,
            step6Fields.foto,

            step6Fields.declaration
        ];


        /* =========================
           EVENT VALIDASI FIELD
        ========================= */

        Object.values(step6Fields).forEach((input) => {

            if (!input) return;


            const eventType =
                input.type === 'file' ||
                input.type === 'checkbox'
                    ? 'change'
                    : 'blur';


            input.addEventListener(eventType, () => {
                validateField(input);
            });


            if (
                input.type === 'number'
            ) {
                input.addEventListener('input', () => {
                    handleInputFormatting(input);
                    clearInvalid(input);
                });
            }

        });


        /* =========================
           VALIDASI SEMUA STEP 6
        ========================= */

        window.validateStep6 = () => {

            let isValid = true;
            let firstInvalidField = null;


            fieldOrder.forEach((input) => {

                if (!input) return;

                const fieldValid = validateField(input);

                if (!fieldValid) {
                    isValid = false;

                    if (!firstInvalidField) {
                        firstInvalidField = input;
                    }
                }

            });


            if (firstInvalidField) {
                firstInvalidField.focus();
            }


            return isValid;
        };


        /* =========================
           VALIDASI TOMBOL KIRIM
        ========================= */

        const submitButton =
            document.getElementById('submitPpdbButton');


        if (submitButton) {

            submitButton.addEventListener('click', function (event) {

                if (!window.validateStep6()) {
                    event.preventDefault();
                    return false;
                }

            });

        }

    });
</script>
