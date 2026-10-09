<div id="step4" class="wizard-step d-none">
    <h4 class="mb-4">Data Orang Tua/Wali</h4>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <h5 class="card-title">Data Ayah</h5>

            <div class="mb-3">
                <label for="nama_ayah" class="form-label">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                <input id="nama_ayah" name="nama_ayah" type="text" class="form-control" placeholder="Masukkan nama lengkap ayah" maxlength="100" autocomplete="name">
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="nik_ayah" class="form-label">NIK Ayah</label>
                <input id="nik_ayah" name="nik_ayah" type="text" inputmode="numeric" maxlength="16" class="form-control" autocomplete="off" placeholder="16 digit NIK (opsional)">
                <small class="text-muted">Opsional. Jika diisi, harus 16 digit angka.</small>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tempat_lahir_ayah" class="form-label">Tempat Lahir Ayah <span class="text-danger">*</span></label>
                <input id="tempat_lahir_ayah" name="tempat_lahir_ayah" type="text" class="form-control" placeholder="Tempat lahir ayah" autocomplete="address-level2" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir_ayah" class="form-label">Tanggal Lahir Ayah <span class="text-danger">*</span></label>
                <input id="tanggal_lahir_ayah" name="tanggal_lahir_ayah" type="date" class="form-control" autocomplete="bday" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="agama_ayah" class="form-label">
                    Agama <span class="text-danger">*</span>
                </label>
                <select id="agama_ayah" name="agama_ayah" class="form-select" required>
                    <option value="">-- Pilih Agama --</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen Protestan">Kristen Protestan</option>
                    <option value="Kristen Katolik">Kristen Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="pendidikan_ayah" class="form-label">Pendidikan Ayah <span class="text-danger">*</span></label>
                <select id="pendidikan_ayah" name="pendidikan_ayah" class="form-select" required>
                    <option value="" selected>Pilih Pendidikan Terakhir</option>
                    <option>Tidak/Belum Sekolah</option>
                    <option>SD</option>
                    <option>SMP</option>
                    <option>SMA/SMK</option>
                    <option>Diploma I</option>
                    <option>Diploma II</option>
                    <option>Diploma III</option>
                    <option>Diploma IV</option>
                    <option>Sarjana (S1)</option>
                    <option>Magister (S2)</option>
                    <option>Doktor (S3)</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah <span class="text-danger">*</span></label>
                <select id="pekerjaan_ayah" name="pekerjaan_ayah" class="form-select" required>
                    <option value="" selected>Pilih Pekerjaan</option>
                    <option>Tidak/Belum Bekerja</option>
                    <option>Mengurus Rumah Tangga (IRT)</option>
                    <option>Petani</option>
                    <option>Nelayan</option>
                    <option>Buruh</option>
                    <option>Pedagang</option>
                    <option>Wiraswasta</option>
                    <option>Pegawai Swasta</option>
                    <option>Pegawai BUMN</option>
                    <option>PNS</option>
                    <option>TNI</option>
                    <option>POLRI</option>
                    <option>Guru</option>
                    <option>Dosen</option>
                    <option>Tenaga Kesehatan</option>
                    <option>Pengemudi/Ojek Online</option>
                    <option>Pensiunan</option>
                    <option>Lainnya</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-0">
                <label for="penghasilan_ayah" class="form-label">Penghasilan Ayah</label>
                <select id="penghasilan_ayah" name="penghasilan_ayah" class="form-select">
                    <option value="" selected>Pilih Rentang Penghasilan</option>
                    <option value="<500.000">&lt; Rp500.000</option>
                    <option value="500.000-999.999">Rp500.000 – Rp999.999</option>
                    <option value="1.000.000-1.999.999">Rp1.000.000 – Rp1.999.999</option>
                    <option value="2.000.000-4.999.999">Rp2.000.000 – Rp4.999.999</option>
                    <option value=">5.000.000">>Rp5.000.000</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <h5 class="card-title">Data Ibu</h5>

            <div class="mb-3">
                <label for="nama_ibu" class="form-label">Nama Lengkap Ibu <span class="text-danger">*</span></label>
                <input id="nama_ibu" name="nama_ibu" type="text" class="form-control" placeholder="Masukkan nama lengkap ibu" maxlength="100" autocomplete="name">
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="nik_ibu" class="form-label">NIK Ibu</label>
                <input id="nik_ibu" name="nik_ibu" type="text" inputmode="numeric" maxlength="16" class="form-control" autocomplete="off" placeholder="16 digit NIK (opsional)">
                <small class="text-muted">Opsional. Jika diisi, harus 16 digit angka.</small>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tempat_lahir_ibu" class="form-label">Tempat Lahir Ibu <span class="text-danger">*</span></label>
                <input id="tempat_lahir_ibu" name="tempat_lahir_ibu" type="text" class="form-control" placeholder="Tempat lahir ibu" autocomplete="address-level2" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir_ibu" class="form-label">Tanggal Lahir Ibu <span class="text-danger">*</span></label>
                <input id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" type="date" class="form-control" autocomplete="bday" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="agama_ibu" class="form-label">
                    Agama <span class="text-danger">*</span>
                </label>
                <select id="agama_ibu" name="agama_ibu" class="form-select" required>
                    <option value="">-- Pilih Agama --</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen Protestan">Kristen Protestan</option>
                    <option value="Kristen Katolik">Kristen Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="pendidikan_ibu" class="form-label">Pendidikan Ibu <span class="text-danger">*</span></label>
                <select id="pendidikan_ibu" name="pendidikan_ibu" class="form-select" required>
                    <option value="" selected>Pilih Pendidikan Terakhir</option>
                    <option>Tidak/Belum Sekolah</option>
                    <option>SD</option>
                    <option>SMP</option>
                    <option>SMA/SMK</option>
                    <option>Diploma I</option>
                    <option>Diploma II</option>
                    <option>Diploma III</option>
                    <option>Diploma IV</option>
                    <option>Sarjana (S1)</option>
                    <option>Magister (S2)</option>
                    <option>Doktor (S3)</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu <span class="text-danger">*</span></label>
                <select id="pekerjaan_ibu" name="pekerjaan_ibu" class="form-select" required>
                    <option value="" selected>Pilih Pekerjaan</option>
                    <option>Tidak/Belum Bekerja</option>
                    <option>Mengurus Rumah Tangga (IRT)</option>
                    <option>Petani</option>
                    <option>Nelayan</option>
                    <option>Buruh</option>
                    <option>Pedagang</option>
                    <option>Wiraswasta</option>
                    <option>Pegawai Swasta</option>
                    <option>Pegawai BUMN</option>
                    <option>PNS</option>
                    <option>TNI</option>
                    <option>POLRI</option>
                    <option>Guru</option>
                    <option>Dosen</option>
                    <option>Tenaga Kesehatan</option>
                    <option>Pengemudi/Ojek Online</option>
                    <option>Pensiunan</option>
                    <option>Lainnya</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-0">
                <label for="penghasilan_ibu" class="form-label">Penghasilan Ibu</label>
                <select id="penghasilan_ibu" name="penghasilan_ibu" class="form-select">
                    <option value="" selected>Pilih Rentang Penghasilan</option>
                    <option value="<500.000">&lt; Rp500.000</option>
                    <option value="500.000-999.999">Rp500.000 – Rp999.999</option>
                    <option value="1.000.000-1.999.999">Rp1.000.000 – Rp1.999.999</option>
                    <option value="2.000.000-4.999.999">Rp2.000.000 – Rp4.999.999</option>
                    <option value=">5.000.000">>Rp5.000.000</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm" onclick="showStep(3)">
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button type="button" class="btn btn-primary btn-sm" onclick="showStep(5)">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const step4Fields = {
            nama_ayah: document.getElementById('nama_ayah'),
            nik_ayah: document.getElementById('nik_ayah'),
            tempat_lahir_ayah: document.getElementById('tempat_lahir_ayah'),
            tanggal_lahir_ayah: document.getElementById('tanggal_lahir_ayah'),
            agama_ayah: document.getElementById('agama_ayah'),
            pendidikan_ayah: document.getElementById('pendidikan_ayah'),
            pekerjaan_ayah: document.getElementById('pekerjaan_ayah'),
            penghasilan_ayah: document.getElementById('penghasilan_ayah'),

            nama_ibu: document.getElementById('nama_ibu'),
            nik_ibu: document.getElementById('nik_ibu'),
            tempat_lahir_ibu: document.getElementById('tempat_lahir_ibu'),
            tanggal_lahir_ibu: document.getElementById('tanggal_lahir_ibu'),
            agama_ibu: document.getElementById('agama_ibu'),
            pendidikan_ibu: document.getElementById('pendidikan_ibu'),
            pekerjaan_ibu: document.getElementById('pekerjaan_ibu'),
            penghasilan_ibu: document.getElementById('penghasilan_ibu')
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

            const wrapper = input.closest('.mb-3, .mb-0');
            const feedback = wrapper ? wrapper.querySelector('.invalid-feedback') : null;

            if (feedback) {
                feedback.textContent = message;
            }
        };

        const clearInvalid = (input) => {
            input.classList.remove('is-invalid');

            const wrapper = input.closest('.mb-3, .mb-0');
            const feedback = wrapper ? wrapper.querySelector('.invalid-feedback') : null;

            if (feedback) {
                feedback.textContent = '';
            }
        };

        const validateField = (input) => {
            clearInvalid(input);

            const value = input.value ? input.value.trim() : '';

            // Field wajib Ayah
            if (
                input.id === 'nama_ayah' ||
                input.id === 'tempat_lahir_ayah' ||
                input.id === 'tanggal_lahir_ayah' ||
                input.id === 'agama_ayah' ||
                input.id === 'pendidikan_ayah' ||
                input.id === 'pekerjaan_ayah'
            ) {
                if (!value) {
                    const labels = {
                        nama_ayah: 'Nama lengkap Ayah',
                        tempat_lahir_ayah: 'Tempat lahir Ayah',
                        tanggal_lahir_ayah: 'Tanggal lahir Ayah',
                        agama_ayah: 'Agama Ayah',
                        pendidikan_ayah: 'Pendidikan Ayah',
                        pekerjaan_ayah: 'Pekerjaan Ayah'
                    };

                    setInvalid(input, `${labels[input.id]} wajib diisi.`);
                    return false;
                }
            }

            // Validasi khusus nama Ayah
            if (input.id === 'nama_ayah') {
                if (!/^[\p{L}\s'\.-]+$/u.test(value)) {
                    setInvalid(input, 'Nama hanya boleh berisi huruf.');
                    return false;
                }

                return true;
            }

            // NIK Ayah opsional, tetapi jika diisi harus 16 digit
            if (input.id === 'nik_ayah') {
                if (value && !/^\d{16}$/.test(value)) {
                    setInvalid(input, 'NIK Ayah harus 16 digit angka.');
                    return false;
                }

                return true;
            }

            // Field wajib Ibu
            if (
                input.id === 'nama_ibu' ||
                input.id === 'tempat_lahir_ibu' ||
                input.id === 'tanggal_lahir_ibu' ||
                input.id === 'agama_ibu' ||
                input.id === 'pendidikan_ibu' ||
                input.id === 'pekerjaan_ibu'
            ) {
                if (!value) {
                    const labels = {
                        nama_ibu: 'Nama lengkap Ibu',
                        tempat_lahir_ibu: 'Tempat lahir Ibu',
                        tanggal_lahir_ibu: 'Tanggal lahir Ibu',
                        agama_ibu: 'Agama Ibu',
                        pendidikan_ibu: 'Pendidikan Ibu',
                        pekerjaan_ibu: 'Pekerjaan Ibu'
                    };

                    setInvalid(input, `${labels[input.id]} wajib diisi.`);
                    return false;
                }
            }

            // Validasi khusus nama Ibu
            if (input.id === 'nama_ibu') {
                if (!/^[\p{L}\s'\.-]+$/u.test(value)) {
                    setInvalid(input, 'Nama hanya boleh berisi huruf.');
                    return false;
                }

                return true;
            }

            // NIK Ibu opsional, tetapi jika diisi harus 16 digit
            if (input.id === 'nik_ibu') {
                if (value && !/^\d{16}$/.test(value)) {
                    setInvalid(input, 'NIK Ibu harus 16 digit angka.');
                    return false;
                }

                return true;
            }

            // Penghasilan Ayah dan Ibu opsional
            if (
                input.id === 'penghasilan_ayah' ||
                input.id === 'penghasilan_ibu'
            ) {
                return true;
            }

            return true;
        };

        const handleBlurFormatting = (input) => {
            if (
                input.tagName.toLowerCase() === 'input' ||
                input.tagName.toLowerCase() === 'textarea'
            ) {
                input.value = input.value.trim().replace(/\s+/g, ' ');
            }

            // Format nama seperti Step 1
            if (
                input.id === 'nama_ayah' ||
                input.id === 'nama_ibu'
            ) {
                input.value = formatTitleCase(input.value);
            }

            if (
                input.id === 'nik_ayah' ||
                input.id === 'nik_ibu'
            ) {
                input.value = sanitizeDigits(input.value).slice(0, 16);
            }
        };

        const fieldOrder = [
            step4Fields.nama_ayah,
            step4Fields.nik_ayah,
            step4Fields.tempat_lahir_ayah,
            step4Fields.tanggal_lahir_ayah,
            step4Fields.agama_ayah,
            step4Fields.pendidikan_ayah,
            step4Fields.pekerjaan_ayah,
            step4Fields.penghasilan_ayah,

            step4Fields.nama_ibu,
            step4Fields.nik_ibu,
            step4Fields.tempat_lahir_ibu,
            step4Fields.tanggal_lahir_ibu,
            step4Fields.agama_ibu,
            step4Fields.pendidikan_ibu,
            step4Fields.pekerjaan_ibu,
            step4Fields.penghasilan_ibu
        ];

        Object.values(step4Fields).forEach((input) => {
            if (!input) return;

            const eventType =
                input.tagName.toLowerCase() === 'select'
                    ? 'change'
                    : 'blur';

            input.addEventListener(eventType, () => {
                handleBlurFormatting(input);
                validateField(input);
            });

            if (input.tagName.toLowerCase() === 'input') {
                input.addEventListener('input', () => {
                    if (
                        input.id === 'nik_ayah' ||
                        input.id === 'nik_ibu'
                    ) {
                        const sanitized = sanitizeDigits(input.value);

                        if (sanitized !== input.value) {
                            input.value = sanitized;
                        }
                    }

                    // Batasi karakter nama seperti Step 1
                    if (
                        input.id === 'nama_ayah' ||
                        input.id === 'nama_ibu'
                    ) {
                        const sanitized = input.value.replace(/[^\p{L}\s'-.]/gu, '');

                        if (sanitized !== input.value) {
                            input.value = sanitized;
                        }
                    }
                });
            }
        });
    });
</script>

