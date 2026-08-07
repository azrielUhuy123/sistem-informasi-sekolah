<div id="step2" class="wizard-step d-none">
    <h4 class="mb-4">Data Alamat</h4>

    <div class="row g-3">
        <div class="col-12">
            <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
            <div class="input-group">
                <select id="provinsi" name="provinsi" class="form-select" required>
                    <option value="">Pilih Provinsi</option>
                </select>
                <span id="wilayahLoading" class="input-group-text bg-white border-start-0 d-none" aria-live="polite">
                    <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                    <span class="visually-hidden">Memuat...</span>
                </span>
            </div>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="kabupaten" class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
            <select id="kabupaten" name="kabupaten" class="form-select" disabled required>
                <option value="">Pilih Kabupaten/Kota</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="kecamatan" class="form-label">Kecamatan <span class="text-danger">*</span></label>
            <select id="kecamatan" name="kecamatan" class="form-select" disabled required>
                <option value="">Pilih Kecamatan</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-6">
            <label for="desa" class="form-label">Kelurahan/Desa <span class="text-danger">*</span></label>
            <select id="desa" name="desa" class="form-select" disabled required>
                <option value="">Pilih Kelurahan/Desa</option>
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-3">
            <label for="rt" class="form-label">RT <span class="text-danger">*</span></label>
            <input id="rt" name="rt" type="text" inputmode="numeric" maxlength="3" class="form-control" placeholder="RT" pattern="\d{1,3}" required>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-md-3">
            <label for="rw" class="form-label">RW <span class="text-danger">*</span></label>
            <input id="rw" name="rw" type="text" inputmode="numeric" maxlength="3" class="form-control" placeholder="RW" pattern="\d{1,3}" required>
            <div class="invalid-feedback"></div>
        </div>

        <div class="col-12">
            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
            <textarea id="alamat" name="alamat" class="form-control" rows="3" placeholder="Jl. Mawar No. 15, Perumahan ABC" required></textarea>
            <div class="invalid-feedback"></div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showStep(1)">
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button type="button" id="step2NextButton" class="btn btn-primary btn-sm">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wilayahUrl = "{{ asset('data/wilayah.json') }}";
        const step2Fields = {
            provinsi: document.getElementById('provinsi'),
            kabupaten: document.getElementById('kabupaten'),
            kecamatan: document.getElementById('kecamatan'),
            desa: document.getElementById('desa'),
            rt: document.getElementById('rt'),
            rw: document.getElementById('rw'),
            alamat: document.getElementById('alamat')
        };
        const step2NextButton = document.getElementById('step2NextButton');
        const wilayahLoading = document.getElementById('wilayahLoading');

        let daerahData = [];

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
            const wrapper = input.closest('.col-md-6, .col-md-3, .col-12');
            const feedback = wrapper ? wrapper.querySelector('.invalid-feedback') : null;
            if (feedback) {
                feedback.textContent = message;
            }
        };

        const clearInvalid = (input) => {
            input.classList.remove('is-invalid');
            const wrapper = input.closest('.col-md-6, .col-md-3, .col-12');
            const feedback = wrapper ? wrapper.querySelector('.invalid-feedback') : null;
            if (feedback) {
                feedback.textContent = '';
            }
        };

        const resetSelect = (selectElement, label) => {
            selectElement.innerHTML = `<option value="">${label}</option>`;
            selectElement.disabled = true;
            clearInvalid(selectElement);
        };

        const populateSelect = (selectElement, items, label) => {
            const options = [`<option value="">${label}</option>`];
            items.forEach(item => options.push(`<option value="${item.nama}">${item.nama}</option>`));
            selectElement.innerHTML = options.join('');
            selectElement.disabled = false;
        };

        const loadWilayah = async () => {
            wilayahLoading.classList.remove('d-none');
            try {
                const response = await fetch(wilayahUrl, { cache: 'no-store' });
                if (!response.ok) {
                    throw new Error('Tidak dapat memuat data wilayah.');
                }
                daerahData = await response.json();
                populateSelect(step2Fields.provinsi, daerahData, 'Pilih Provinsi');
            } catch (error) {
                console.error(error);
                resetSelect(step2Fields.provinsi, 'Pilih Provinsi');
            } finally {
                wilayahLoading.classList.add('d-none');
            }
        };

        const findByName = (list, nama) => list.find(item => item.nama === nama);

        const handleProvinsiChange = () => {
            clearInvalid(step2Fields.provinsi);
            resetSelect(step2Fields.kabupaten, 'Pilih Kabupaten/Kota');
            resetSelect(step2Fields.kecamatan, 'Pilih Kecamatan');
            resetSelect(step2Fields.desa, 'Pilih Kelurahan/Desa');

            const provinsi = findByName(daerahData, step2Fields.provinsi.value);
            if (provinsi?.kabupaten?.length) {
                populateSelect(step2Fields.kabupaten, provinsi.kabupaten, 'Pilih Kabupaten/Kota');
            }
        };

        const handleKabupatenChange = () => {
            clearInvalid(step2Fields.kabupaten);
            resetSelect(step2Fields.kecamatan, 'Pilih Kecamatan');
            resetSelect(step2Fields.desa, 'Pilih Kelurahan/Desa');

            const provinsi = findByName(daerahData, step2Fields.provinsi.value);
            const kabupaten = provinsi?.kabupaten ? findByName(provinsi.kabupaten, step2Fields.kabupaten.value) : null;
            if (kabupaten?.kecamatan?.length) {
                populateSelect(step2Fields.kecamatan, kabupaten.kecamatan, 'Pilih Kecamatan');
            }
        };

        const handleKecamatanChange = () => {
            clearInvalid(step2Fields.kecamatan);
            resetSelect(step2Fields.desa, 'Pilih Kelurahan/Desa');

            const provinsi = findByName(daerahData, step2Fields.provinsi.value);
            const kabupaten = provinsi?.kabupaten ? findByName(provinsi.kabupaten, step2Fields.kabupaten.value) : null;
            const kecamatan = kabupaten?.kecamatan ? findByName(kabupaten.kecamatan, step2Fields.kecamatan.value) : null;
            if (kecamatan?.desa?.length) {
                populateSelect(step2Fields.desa, kecamatan.desa, 'Pilih Kelurahan/Desa');
            }
        };

        const validateField = (input) => {
            clearInvalid(input);
            const value = input.value ? input.value.trim() : '';

            if (input.id === 'provinsi' && !value) {
                setInvalid(input, 'Provinsi wajib diisi.');
                return false;
            }
            if (input.id === 'kabupaten' && !value) {
                setInvalid(input, 'Kabupaten/Kota wajib diisi.');
                return false;
            }
            if (input.id === 'kecamatan' && !value) {
                setInvalid(input, 'Kecamatan wajib diisi.');
                return false;
            }
            if (input.id === 'desa' && !value) {
                setInvalid(input, 'Kelurahan/Desa wajib diisi.');
                return false;
            }
            if (input.id === 'rt') {
                if (!value) {
                    setInvalid(input, 'RT wajib diisi.');
                    return false;
                }
                if (!/^\d{1,3}$/.test(value)) {
                    setInvalid(input, 'RT hanya boleh angka maksimal 3 digit.');
                    return false;
                }
                return true;
            }
            if (input.id === 'rw') {
                if (!value) {
                    setInvalid(input, 'RW wajib diisi.');
                    return false;
                }
                if (!/^\d{1,3}$/.test(value)) {
                    setInvalid(input, 'RW hanya boleh angka maksimal 3 digit.');
                    return false;
                }
                return true;
            }
            if (input.id === 'alamat') {
                if (!value) {
                    setInvalid(input, 'Alamat lengkap wajib diisi.');
                    return false;
                }
                return true;
            }
            return true;
        };

        const handleBlurFormatting = (input) => {
            if (input.tagName.toLowerCase() === 'input' || input.tagName.toLowerCase() === 'textarea') {
                input.value = input.value.trim().replace(/\s+/g, ' ');
            }
            if (input.id === 'rt' || input.id === 'rw') {
                input.value = sanitizeDigits(input.value).slice(0, 3);
            }
        };

        const fieldOrder = [
            step2Fields.provinsi,
            step2Fields.kabupaten,
            step2Fields.kecamatan,
            step2Fields.desa,
            step2Fields.rt,
            step2Fields.rw,
            step2Fields.alamat
        ];

        Object.values(step2Fields).forEach((input) => {
            if (!input) return;

            const eventType = input.tagName.toLowerCase() === 'select' ? 'change' : 'blur';
            input.addEventListener(eventType, () => {
                handleBlurFormatting(input);
                validateField(input);
            });

            if (input.tagName.toLowerCase() === 'input') {
                input.addEventListener('input', () => {
                    if (input.id === 'rt' || input.id === 'rw') {
                        const sanitized = sanitizeDigits(input.value);
                        if (sanitized !== input.value) {
                            input.value = sanitized;
                        }
                    }
                });
            }
        });

        step2Fields.provinsi.addEventListener('change', handleProvinsiChange);
        step2Fields.kabupaten.addEventListener('change', handleKabupatenChange);
        step2Fields.kecamatan.addEventListener('change', handleKecamatanChange);

        step2NextButton.addEventListener('click', () => {
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
                showStep(3);
            }
        });

        loadWilayah();
    });
</script>
