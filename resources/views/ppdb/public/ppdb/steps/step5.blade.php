<div id="step5" class="wizard-step d-none">
    <h4 class="mb-4">Kontak</h4>

    <div class="row g-3">        

        <div class="col-12">
            <label for="nomor_hp" class="form-label">Nomor HP/WhatsApp <span class="text-danger">*</span></label>
            <input id="nomor_hp" name="nomor_hp" type="text" class="form-control" maxlength="13" inputmode="numeric" autocomplete="tel-national" placeholder="Contoh: 081234567890">
            <small class="text-muted">Nomor utama yang dapat dihubungi.</small>
            <div class="invalid-feedback"></div>
        </div>

    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input id="email" name="email" type="email" class="form-control" maxlength="255" autocomplete="email" placeholder="contoh@email.com">
            <small class="text-muted">Email digunakan untuk menerima informasi dan status pendaftaran.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="email_confirmation" class="form-label">Konfirmasi Email <span class="text-danger">*</span></label>
            <input id="email_confirmation" name="email_confirmation" type="email" class="form-control" maxlength="255" autocomplete="email" placeholder="Masukkan kembali email">
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mt-4">
        <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm" onclick="showStep(4)">
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button type="button" class="btn btn-primary btn-sm" onclick="showStep(6)">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const nomorHp = document.getElementById('nomor_hp');
        const email = document.getElementById('email');
        const emailConfirmation = document.getElementById('email_confirmation');

        const getWrapper = (input) => {
            return input.closest('.col-12, .col-md-6');
        };

        const setInvalid = (input, message) => {
            if (!input) return;

            input.classList.add('is-invalid');

            const wrapper = getWrapper(input);
            const feedback = wrapper
                ? wrapper.querySelector('.invalid-feedback')
                : null;

            if (feedback) {
                feedback.textContent = message;
            }
        };

        const clearInvalid = (input) => {
            if (!input) return;

            input.classList.remove('is-invalid');

            const wrapper = getWrapper(input);
            const feedback = wrapper
                ? wrapper.querySelector('.invalid-feedback')
                : null;

            if (feedback) {
                feedback.textContent = '';
            }
        };

        const sanitizePhone = (value) => {
            return value.replace(/\D/g, '').slice(0, 13);
        };

        const validateNomorHp = () => {
            clearInvalid(nomorHp);

            const value = nomorHp.value.trim();

            if (!value) {
                setInvalid(
                    nomorHp,
                    'Nomor HP/WhatsApp wajib diisi.'
                );
                return false;
            }

            if (!/^08\d{8,11}$/.test(value)) {
                setInvalid(
                    nomorHp,
                    'Nomor HP/WhatsApp harus diawali 08 dan terdiri dari 10–13 digit.'
                );
                return false;
            }

            return true;
        };

        const validateEmail = () => {
            clearInvalid(email);

            const value = email.value.trim();

            // Email bersifat opsional
            if (!value) {
                setInvalid(
                    email,
                    'Email wajib diisi.'
                );
                return false;
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(value)) {
                setInvalid(
                    email,
                    'Format email tidak valid.'
                );
                return false;
            }

            return true;
        };

        const validateEmailConfirmation = () => {
            clearInvalid(emailConfirmation);

            const emailValue = email.value.trim();
            const confirmationValue = emailConfirmation.value.trim();

            if (!confirmationValue) {
                setInvalid(
                    emailConfirmation,
                    'Konfirmasi email wajib diisi.'
                );
                return false;
            }

            if (emailValue !== confirmationValue) {
                setInvalid(
                    emailConfirmation,
                    'Konfirmasi email tidak sama dengan email.'
                );
                return false;
            }

            return true;
        };

        // Nomor HP
        nomorHp?.addEventListener('input', function () {
            const sanitized = sanitizePhone(this.value);

            if (this.value !== sanitized) {
                this.value = sanitized;
            }

            if (this.classList.contains('is-invalid')) {
                validateNomorHp();
            }
        });

        nomorHp?.addEventListener('blur', function () {
            this.value = sanitizePhone(this.value);
            validateNomorHp();
        });

        // Email
        email?.addEventListener('blur', function () {
            this.value = this.value.trim();
            validateEmail();

            // Jika konfirmasi sudah diisi,
            // cek kembali kecocokannya.
            if (emailConfirmation.value.trim()) {
                validateEmailConfirmation();
            }
        });

        email?.addEventListener('input', function () {
            if (this.classList.contains('is-invalid')) {
                validateEmail();
            }

            if (emailConfirmation.value.trim()) {
                validateEmailConfirmation();
            }
        });

        // Konfirmasi email
        emailConfirmation?.addEventListener('blur', function () {
            this.value = this.value.trim();
            validateEmailConfirmation();
        });

        emailConfirmation?.addEventListener('input', function () {
            if (this.classList.contains('is-invalid')) {
                validateEmailConfirmation();
            }
        });

    });
</script>
