<div id="step5" class="wizard-step d-none">
    <h4 class="mb-4">Data Periodik</h4>

    <div class="row g-3">
        <div class="col-md-3">
            <label for="tinggi_badan" class="form-label">Tinggi Badan <span class="text-danger">*</span></label>
            <input id="tinggi_badan" name="tinggi_badan" type="number" min="30" max="250" step="0.1" class="form-control" placeholder="Contoh: 115" inputmode="decimal" autocomplete="off" required>
            <small class="text-muted">Masukkan tinggi badan dalam sentimeter (cm).</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-3">
            <label for="berat_badan" class="form-label">Berat Badan <span class="text-danger">*</span></label>
            <input id="berat_badan" name="berat_badan" type="number" min="5" max="150" step="0.1" class="form-control" placeholder="Contoh: 20" inputmode="decimal" autocomplete="off" required>
            <small class="text-muted">Masukkan berat badan dalam kilogram (kg).</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-3">
            <label for="anak_ke" class="form-label">Anak Ke- <span class="text-danger">*</span></label>
            <input id="anak_ke" name="anak_ke" type="number" min="1" max="20" class="form-control" placeholder="Contoh: 2" inputmode="numeric" autocomplete="off" required>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-3">
            <label for="jumlah_saudara" class="form-label">Jumlah Saudara <span class="text-danger">*</span></label>
            <input id="jumlah_saudara" name="jumlah_saudara" type="number" min="0" max="20" class="form-control" placeholder="Contoh: 3" inputmode="numeric" autocomplete="off" required>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <hr class="my-4">

    <h4 class="mb-4">Upload Berkas</h4>

    <div class="row g-3">
        <div class="col-md-4">
            <label for="kartu_keluarga" class="form-label">Kartu Keluarga <span class="text-danger">*</span></label>
            <input id="kartu_keluarga" name="kartu_keluarga" type="file" class="form-control" accept=".pdf,.jpg,.jpeg" required>
            <small class="text-muted">Format PDF, JPG atau JPEG. Ukuran maksimal 2 MB.</small>
            <small class="text-muted file-info"></small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-4">
            <label for="akta_lahir" class="form-label">Akta Kelahiran <span class="text-danger">*</span></label>
            <input id="akta_lahir" name="akta_lahir" type="file" class="form-control" accept=".pdf,.jpg,.jpeg" required>
            <small class="text-muted">Format PDF, JPG atau JPEG. Ukuran maksimal 2 MB.</small>
            <small class="text-muted file-info"></small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-4">
            <label for="foto" class="form-label">Pas Foto <span class="text-danger">*</span></label>
            <input id="foto" name="foto" type="file" class="form-control" accept=".jpg,.jpeg,.png" required>
            <small class="text-muted">Format JPG, JPEG atau PNG. Ukuran maksimal 2 MB.</small>
            <small class="text-muted file-info"></small>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="form-check mt-4">
        <input id="declaration" name="declaration" type="checkbox" class="form-check-input" required>
        <label for="declaration" class="form-check-label">Saya menyatakan bahwa seluruh data dan dokumen yang saya unggah adalah benar, lengkap, dan dapat dipertanggungjawabkan.</label>
        <div class="invalid-feedback"></div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mt-4">
        <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm" onclick="showStep(4)">
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button type="button" class="btn btn-success btn-sm btn-confirm" 
        data-title="KONFIRMASI PENDAFTARAN"
        data-message="Pastikan seluruh data yang telah Anda isi sudah benar. Setelah dikirim, data tidak dapat diubah secara mandiri."
        data-button="btn-success">
            Kirim Pendaftaran <i class="bi bi-check2 ms-1"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form[action="{{ route('ppdb.store') }}"]') || document.querySelector('form');
        const step5Fields = {
            tinggi_badan: document.getElementById('tinggi_badan'),
            berat_badan: document.getElementById('berat_badan'),
            anak_ke: document.getElementById('anak_ke'),
            jumlah_saudara: document.getElementById('jumlah_saudara'),
            kartu_keluarga: document.getElementById('kartu_keluarga'),
            akta_lahir: document.getElementById('akta_lahir'),
            foto: document.getElementById('foto'),
            declaration: document.getElementById('declaration')
        };

        const fileRules = {
            kartu_keluarga: {
                extensions: ['pdf', 'jpg', 'jpeg'],
                maxSize: 2 * 1024 * 1024,
                requiredMessage: 'Kartu Keluarga wajib diunggah.',
                extensionMessage: 'Format Kartu Keluarga harus PDF, JPG atau JPEG.',
                sizeMessage: 'Ukuran Kartu Keluarga maksimal 2 MB.'
            },
            akta_lahir: {
                extensions: ['pdf', 'jpg', 'jpeg'],
                maxSize: 2 * 1024 * 1024,
                requiredMessage: 'Akta Kelahiran wajib diunggah.',
                extensionMessage: 'Format Akta Kelahiran harus PDF, JPG atau JPEG.',
                sizeMessage: 'Ukuran Akta Kelahiran maksimal 2 MB.'
            },
            foto: {
                extensions: ['jpg', 'jpeg', 'png'],
                maxSize: 2 * 1024 * 1024,
                requiredMessage: 'Pas Foto wajib diunggah.',
                extensionMessage: 'Format Pas Foto harus JPG, JPEG atau PNG.',
                sizeMessage: 'Ukuran Pas Foto maksimal 2 MB.'
            }
        };

        const parseFileSize = (bytes) => {
            if (bytes >= 1024 * 1024) {
                return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
            }
            if (bytes >= 1024) {
                return (bytes / 1024).toFixed(2) + ' KB';
            }
            return bytes + ' B';
        };

        const getFeedbackElement = (input) => {
            const wrapper = input.closest('.col-md-4, .col-md-3, .form-check');
            return wrapper ? wrapper.querySelector('.invalid-feedback') : null;
        };

        const setInvalid = (input, message) => {
            input.classList.add('is-invalid');
            const feedback = getFeedbackElement(input);
            if (feedback) {
                feedback.textContent = message;
            }
        };

        const clearInvalid = (input) => {
            input.classList.remove('is-invalid');
            const feedback = getFeedbackElement(input);
            if (feedback) {
                feedback.textContent = '';
            }
        };

        const sanitizeDecimal = (value) => {
            let sanitized = value.replace(/[^0-9.]/g, '');
            const parts = sanitized.split('.');
            if (parts.length > 2) {
                sanitized = parts[0] + '.' + parts.slice(1).join('');
            }
            return sanitized;
        };

        const sanitizeInteger = (value) => value.replace(/\D/g, '');

        const getFileInfoElement = (input) => {
            const wrapper = input.closest('.col-md-4');
            return wrapper ? wrapper.querySelector('.file-info') : null;
        };

        const updateFileInfo = (input) => {
            const info = getFileInfoElement(input);
            if (!info) {
                return;
            }
            const file = input.files[0];
            info.textContent = file ? `${file.name} (${parseFileSize(file.size)})` : '';
        };

        const validateFileInput = (input) => {
            clearInvalid(input);
            updateFileInfo(input);

            const rule = fileRules[input.name];
            const file = input.files[0];

            if (!file) {
                setInvalid(input, rule.requiredMessage);
                return false;
            }

            const extension = file.name.split('.').pop().toLowerCase();
            if (!rule.extensions.includes(extension)) {
                setInvalid(input, rule.extensionMessage);
                return false;
            }

            if (file.size > rule.maxSize) {
                setInvalid(input, rule.sizeMessage);
                return false;
            }

            return true;
        };

        const validateField = (input) => {
            clearInvalid(input);
            const value = input.value.trim();

            switch (input.name) {
                case 'tinggi_badan': {
                    if (!value) {
                        setInvalid(input, 'Tinggi badan wajib diisi.');
                        return false;
                    }
                    if (!/^\d+(\.\d+)?$/.test(value)) {
                        setInvalid(input, 'Tinggi badan harus berupa angka.');
                        return false;
                    }
                    const numeric = parseFloat(value);
                    if (numeric < 30 || numeric > 250 || Math.round(numeric * 10) !== numeric * 10) {
                        setInvalid(input, 'Tinggi badan harus antara 30 cm sampai 250 cm.');
                        return false;
                    }
                    return true;
                }
                case 'berat_badan': {
                    if (!value) {
                        setInvalid(input, 'Berat badan wajib diisi.');
                        return false;
                    }
                    if (!/^\d+(\.\d+)?$/.test(value)) {
                        setInvalid(input, 'Berat badan harus berupa angka.');
                        return false;
                    }
                    const numeric = parseFloat(value);
                    if (numeric < 5 || numeric > 150 || Math.round(numeric * 10) !== numeric * 10) {
                        setInvalid(input, 'Berat badan harus antara 5 kg sampai 150 kg.');
                        return false;
                    }
                    return true;
                }
                case 'anak_ke': {
                    if (!value) {
                        setInvalid(input, 'Data anak ke wajib diisi.');
                        return false;
                    }
                    if (!/^\d+$/.test(value)) {
                        setInvalid(input, 'Anak ke harus angka.');
                        return false;
                    }
                    const numeric = parseInt(value, 10);
                    if (numeric < 1 || numeric > 20) {
                        setInvalid(input, 'Anak ke harus antara 1 sampai 20.');
                        return false;
                    }
                    return true;
                }
                case 'jumlah_saudara': {
                    if (!value) {
                        setInvalid(input, 'Jumlah saudara wajib diisi.');
                        return false;
                    }
                    if (!/^\d+$/.test(value)) {
                        setInvalid(input, 'Jumlah saudara harus angka.');
                        return false;
                    }
                    const numeric = parseInt(value, 10);
                    if (numeric < 0 || numeric > 20) {
                        setInvalid(input, 'Jumlah saudara harus antara 0 sampai 20.');
                        return false;
                    }
                    return true;
                }
                case 'kartu_keluarga':
                case 'akta_lahir':
                case 'foto':
                    return validateFileInput(input);
                case 'declaration': {
                    if (!input.checked) {
                        setInvalid(input, 'Harap menyetujui pernyataan ini.');
                        return false;
                    }
                    return true;
                }
                default:
                    return true;
            }
        };

        const fieldOrder = [
            step5Fields.tinggi_badan,
            step5Fields.berat_badan,
            step5Fields.anak_ke,
            step5Fields.jumlah_saudara,
            step5Fields.kartu_keluarga,
            step5Fields.akta_lahir,
            step5Fields.foto,
            step5Fields.declaration
        ];

        const handleBlur = (input) => {
            if (input.name === 'tinggi_badan' || input.name === 'berat_badan') {
                input.value = sanitizeDecimal(input.value);
            }
            if (input.name === 'anak_ke' || input.name === 'jumlah_saudara') {
                input.value = sanitizeInteger(input.value);
            }
            validateField(input);
        };

        Object.values(step5Fields).forEach((input) => {
            if (!input) {
                return;
            }

            if (['tinggi_badan', 'berat_badan', 'anak_ke', 'jumlah_saudara'].includes(input.name)) {
                input.addEventListener('input', () => {
                    if (input.name === 'tinggi_badan' || input.name === 'berat_badan') {
                        const sanitized = sanitizeDecimal(input.value);
                        if (sanitized !== input.value) {
                            input.value = sanitized;
                        }
                    }
                    if (input.name === 'anak_ke' || input.name === 'jumlah_saudara') {
                        const sanitized = sanitizeInteger(input.value);
                        if (sanitized !== input.value) {
                            input.value = sanitized;
                        }
                    }
                });

                input.addEventListener('blur', () => handleBlur(input));
                return;
            }

            if (['kartu_keluarga', 'akta_lahir', 'foto'].includes(input.name)) {
                input.addEventListener('change', () => {
                    updateFileInfo(input);
                    validateField(input);
                });
                return;
            }

            if (input.name === 'declaration') {
                input.addEventListener('change', () => validateField(input));
            }
        });

    });
</script>