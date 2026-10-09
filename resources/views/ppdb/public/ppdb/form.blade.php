@extends('layouts.ppdb-form')

    @section('title', 'Form Pendaftaran PPDB')

    @section('content')

    <style>
        .card.shadow {
            border: none;
            border-radius: 24px;
            overflow: hidden;
        }
        .card-header {
            border-bottom: none;            
        }
        
        .card-body {
            padding: 1.5rem 1.25rem;
        }
        .wizard-steps {
            display: flex;
            align-items: flex-start;
            width: 100%;
            flex-wrap: nowrap;
            gap: 0;
            margin-bottom: 1rem;
            padding: 1.5rem 1.25rem;
        }
        .wizard-step-item {
            display: flex;
            flex: 1 1 0;
            min-width: 0;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .wizard-indicator {
            font-size: 0.75rem;
            line-height: 1;
            display: inline-block;
            color: #5b636e;
        }

        .wizard-step-label {
            display: block;
            margin-top: 0.35rem;
            color: #5b636e;
            font-size: 0.75rem;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }
        .wizard-step-item[data-status="active"] .wizard-step-label {
            color: #5b636e;
            font-weight: 700;
        }
        
        .wizard-connector {
            height: 2px;
            flex: 1 1 0;
            min-width: 0;
            margin-top: 0.875rem;
            background: #d3d8df;
            opacity: 0.7;
        }        
        .tittle-left {
            width: 45%;
            padding: 55px 50px;
        }

        .tittle-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
        }
        
        .tittle-logo {
            width: 60px;            
        }

        .tittle-school {
            color: #0881c7;
            font-size: 14px;
            line-height: 1.2;
        }


        .tittle-school span {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 6px;
        }
    

        @media (max-width: 768px) {                        
            .tittle-left {
                width: 100%;
                padding: 36px 24px;
            }
            
            .tittle-logo {
                margin-bottom: 18px;
            }
            .tittle-school {
                font-size: 13px;
            }
            .tittle-school h2 {
                font-size: 24px;
            }
                
        }

        @media (max-width: 480px) {
            .tittle-left {
                padding: 28px 18px;
            }

            .tittle-school h2 {
                font-size: 22px;
            }

            .tittle-school {
                font-size: 12px;
            }
        }
        @media (max-width: 576px) {
            .card-body {
                padding: 1.25rem 0.75rem;
            }
            .wizard-steps {
                width: 100%;
            }
            .wizard-step-label {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }
            .wizard-indicator {
                width: 1.625rem;
                height: 1.625rem;
                flex-basis: 1.625rem;
            }
            .wizard-connector {
                display: none;
            }
        }
    </style>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header">
                
                <div class="tittle-header">
                    <img src="{{ asset('assets/images/logo-kolaan.png') }}" class="tittle-logo">
                    <div class="tittle-school">
                        <span>Form Pendaftaran</span><br>
                        Sekolah Dasar Negeri<br>Mekar Jaya
                    </div>
                </div>
            </div>            

            <div class="card-body">
                <div class="wizard-steps" role="list" aria-label="Tahapan pendaftaran PPDB">
                    <div class="wizard-step-item" data-wizard-step="1" data-status="active" role="listitem">
                        <span class="wizard-indicator bi bi-circle-fill" data-wizard-indicator data-status="active" aria-hidden="true"></span>
                        <span class="wizard-step-label" id="labelStep1">Data Pribadi Anak</span>
                    </div>
                    <span class="wizard-connector" data-wizard-connector="1" data-status="pending" aria-hidden="true"></span>
                    <div class="wizard-step-item" data-wizard-step="2" data-status="pending" role="listitem">
                        <span class="wizard-indicator bi bi-circle" data-wizard-indicator data-status="pending" aria-hidden="true"></span>
                        <span class="wizard-step-label" id="labelStep2">Alamat</span>
                    </div>
                    <span class="wizard-connector" data-wizard-connector="2" data-status="pending" aria-hidden="true"></span>
                    <div class="wizard-step-item" data-wizard-step="3" data-status="pending" role="listitem">
                        <span class="wizard-indicator bi bi-circle" data-wizard-indicator data-status="pending" aria-hidden="true"></span>
                        <span class="wizard-step-label" id="labelStep3">Asal Sekolah</span>
                    </div>
                    <span class="wizard-connector" data-wizard-connector="3" data-status="pending" aria-hidden="true"></span>
                    <div class="wizard-step-item" data-wizard-step="4" data-status="pending" role="listitem">
                        <span class="wizard-indicator bi bi-circle" data-wizard-indicator data-status="pending" aria-hidden="true"></span>
                        <span class="wizard-step-label" id="labelStep4">Orang Tua</span>
                    </div>
                    <span class="wizard-connector" data-wizard-connector="4" data-status="pending" aria-hidden="true"></span>
                    <div class="wizard-step-item" data-wizard-step="5" data-status="pending" role="listitem">
                        <span class="wizard-indicator bi bi-circle" data-wizard-indicator data-status="pending" aria-hidden="true"></span>
                        <span class="wizard-step-label" id="labelStep5">Kontak</span>
                    </div>
                    <span class="wizard-connector" data-wizard-connector="5" data-status="pending" aria-hidden="true"></span>
                    <div class="wizard-step-item" data-wizard-step="6" data-status="pending" role="listitem">
                        <span class="wizard-indicator bi bi-circle" data-wizard-indicator data-status="pending" aria-hidden="true"></span>
                        <span class="wizard-step-label" id="labelStep6">Data Periodik & Dokumen</span>
                    </div>
                </div>

                <div id="validationSummary" class="alert alert-danger d-none mb-4"></div>

                <form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data" id="ppdbForm" novalidate>
                    @csrf

                    @include('ppdb.public.ppdb.steps.step1')
                    @include('ppdb.public.ppdb.steps.step2')
                    @include('ppdb.public.ppdb.steps.step3')
                    @include('ppdb.public.ppdb.steps.step4')
                    @include('ppdb.public.ppdb.steps.step5')
                    @include('ppdb.public.ppdb.steps.step6')
                </form>
            </div>
        </div>
    </div>

    @endsection

    @push('scripts')
    <script>
        const wizardSteps = [
            'Data Pribadi',
            'Data Alamat',
            'Asal Sekolah',
            'Data Orang Tua/Wali',
            'Kontak',
            'Data Periodik & Dokumen',
        ].map((name, index) => ({
            name,
            labelId: `labelStep${index + 1}`,
            panelId: `step${index + 1}`,
        }));

        function getWizardStepStatuses(activeStep, errorsByStep) {
            return Object.fromEntries(wizardSteps.map(({ name }, index) => {
                if (index + 1 === Number(activeStep)) {
                    return [name, 'active'];
                }

                const errors = errorsByStep?.[name];
                return [name, Array.isArray(errors) && errors.length === 0 ? 'complete' : 'pending'];
            }));
        }

        function updateWizardStepStatuses(activeStep) {
            const statuses = getWizardStepStatuses(activeStep, window.__ppdbValidationGroupErrors || {});

            wizardSteps.forEach(({ name, labelId }, index) => {
                const label = document.getElementById(labelId);
                if (!label) return;

                const status = statuses[name];
                const stepItem = label.closest('.wizard-step-item');
                const indicator = stepItem?.querySelector('[data-wizard-indicator]');

                if (stepItem) stepItem.dataset.status = status;

                if (indicator) {
                    indicator.dataset.status = status;

                    indicator.classList.remove(
                        'bi-check-circle-fill',
                        'bi-circle-fill',
                        'bi-circle'
                    );

                    const iconClass = {
                        complete: 'bi-check-circle-fill',
                        active: 'bi-circle-fill',
                        pending: 'bi-circle',
                    };

                    indicator.classList.add(iconClass[status]);
                }

                if (status === 'active') {
                    label.setAttribute('aria-current', 'step');
                } else {
                    label.removeAttribute('aria-current');
                }

                const connector = document.querySelector(`[data-wizard-connector="${index + 1}"]`);
                if (connector) {
                    connector.dataset.status = status === 'complete' ? 'complete' : 'pending';
                }
            });
        }

        function showStep(step) {
            document.querySelectorAll('.wizard-step').forEach(item => {
                item.classList.add('d-none');
            });

            const currentStep = document.getElementById(wizardSteps[Number(step) - 1]?.panelId);
            if (currentStep) {
                currentStep.classList.remove('d-none');
            }

            window.__ppdbActiveStep = step;
            updateWizardStepStatuses(step);
        }

        function showFirstInvalidStep() {
            const errorsByStep = window.__ppdbValidationGroupErrors || {};
            const firstInvalidStep = wizardSteps.find(({ name }) => errorsByStep[name]?.length);

            if (firstInvalidStep) {
                showStep(Number(firstInvalidStep.panelId.replace('step', '')));
            }
        }

        function buildValidationSummary(errorsByStep) {
            const container = document.getElementById('validationSummary');
            if (!container) return;

            const entries = Object.entries(errorsByStep)
                .filter(([, items]) => Array.isArray(items) && items.length)
                .map(([title, items]) => ({
                    title,
                    step: wizardSteps.findIndex(({ name }) => name === title) + 1 || 1,
                    items,
                }));

            if (!entries.length) {
                container.classList.add('d-none');
                container.innerHTML = '';
                return;
            }

            const innerHtml = [
                '<div class="d-flex justify-content-between align-items-start mb-3">',
                '    <div class="fw-bold">Pendaftaran belum dapat dikirim. Silakan lengkapi data berikut:</div>',
                '    <button type="button" class="btn-close ms-3" aria-label="Tutup" id="closeValidationSummary"></button>',
                '</div>',
                ...entries.map(({ title, step, items }) => {
                    const detailHtml = items.map((message) => `
                        <div class="mb-1">${message}</div>
                    `).join('');

                    return `
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                <div class="fw-semibold text-danger">${title}</div>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-fix-step="${step}">Perbaiki</button>
                            </div>
                            <div class="ps-2 text-danger">${detailHtml}</div>
                        </div>
                    `;
                })
            ].join('');

            container.innerHTML = innerHtml;
            container.classList.remove('d-none');

            const closeButton = document.getElementById('closeValidationSummary');

            if (closeButton) {
                closeButton.addEventListener('click', function () {
                    container.classList.add('d-none');
                });
            }

            container.querySelectorAll('[data-fix-step]').forEach((button) => {
                button.addEventListener('click', function () {
                    const targetStep = Number(this.dataset.fixStep || 1);
                    showStep(targetStep);
                    const targetStepEl = document.getElementById('step' + targetStep);
                    if (!targetStepEl) return;

                    const firstField = targetStepEl.querySelector('input, select, textarea') || null;
                    if (firstField) {
                        firstField.focus();
                    }
                });
            });
        }

        function getFieldErrorContainer(input) {
            if (!input) return null;

            if (input.type === 'checkbox') {
                return input.closest('.form-check')?.querySelector('.invalid-feedback');
            }

                return input.closest('.mb-3, .col-md-2, .col-md-3, .col-md-4, .col-md-6, .col-md-12, .col-12')?.querySelector('.invalid-feedback');
        }

        function clearFieldError(input) {
            if (!input) return;

            input.classList.remove('is-invalid');

            const feedback = getFieldErrorContainer(input);
            if (feedback) {
                feedback.textContent = '';
            }
        }

        function setFieldError(input, message) {
            if (!input) return;

            input.classList.add('is-invalid');

            const feedback = getFieldErrorContainer(input);
            if (feedback) {
                feedback.textContent = message;
            }
        }

        function validateMandatoryField(input, rules) {
            const value = (input && input.type === 'checkbox') ? input.checked : (input ? input.value : '');
            const normalized = typeof value === 'string' ? value.trim() : value;

            if (rules.required && !normalized) {
                return `${rules.label} wajib diisi.`;
            }

            if (rules.pattern && normalized && !rules.pattern.test(normalized)) {
                return rules.message || `${rules.label} tidak valid.`;
            }

            if (rules.custom) {
                const customMessage = rules.custom(normalized, input);
                if (customMessage) {
                    return customMessage;
                }
            }

            return null;
        }

        function validateWholeForm() {
            const errorsByStep = {
                'Data Pribadi': [],
                'Data Alamat': [],
                'Asal Sekolah': [],
                'Data Orang Tua/Wali': [],
                'Kontak': [],
                'Data Periodik & Dokumen': [],
            };
            const getField = (id) => document.getElementById(id);
            const pushError = (group, message) => {
                if (message) errorsByStep[group].push(message);
            };

            document.querySelectorAll('.is-invalid').forEach((field) => field.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach((feedback) => {
                feedback.textContent = '';
            });

            const checkField = (id, group, rules) => {
                const input = getField(id);
                const message = validateMandatoryField(input, rules);
                pushError(group, message);
                if (message) setFieldError(input, message);
            };

            const checkFile = (id, label, required, extensions) => {
                const input = getField(id);
                const message = validateMandatoryField(input, {
                    required,
                    label,
                    custom: (value, field) => {
                        if (!field?.files?.length) return null;
                        const file = field.files[0];
                        if (file.size > 2 * 1024 * 1024) return `${label} maksimal 2 MB.`;
                        const extension = file.name.split('.').pop().toLowerCase();
                        return extensions.includes(extension)
                            ? null
                            : `${label} harus berformat ${extensions.join(', ').toUpperCase()}.`;
                    },
                });
                pushError('Data Periodik & Dokumen', message);
                if (message) setFieldError(input, message);
            };

            checkField('nama', 'Data Pribadi', { required: true, label: 'Nama lengkap', pattern: /^[\p{L}\s'.-]+$/u, message: 'Nama hanya boleh berisi huruf.' });
            checkField('jenis_kelamin', 'Data Pribadi', { required: true, label: 'Jenis kelamin' });
            checkField('nik', 'Data Pribadi', { required: true, label: 'NIK', pattern: /^\d{16}$/, message: 'NIK harus terdiri dari 16 digit angka.' });
            checkField('nomor_kk', 'Data Pribadi', { required: true, label: 'Nomor KK', pattern: /^\d{16}$/, message: 'Nomor KK harus terdiri dari 16 digit angka.' });
            checkField('tempat_lahir', 'Data Pribadi', { required: true, label: 'Tempat lahir' });
            checkField('tanggal_lahir', 'Data Pribadi', {
                required: true,
                label: 'Tanggal lahir',
                custom: (value) => value && value > new Date().toLocaleDateString('en-CA')
                    ? 'Tanggal lahir tidak boleh melewati hari ini.'
                    : null,
            });
            checkField('nomor_akta', 'Data Pribadi', { required: false, label: 'Nomor akta', pattern: /^.{1,30}$/, message: 'Nomor akta maksimal 30 karakter.' });
            checkField('agama', 'Data Pribadi', { required: true, label: 'Agama' });
            checkField('kewarganegaraan', 'Data Pribadi', { required: true, label: 'Kewarganegaraan' });

            checkField('provinsi', 'Data Alamat', { required: true, label: 'Provinsi' });
            checkField('kabupaten', 'Data Alamat', { required: true, label: 'Kabupaten/Kota' });
            checkField('kecamatan', 'Data Alamat', { required: true, label: 'Kecamatan' });
            checkField('desa', 'Data Alamat', { required: true, label: 'Kelurahan/Desa' });
            checkField('rt', 'Data Alamat', { required: true, label: 'RT', pattern: /^\d{1,3}$/, message: 'RT harus berupa 1 sampai 3 digit angka.' });
            checkField('rw', 'Data Alamat', { required: true, label: 'RW', pattern: /^\d{1,3}$/, message: 'RW harus berupa 1 sampai 3 digit angka.' });
            checkField('alamat', 'Data Alamat', { required: true, label: 'Alamat lengkap' });

            const schoolType = getField('jenis_pendidikan_asal')?.value || '';
            checkField('jenis_pendidikan_asal', 'Asal Sekolah', { required: true, label: 'Jenis pendidikan asal' });
            if (schoolType && schoolType !== 'Belum Pernah Sekolah') {
                checkField('nama_sekolah_asal', 'Asal Sekolah', { required: true, label: 'Nama sekolah asal' });
                checkField('nisn_sekolah_asal', 'Asal Sekolah', { required: true, label: 'NISN sekolah asal', pattern: /^\d{10}$/, message: 'NISN harus terdiri dari 10 digit angka.' });
            }

            ['ayah', 'ibu'].forEach((parent) => {
                const parentLabel = parent === 'ayah' ? 'Ayah' : 'Ibu';
                checkField(`nama_${parent}`, 'Data Orang Tua/Wali', { required: true, label: `Nama ${parentLabel}`, pattern: /^[\p{L}\s'.-]+$/u, message: `Nama ${parentLabel} hanya boleh berisi huruf.` });
                const nik = getField(`nik_${parent}`);
                if (nik?.value.trim()) {
                    checkField(`nik_${parent}`, 'Data Orang Tua/Wali', { required: false, label: `NIK ${parentLabel}`, pattern: /^\d{16}$/, message: `NIK ${parentLabel} harus terdiri dari 16 digit angka.` });
                }
                checkField(`tempat_lahir_${parent}`, 'Data Orang Tua/Wali', { required: true, label: `Tempat lahir ${parentLabel}` });
                checkField(`tanggal_lahir_${parent}`, 'Data Orang Tua/Wali', { required: true, label: `Tanggal lahir ${parentLabel}` });
                checkField(`agama_${parent}`, 'Data Orang Tua/Wali', { required: true, label: `Agama ${parentLabel}` });
                checkField(`pendidikan_${parent}`, 'Data Orang Tua/Wali', { required: true, label: `Pendidikan ${parentLabel}` });
                checkField(`pekerjaan_${parent}`, 'Data Orang Tua/Wali', { required: true, label: `Pekerjaan ${parentLabel}` });
            });

            checkField('nomor_hp', 'Kontak', { required: true, label: 'Nomor HP/WhatsApp', pattern: /^08\d{8,11}$/, message: 'Nomor HP/WhatsApp harus diawali 08 dan terdiri dari 10-13 digit.' });
            const email = getField('email');
            checkField('email', 'Kontak', { required: true, label: 'Email', pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, message: 'Format email tidak valid.' });
            checkField('email_confirmation', 'Kontak', {
                required: true,
                label: 'Konfirmasi email',
                custom: (value) => value && email?.value.trim() !== value.trim()
                    ? 'Konfirmasi email tidak sama dengan email.'
                    : null,
            });

            [
                ['anak_ke', 'Anak ke', 1, 20, true],
                ['jumlah_saudara', 'Jumlah saudara', 0, 20, true],
                ['tinggi_badan', 'Tinggi badan', 30, 250, false],
                ['berat_badan', 'Berat badan', 5, 150, false],
            ].forEach(([id, label, min, max, integer]) => {
                checkField(id, 'Data Periodik & Dokumen', {
                    required: true,
                    label,
                    custom: (value) => {
                        if (!value) return null;
                        const number = Number(value);
                        if (!Number.isFinite(number) || number < min || number > max || (integer && !Number.isInteger(number)) || (!integer && !Number.isInteger(number * 10))) {
                            return `${label} harus ${integer ? 'berupa bilangan bulat ' : ''}antara ${min} sampai ${max}${integer ? '' : ' dengan maksimal 1 angka desimal'}.`;
                        }
                        return null;
                    },
                });
            });

            checkFile('kartu_keluarga', 'Kartu keluarga', true, ['pdf', 'jpg', 'jpeg']);
            checkFile('ktp', 'KTP orang tua', true, ['pdf', 'jpg', 'jpeg']);
            checkFile('ijazah', 'Ijazah terakhir', false, ['pdf', 'jpg', 'jpeg']);
            checkFile('akta_lahir', 'Akta kelahiran', false, ['pdf', 'jpg', 'jpeg']);
            checkFile('kartu_kip', 'Kartu KIP', false, ['pdf', 'jpg', 'jpeg']);
            checkFile('foto', 'Pas foto', true, ['jpg', 'jpeg', 'png']);

            checkField('declaration', 'Data Periodik & Dokumen', {
                required: true,
                label: 'Pernyataan',
                custom: (value, input) => input?.checked ? null : 'Pernyataan wajib disetujui.',
            });

            window.__ppdbValidationGroupErrors = errorsByStep;
            buildValidationSummary(errorsByStep);
            updateWizardStepStatuses(window.__ppdbActiveStep || 1);
            return Object.values(errorsByStep).every((items) => !items.length);
        }

        document.addEventListener('DOMContentLoaded', () => {
            showStep(1);

            const form = document.getElementById('ppdbForm');
            const finalSubmitButton = document.getElementById('submitPpdbButton') || document.querySelector('.btn-confirm');

            if (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.finalSubmitReady === '1') {
                        form.dataset.finalSubmitReady = '0';
                        return;
                    }

                    const isValid = validateWholeForm();
                    if (!isValid) {
                        event.preventDefault();
                        showFirstInvalidStep();
                        return false;
                    }

                    if (!window.confirm('Apakah data pendaftaran yang Anda masukkan sudah benar?')) {
                        event.preventDefault();
                        return false;
                    }
                });
            }

            if (finalSubmitButton) {
                finalSubmitButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();

                    if (!validateWholeForm()) {
                        showFirstInvalidStep();
                        return false;
                    }

                    if (!window.confirm('Apakah data pendaftaran yang Anda masukkan sudah benar?')) {
                        return false;
                    }

                    form.dataset.finalSubmitReady = '1';
                    form.submit();
                    return false;
                });
            }
        });
    </script>
    @endpush
