<div id="step3" class="wizard-step d-none">
    <h4 class="mb-4">Data Orang Tua</h4>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            
            <h5 class="card-title">Data Ayah</h5>

            <div class="mb-3">
                <label for="nama_ayah" class="form-label">Nama Lengkap Ayah <span class="text-danger">*</span></label>
                <input id="nama_ayah" name="nama_ayah" type="text" class="form-control" placeholder="Masukkan nama lengkap sesuai KTP/akta kelahiran" maxlength="100" autocomplete="name" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="nik_ayah" class="form-label">NIK Ayah <span class="text-danger">*</span></label>
                <input id="nik_ayah" name="nik_ayah" type="text" inputmode="numeric" maxlength="16" class="form-control" autocomplete="off" placeholder="Masukkan 16 digit NIK" required>
                <small class="text-muted">16 digit sesuai KTP/KK.</small>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tempat_lahir_ayah" class="form-label">Tempat Lahir Ayah <span class="text-danger">*</span></label>
                <input id="tempat_lahir_ayah" name="tempat_lahir_ayah" type="text" class="form-control" placeholder="Masukkan kota/kabupaten tempat lahir" autocomplete="address-level2" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir_ayah" class="form-label">Tanggal Lahir Ayah <span class="text-danger">*</span></label>
                <input id="tanggal_lahir_ayah" name="tanggal_lahir_ayah" type="date" class="form-control" autocomplete="bday" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="pendidikan_ayah" class="form-label">Pendidikan Ayah <span class="text-danger">*</span></label>
                <select id="pendidikan_ayah" name="pendidikan_ayah" class="form-select" required>
                    <option value="" selected disabled>Pilih Pendidikan Terakhir</option>
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
                    <option value="" selected disabled>Pilih Pekerjaan</option>
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
                <label for="penghasilan_ayah" class="form-label">Penghasilan Ayah <span class="text-danger">*</span></label>
                <select id="penghasilan_ayah" name="penghasilan_ayah" class="form-select" required>
                    <option value="" selected disabled>Pilih Rentang Penghasilan</option>
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
                <input id="nama_ibu" name="nama_ibu" type="text" class="form-control" placeholder="Masukkan nama lengkap sesuai KTP/akta kelahiran" maxlength="100" autocomplete="name" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="nik_ibu" class="form-label">NIK Ibu <span class="text-danger">*</span></label>
                <input id="nik_ibu" name="nik_ibu" type="text" inputmode="numeric" maxlength="16" class="form-control" autocomplete="off" placeholder="Masukkan 16 digit NIK" required>
                <small class="text-muted">16 digit sesuai KTP/KK.</small>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tempat_lahir_ibu" class="form-label">Tempat Lahir Ibu <span class="text-danger">*</span></label>
                <input id="tempat_lahir_ibu" name="tempat_lahir_ibu" type="text" class="form-control" placeholder="Masukkan kota/kabupaten tempat lahir" autocomplete="address-level2" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir_ibu" class="form-label">Tanggal Lahir Ibu <span class="text-danger">*</span></label>
                <input id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" type="date" class="form-control" autocomplete="bday" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="pendidikan_ibu" class="form-label">Pendidikan Ibu <span class="text-danger">*</span></label>
                <select id="pendidikan_ibu" name="pendidikan_ibu" class="form-select" required>
                    <option value="" selected disabled>Pilih Pendidikan Terakhir</option>
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
                    <option value="" selected disabled>Pilih Pekerjaan</option>
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
                <label for="penghasilan_ibu" class="form-label">Penghasilan Ibu <span class="text-danger">*</span></label>
                <select id="penghasilan_ibu" name="penghasilan_ibu" class="form-select" required>
                    <option value="" selected disabled>Pilih Rentang Penghasilan</option>
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
        <button type="button" class="btn btn-outline-secondary rounded-3 btn-sm" onclick="showStep(2)">
            <i class="bi bi-arrow-left me-1"></i>
            Sebelumnya
        </button>

        <button type="button" id="step3-next" class="btn btn-primary btn-sm">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const step3Fields = {
                nama_ayah: document.getElementById('nama_ayah'),
                nik_ayah: document.getElementById('nik_ayah'),
                tempat_lahir_ayah: document.getElementById('tempat_lahir_ayah'),
                tanggal_lahir_ayah: document.getElementById('tanggal_lahir_ayah'),
                pendidikan_ayah: document.getElementById('pendidikan_ayah'),
                pekerjaan_ayah: document.getElementById('pekerjaan_ayah'),
                penghasilan_ayah: document.getElementById('penghasilan_ayah'),
                nama_ibu: document.getElementById('nama_ibu'),
                nik_ibu: document.getElementById('nik_ibu'),
                tempat_lahir_ibu: document.getElementById('tempat_lahir_ibu'),
                tanggal_lahir_ibu: document.getElementById('tanggal_lahir_ibu'),
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

            const isValidName = (value) => /^[\p{L}\s'\.-]+$/u.test(value);

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
                    case 'nama_ayah':
                    case 'nama_ibu':
                        if (!value) {
                            setInvalid(input, 'Nama lengkap wajib diisi.');
                            return false;
                        }
                        if (!isValidName(value)) {
                            setInvalid(input, 'Nama hanya boleh berisi huruf.');
                            return false;
                        }
                        return true;
                    case 'nik_ayah':
                    case 'nik_ibu':
                        if (!value) {
                            setInvalid(input, 'NIK wajib diisi.');
                            return false;
                        }
                        if (!/^\d{16}$/.test(value)) {
                            setInvalid(input, 'NIK harus terdiri dari 16 digit angka.');
                            return false;
                        }
                        return true;
                    case 'tempat_lahir_ayah':
                    case 'tempat_lahir_ibu':
                        if (!value) {
                            setInvalid(input, 'Tempat lahir wajib diisi.');
                            return false;
                        }
                        if (!isValidName(value)) {
                            setInvalid(input, 'Tempat lahir hanya boleh berisi huruf.');
                            return false;
                        }
                        return true;
                    case 'tanggal_lahir_ayah':
                    case 'tanggal_lahir_ibu':
                        if (!value) {
                            setInvalid(input, 'Tanggal lahir wajib diisi.');
                            return false;
                        }
                        if (value > new Date().toISOString().slice(0, 10)) {
                            setInvalid(input, 'Tanggal lahir tidak boleh di masa depan.');
                            return false;
                        }
                        return true;
                    case 'pendidikan_ayah':
                    case 'pendidikan_ibu':
                        if (!value) {
                            setInvalid(input, 'Silakan pilih pendidikan.');
                            return false;
                        }
                        return true;
                    case 'pekerjaan_ayah':
                    case 'pekerjaan_ibu':
                        if (!value) {
                            setInvalid(input, 'Silakan pilih pekerjaan.');
                            return false;
                        }
                        return true;
                    case 'penghasilan_ayah':
                    case 'penghasilan_ibu':
                        if (!value) {
                            setInvalid(input, 'Silakan pilih penghasilan.');
                            return false;
                        }
                        return true;
                    default:
                        return true;
                }
            };

            const handleBlurFormatting = (input) => {
                if (input.name === 'nama_ayah' || input.name === 'nama_ibu' || input.name === 'tempat_lahir_ayah' || input.name === 'tempat_lahir_ibu') {
                    input.value = formatTitleCase(input.value);
                }
                if (input.name === 'nik_ayah' || input.name === 'nik_ibu') {
                    input.value = sanitizeDigits(input.value).slice(0, 16);
                }
                input.value = input.value.trim().replace(/\s+/g, ' ');
            };

            const fieldOrder = [
                step3Fields.nama_ayah,
                step3Fields.nik_ayah,
                step3Fields.tempat_lahir_ayah,
                step3Fields.tanggal_lahir_ayah,
                step3Fields.pendidikan_ayah,
                step3Fields.pekerjaan_ayah,
                step3Fields.penghasilan_ayah,
                step3Fields.nama_ibu,
                step3Fields.nik_ibu,
                step3Fields.tempat_lahir_ibu,
                step3Fields.tanggal_lahir_ibu,
                step3Fields.pendidikan_ibu,
                step3Fields.pekerjaan_ibu,
                step3Fields.penghasilan_ibu
            ];

            Object.values(step3Fields).forEach((input) => {
                if (!input) return;

                const eventType = input.tagName.toLowerCase() === 'select' ? 'change' : 'blur';
                input.addEventListener(eventType, () => {
                    handleBlurFormatting(input);
                    validateField(input);
                });

                input.addEventListener('input', () => {
                    if (input.name === 'nik_ayah' || input.name === 'nik_ibu') {
                        const sanitized = sanitizeDigits(input.value);
                        if (sanitized !== input.value) {
                            input.value = sanitized;
                        }
                    }
                    if (input.name === 'nama_ayah' || input.name === 'nama_ibu' || input.name === 'tempat_lahir_ayah' || input.name === 'tempat_lahir_ibu') {
                        const sanitized = input.value.replace(/[^\p{L}\s'\.-]/gu, '');
                        if (sanitized !== input.value) {
                            input.value = sanitized;
                        }
                        input.value = input.value.replace(/\s{2,}/g, ' ');
                    }
                });
            });

            document.getElementById('step3-next').addEventListener('click', () => {
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
                    showStep(4);
                }
            });

            const today = new Date().toISOString().slice(0, 10);
            ['tanggal_lahir_ayah', 'tanggal_lahir_ibu'].forEach((id) => {
                const el = document.getElementById(id);
                if (el) {
                    el.setAttribute('max', today);
                }
            });
        });
    </script>
</div>