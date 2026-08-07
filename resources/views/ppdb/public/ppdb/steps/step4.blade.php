<div id="step4" class="wizard-step d-none">
    <h4 class="mb-4">Kontak</h4>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="telepon_rumah" class="form-label">Nomor Telepon Rumah <span class="text-danger">*</span></label>
            <input id="telepon_rumah" name="telepon_rumah" type="text" class="form-control" maxlength="15" inputmode="numeric" autocomplete="tel" required>
            <small class="text-muted">Masukkan nomor telepon yang dapat dihubungi.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="hp_ayah" class="form-label">Nomor HP/WhatsApp Ayah <span class="text-danger">*</span></label>
            <input id="hp_ayah" name="hp_ayah" type="text" class="form-control" maxlength="13" inputmode="numeric" autocomplete="tel-national" required>
            <small class="text-muted">Masukkan nomor WhatsApp Ayah yang aktif.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="hp_ibu" class="form-label">Nomor HP/WhatsApp Ibu <span class="text-danger">*</span></label>
            <input id="hp_ibu" name="hp_ibu" type="text" class="form-control" maxlength="13" inputmode="numeric" autocomplete="tel-national" required>
            <small class="text-muted">Masukkan nomor WhatsApp Ibu yang aktif.</small>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showStep(3)">
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button type="button" class="btn btn-primary btn-sm" id="step4-next">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const step4Fields = {
                telepon_rumah: document.getElementById('telepon_rumah'),
                hp_ayah: document.getElementById('hp_ayah'),
                hp_ibu: document.getElementById('hp_ibu')
            };

            const nextButton = document.getElementById('step4-next');

            const sanitizeDigits = (value) => value.replace(/\D/g, '');

            const setInvalid = (input, message) => {
                input.classList.add('is-invalid');
                const wrapper = input.closest('.mb-3');
                const feedback = wrapper ? wrapper.querySelector('.invalid-feedback') : null;
                if (feedback) {
                    feedback.textContent = message;
                }
            };

            const clearInvalid = (input) => {
                input.classList.remove('is-invalid');
                const wrapper = input.closest('.mb-3');
                const feedback = wrapper ? wrapper.querySelector('.invalid-feedback') : null;
                if (feedback) {
                    feedback.textContent = '';
                }
            };

            const validateField = (input) => {
                clearInvalid(input);
                const value = input.value.trim();

                switch (input.name) {
                    case 'telepon_rumah':
                        if (!value) {
                            setInvalid(input, 'Nomor telepon rumah wajib diisi.');
                            return false;
                        }
                        if (!/^\d{8,15}$/.test(value)) {
                            setInvalid(input, 'Nomor telepon rumah harus terdiri dari 8–15 digit.');
                            return false;
                        }
                        return true;
                    case 'hp_ayah':
                        if (!value) {
                            setInvalid(input, 'Nomor HP Ayah wajib diisi.');
                            return false;
                        }
                        if (!/^08\d{8,11}$/.test(value)) {
                            setInvalid(input, 'Nomor HP Ayah tidak valid.');
                            return false;
                        }
                        return true;
                    case 'hp_ibu':
                        if (!value) {
                            setInvalid(input, 'Nomor HP Ibu wajib diisi.');
                            return false;
                        }
                        if (!/^08\d{8,11}$/.test(value)) {
                            setInvalid(input, 'Nomor HP Ibu tidak valid.');
                            return false;
                        }
                        return true;
                    default:
                        return true;
                }
            };

            const handleBlurFormatting = (input) => {
                input.value = input.value.trim().replace(/\s+/g, ' ');
            };

            const fieldOrder = [
                step4Fields.telepon_rumah,
                step4Fields.hp_ayah,
                step4Fields.hp_ibu
            ];

            Object.values(step4Fields).forEach((input) => {
                if (!input) return;

                input.addEventListener('blur', () => {
                    handleBlurFormatting(input);
                    validateField(input);
                });

                input.addEventListener('input', () => {
                    const sanitized = sanitizeDigits(input.value).slice(0, input.maxLength || 15);
                    if (sanitized !== input.value) {
                        input.value = sanitized;
                    }
                });
            });

            nextButton.addEventListener('click', () => {
                let firstInvalid = null;
                let isValid = true;

                fieldOrder.forEach((field) => {
                    if (field && !validateField(field)) {
                        isValid = false;
                        if (!firstInvalid) {
                            firstInvalid = field;
                        }
                    }
                });

                if (firstInvalid) {
                    firstInvalid.focus();
                }

                if (isValid) {
                    showStep(5);
                }
            });
        });
    </script>
</div>