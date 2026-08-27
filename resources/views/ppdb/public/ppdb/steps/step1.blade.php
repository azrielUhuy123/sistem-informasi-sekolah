<div id="step1" class="wizard-step">
    <h4 class="mb-4">Data Pribadi</h4>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text"
                id="nama"
                name="nama"
                class="form-control"
                placeholder="Masukkan nama lengkap sesuai akta kelahiran"
                maxlength="100"
                autocomplete="name"
                required>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
            <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
            <input type="text"
                id="nik"
                name="nik"
                class="form-control"
                placeholder="Masukkan 16 digit NIK"
                maxlength="16"
                inputmode="numeric"
                autocomplete="off"
                required>
            <small class="text-muted">16 digit sesuai KTP/KK.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="nomor_kk" class="form-label">Nomor Kartu Keluarga <span class="text-danger">*</span></label>
            <input type="text"
                id="nomor_kk"
                name="nomor_kk"
                class="form-control"
                placeholder="Masukkan 16 digit nomor KK"
                maxlength="16"
                inputmode="numeric"
                autocomplete="off"
                required>
            <small class="text-muted">16 digit sesuai Kartu Keluarga.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="tempat_lahir" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
            <input type="text"
                id="tempat_lahir"
                name="tempat_lahir"
                class="form-control"
                placeholder="Masukkan kota/kabupaten tempat lahir"
                autocomplete="address-level2"
                required>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
            <input type="date"
                id="tanggal_lahir"
                name="tanggal_lahir"
                class="form-control"
                max="{{ date('Y-m-d') }}"
                required>
            <small class="text-muted">Pilih tanggal lahir tidak boleh melewati hari ini.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="nomor_akta" class="form-label">Nomor Akta Kelahiran <span class="text-danger">*</span></label>
            <input type="text"
                id="nomor_akta"
                name="nomor_akta"
                class="form-control"
                placeholder="Masukkan nomor akta kelahiran" 
                maxlength="30"
                required>
            <small class="text-muted">Nomor sesuai Akta Kelahiran.</small>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
            <select id="agama" name="agama" class="form-select" required>
                <option value="">Pilih Agama</option>
                <option value="Islam">Islam</option>
                <option value="Kristen Protestan">Kristen Protestan</option>
                <option value="Kristen Katolik">Kristen Katolik</option>
                <option value="Hindu">Hindu</option>
                <option value="Buddha">Buddha</option>
                <option value="Konghucu">Konghucu</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="kewarganegaraan" class="form-label">Kewarganegaraan <span class="text-danger">*</span></label>
            <select id="kewarganegaraan" name="kewarganegaraan" class="form-select" required>
                <option value="">Pilih Kewarganegaraan</option>
                <option value="Indonesia">Indonesia</option>
                <option value="WNA">Warga Negara Asing</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('ppdb.informasi') }}" class="btn btn-outline-secondary rounded-3 btn-sm">
            <i class="bi bi-chevron-left me-1"></i> Kembali
        </a>

        <button type="button" id="step1NextButton" class="btn btn-primary btn-sm">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const step1Fields = {
            nama: document.getElementById('nama'),
            jenis_kelamin: document.getElementById('jenis_kelamin'),
            nik: document.getElementById('nik'),
            nomor_kk: document.getElementById('nomor_kk'),
            tempat_lahir: document.getElementById('tempat_lahir'),
            tanggal_lahir: document.getElementById('tanggal_lahir'),
            nomor_akta: document.getElementById('nomor_akta'),
            agama: document.getElementById('agama'),
            kewarganegaraan: document.getElementById('kewarganegaraan')
        };

        const formatTitleCase = (value) => {
            return value
                .trim()
                .replace(/\s+/g, ' ')
                .toLowerCase()
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        };

        const sanitizeDigits = (value) => value.replace(/\D/g, '');

        const setInvalid = (input, message) => {
            input.classList.add('is-invalid');
            const feedback = input.closest('.col-md-6').querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = message;
            }
        };

        const clearInvalid = (input) => {
            input.classList.remove('is-invalid');
            const feedback = input.closest('.col-md-6').querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = '';
            }
        };

        const validateField = (input) => {
            clearInvalid(input);

            const value = input.value.trim();
            switch (input.name) {
                case 'nama':
                    if (!value) {
                        setInvalid(input, 'Nama lengkap wajib diisi.');
                        return false;
                    }
                    if (!/^[\p{L}\s'\.-]+$/u.test(value)) {
                        setInvalid(input, 'Nama hanya boleh berisi huruf.');
                        return false;
                    }
                    return true;
                case 'jenis_kelamin':
                    if (!value) {
                        setInvalid(input, 'Silakan pilih jenis kelamin.');
                        return false;
                    }
                    return true;
                case 'nik':
                    if (!value) {
                        setInvalid(input, 'NIK wajib diisi.');
                        return false;
                    }
                    if (!/^\d{16}$/.test(value)) {
                        setInvalid(input, 'NIK harus terdiri dari 16 digit angka.');
                        return false;
                    }
                    return true;
                case 'nomor_kk':
                    if (!value) {
                        setInvalid(input, 'Nomor KK wajib diisi.');
                        return false;
                    }
                    if (!/^\d{16}$/.test(value)) {
                        setInvalid(input, 'Nomor KK harus terdiri dari 16 digit angka.');
                        return false;
                    }
                    return true;
                case 'tempat_lahir':
                    if (!value) {
                        setInvalid(input, 'Tempat lahir wajib diisi.');
                        return false;
                    }
                    if (!/^[\p{L}\s'\.-]+$/u.test(value)) {
                        setInvalid(input, 'Tempat lahir hanya boleh berisi huruf.');
                        return false;
                    }
                    return true;
                case 'tanggal_lahir':
                    if (!value) {
                        setInvalid(input, 'Tanggal lahir wajib diisi.');
                        return false;
                    }
                    if (value > new Date().toISOString().slice(0, 10)) {
                        setInvalid(input, 'Tanggal lahir tidak boleh di masa depan.');
                        return false;
                    }
                    return true;
                case 'nomor_akta':
                    if (!value) {
                        setInvalid(input, 'Nomor Akta Kelahiran wajib diisi.');
                        return false;
                    }
                    return true;
                case 'agama':
                    if (!value) {
                        setInvalid(input, 'Silakan pilih agama.');
                        return false;
                    }
                    return true;
                case 'kewarganegaraan':
                    if (!value) {
                        setInvalid(input, 'Silakan pilih kewarganegaraan.');
                        return false;
                    }
                    return true;
                default:
                    return true;
            }
        };

        const handleBlurFormatting = (input) => {
            if (input.name === 'nama' || input.name === 'tempat_lahir') {
                input.value = formatTitleCase(input.value);
            }
            if (input.name === 'nik' || input.name === 'nomor_kk') {
                input.value = sanitizeDigits(input.value).slice(0, 16);
            }
            input.value = input.value.trim().replace(/\s+/g, ' ');
        };

        ['nama', 'tempat_lahir', 'nik', 'nomor_kk', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'kewarganegaraan', 'nomor_akta'].forEach((fieldName) => {
            const input = document.getElementsByName(fieldName)[0];
            if (!input) {
                return;
            }

            input.addEventListener('blur', () => {
                handleBlurFormatting(input);
                validateField(input);
            });

            input.addEventListener('input', () => {
                if (input.name === 'nik' || input.name === 'nomor_kk') {
                    const sanitized = input.value.replace(/\D/g, '');
                    if (sanitized !== input.value) {
                        input.value = sanitized;
                    }
                }

                if (input.name === 'nama' || input.name === 'tempat_lahir') {
                    const sanitized = input.value.replace(/[^\p{L}\s'-.]/gu, '');
                    if (sanitized !== input.value) {
                        input.value = sanitized;
                    }
                }
            });
        });

        document.getElementById('step1NextButton').addEventListener('click', () => {
            const fieldOrder = [
                step1Fields.nama,
                step1Fields.jenis_kelamin,
                step1Fields.nik,
                step1Fields.nomor_kk,
                step1Fields.tempat_lahir,
                step1Fields.tanggal_lahir,
                step1Fields.agama,
                step1Fields.kewarganegaraan,
                step1Fields.nomor_akta
            ];

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
                showStep(2);
            }
        });
    });
</script>