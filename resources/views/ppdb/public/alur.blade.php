@extends('layouts.ppdb-form')

@section('title', 'Cara Pendaftaran')

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
    .flow-wizard {
        position: relative;
        width: 100%;
        padding: 1.5rem 1.25rem;
        padding-top: 1.5rem;
    }

    .flow-wizard-step {
        position: static;
    }

    .flow-wizard-indicator {
        position: absolute;
        top: 0;
        left: var(--wizard-step-center);
        z-index: 2;

        display: grid;
        width: 0.75rem;
        height: 0.75rem;
        place-items: center;

        transform: translateX(-50%);
        background: #fff;
        font-size: 0.75rem;
        line-height: 1;
        color: #5b636e;
    }

    .flow-wizard-connector {
        position: absolute;
        top: 0.375rem;
        left: var(--wizard-connector-start);
        width: var(--wizard-connector-width);
        height: 2px;

        z-index: 1;

        background: #d3d8df;
        opacity: 0.7;
    }


    .flow-wizard-step-content[hidden] {
        display: none;
    }

    .flow-wizard-panel {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .flow-wizard-panel > div:last-child {
        width: 100%;
        text-align: center;
    }

    .flow-wizard-image,
    .flow-wizard-video {
        display: grid;
        min-height: 12rem;
        place-items: center;
        border: 1px dashed #adb5bd;
        border-radius: 0.5rem;
        background: #f8f9fa;
        color: #6c757d;
        text-align: center;
    }
    .flow-wizard-image img {
        display: block;
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 0.5rem;
    }
    .flow-wizard-video video {
        display: block;
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 0.5rem;
    }

    .flow-wizard-navigation {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }
    .flow-wizard-preparation {
        width: 100%;
        max-width: 850px;
        margin: 0 auto;
    }

    .preparation-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .preparation-item {
        padding: 1rem 1.25rem;
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        text-align: left;
    }

    .preparation-item h5 {
        margin: 0 0 0.4rem;
        font-size: 1rem;
        font-weight: 600;
        color: #495057;
        text-align: left;
    }

    .preparation-item p {
        margin: 0;
        color: #6c757d;
        line-height: 1.6;
        font-size: 0.95rem;
        text-align: left;
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
        
    .ppdb-warning-modal .modal-content {
        border-radius: 24px;
        box-shadow: 0 18px 50px rgba(13, 110, 253, .18);
    }
    .ppdb-warning-modal .modal-header {
        border-bottom: 1px solid #e9f0f8;
    }
    .ppdb-warning-modal .modal-title {
        color: #17416e;
    }
    .ppdb-warning-modal .ppdb-warning-list {
        padding-left: 1.25rem;
    }
    .ppdb-warning-modal .ppdb-warning-list li {
        margin-bottom: .65rem;
    }
    .ppdb-warning-modal .ppdb-email-note {
        background: #f0f7ff;
        border-left: 4px solid #0d6efd;
        color: #17416e;
    }
    .ppdb-warning-modal .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
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

    @media (max-width: 575.98px) {
        .card-body {
            padding: 1.25rem 0.75rem;
        }

        .flow-wizard {
            padding-top: 3rem;
        }

        .flow-wizard-indicator {
            width: 2.125rem;
            height: 2.125rem;
        }

        .flow-wizard-connector {
            display: none;
        }

        .flow-wizard-panel {
            grid-template-columns: minmax(0, 1fr);
        }

        .flow-wizard-image {
            min-height: 9rem;
        }
    }
</style>

<div class="container py-5">    

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow">
                <div class="card-header">                
                    <div class="tittle-header">
                        <img src="{{ asset('assets/images/logo-kolaan.png') }}" class="tittle-logo">
                        <div class="tittle-school">
                            <span>Cara Pendaftaran</span><br>
                            Sekolah Dasar Negeri<br>Mekar Jaya
                        </div>
                    </div>
                </div>      
                <div class="card-body">
                    <div class="flow-wizard" data-ppdb-wizard aria-label="Wizard alur PPDB">
                        <section class="flow-wizard-step" data-ppdb-wizard-step="1" data-status="pending">
                            <span class="flow-wizard-indicator bi bi-circle" data-ppdb-step-indicator aria-label="Langkah 2"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content hidden>
                                <div class="flow-wizard-panel">   
                                                                                                                                 
                                    <div class="flow-wizard-preparation">
                                        <h2 class="h4 pt-4" data-ppdb-step-title>
                                            Data yang Perlu Disiapkan
                                        </h2>
                                        <div class="preparation-list">
                                            <div class="preparation-item">
                                                <h5>Data Pribadi Anak</h5>
                                                <p>
                                                    <span class="text-danger">Wajib:</span>
                                                    Nama, Jenis Kelamin, NIK, Nomor KK,
                                                    Tempat dan Tanggal Lahir, Agama,
                                                    Kewarganegaraan.
                                                    <br>
                                                    <span>Opsional:</span>
                                                    Nomor Akta.
                                                </p>
                                            </div>
                                            <div class="preparation-item">
                                                <h5>Data Alamat</h5>
                                                <p>
                                                    <span class="text-danger">Wajib:</span>
                                                    Provinsi, Kabupaten, Kecamatan,
                                                    Kelurahan, RT, RW, Alamat Lengkap.
                                                </p>
                                            </div>
                                            <div class="preparation-item">
                                                <h5>Data Asal Sekolah</h5>
                                                <p>
                                                    <span class="text-danger">Wajib:</span>
                                                    Jenis Pendidikan Asal, Nama Sekolah Asal,
                                                    NISN Asal.
                                                </p>
                                            </div>
                                            <div class="preparation-item">
                                                <h5>Data Orang Tua/Wali</h5>
                                                <p>
                                                    <span class="text-danger">Wajib:</span>
                                                    Nama, Tempat dan Tanggal Lahir, Agama,
                                                    Pendidikan, Pekerjaan.
                                                    <br>
                                                    <span>Opsional:</span>
                                                    NIK, Penghasilan.
                                                </p>
                                            </div>
                                            <div class="preparation-item">
                                                <h5>Data Kontak</h5>
                                                <p>
                                                    <span class="text-danger">Wajib:</span>
                                                    Nomor HP/WhatsApp, Email.
                                                </p>
                                            </div>
                                            <div class="preparation-item">
                                                <h5>Data Periodik & Dokumen</h5>
                                                <p>
                                                    <span class="text-danger">Wajib:</span>
                                                    Anak Ke, Jumlah Saudara, Tinggi Badan,
                                                    Berat Badan, File Kartu Keluarga,
                                                    File KTP Orang Tua, Pas Foto.
                                                    <br>
                                                    <span>Opsional:</span>
                                                    Ijazah, Akta, KIP.
                                                </p>
                                            </div>
                                        </div> 
                                    </div>                                
                                </div>
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <a href="{{ route('ppdb.informasi') }}" class="btn btn-outline-secondary
                                    ">Kembali</a>                                    
                                    <button type="button" class="btn btn-primary" data-ppdb-wizard-next>Selanjutnya</button>
                                </div>
                            </div>
                        </section>
                        
                        <span class="flow-wizard-connector" data-ppdb-wizard-connector data-status="pending" aria-hidden="true"></span>
                        <section class="flow-wizard-step" data-ppdb-wizard-step="2" data-status="active" aria-current="step">
                            <span class="flow-wizard-indicator bi bi-check-circle-fill" data-ppdb-step-indicator aria-label="Langkah 1"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content>
                                <div class="flow-wizard-panel">
                                    <div class="flow-wizard-image" data-ppdb-image-placeholder>
                                        <img src="{{ asset('assets/images/alur/Screenshot (2836).png') }}">
                                    </div>
                                    <div>
                                        <h2 class="h4" data-ppdb-step-title>Isi Formulir Pendaftaran</h2>
                                        <p class="text-muted" data-ppdb-step-description>Lengkapi data calon peserta didik sesuai dengan dokumen resmi yang dimiliki.</p>
                                    </div>
                                </div>
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <button type="button" class="btn btn-outline-secondary" data-ppdb-wizard-previous>Kembali</button>
                                    <button type="button" class="btn btn-primary" data-ppdb-wizard-next>Selanjutnya</button>
                                </div>
                            </div>
                        </section>
                        <span class="flow-wizard-connector" data-ppdb-wizard-connector data-status="pending" aria-hidden="true"></span>
                        <section class="flow-wizard-step" data-ppdb-wizard-step="3" data-status="pending">
                            <span class="flow-wizard-indicator bi bi-circle" data-ppdb-step-indicator aria-label="Langkah 5"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content hidden>
                                <div class="flow-wizard-panel">
                                    <div class="flow-wizard-image" data-ppdb-image-placeholder>
                                        <img src="{{ asset('assets/images/alur/Screenshot (2829).png') }}">
                                    </div>
                                    <div>
                                        <h2 class="h4" data-ppdb-step-title>Perhatikan Tanda *</h2>
                                        <p class="text-muted" data-ppdb-step-description>Kolom bertanda * wajib diisi. Kolom tanpa tanda * bersifat opsional.</p>
                                    </div>
                                </div>
                                
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <button type="button" class="btn btn-outline-secondary" data-ppdb-wizard-previous>Kembali</button>
                                    <button type="button" class="btn btn-primary" data-ppdb-wizard-next>Selanjutnya</button>
                                </div>
                            </div>
                        </section>
                        
                        <span class="flow-wizard-connector" data-ppdb-wizard-connector data-status="pending" aria-hidden="true"></span>
                        <section class="flow-wizard-step" data-ppdb-wizard-step="4" data-status="pending">
                            <span class="flow-wizard-indicator bi bi-circle" data-ppdb-step-indicator aria-label="Langkah 2"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content hidden>
                                <div class="flow-wizard-panel">
                                    <div class="flow-wizard-image" data-ppdb-image-placeholder>
                                        <img src="{{ asset('assets/images/alur/Screenshot (2826).png') }}">
                                    </div>
                                    <div>
                                        <h2 class="h4" data-ppdb-step-title>Pastikan Kontak Aktif</h2>
                                        <p class="text-muted" data-ppdb-step-description>Pastikan nomor HP/WhatsApp Anda aktif dan alamat email dapat diakses. Informasi dan pemberitahuan terkait proses pendaftaran akan dikirim melalui email tersebut.</p>
                                    </div>
                                </div>
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <button type="button" class="btn btn-outline-secondary" data-ppdb-wizard-previous>Kembali</button>
                                    <button type="button" class="btn btn-primary" data-ppdb-wizard-next>Selanjutnya</button>
                                </div>
                            </div>
                        </section>
                        <span class="flow-wizard-connector" data-ppdb-wizard-connector data-status="pending" aria-hidden="true"></span>

                        <section class="flow-wizard-step" data-ppdb-wizard-step="5" data-status="pending">
                            <span class="flow-wizard-indicator bi bi-circle" data-ppdb-step-indicator aria-label="Langkah 3"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content hidden>
                                <div class="flow-wizard-panel">
                                    <div class="flow-wizard-image" data-ppdb-image-placeholder>
                                        <img src="{{ asset('assets/images/alur/Screenshot (2828).png') }}">
                                    </div>
                                    <div>
                                        <h2 class="h4" data-ppdb-step-title>Perhatikan Ukuran Berkas</h2>
                                        <p class="text-muted" data-ppdb-step-description>Setiap berkas yang diunggah harus berukuran di bawah 2 MB. Jika lebih besar, kompres berkas terlebih dahulu.</p>
                                    </div>
                                </div>
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <button type="button" class="btn btn-outline-secondary" data-ppdb-wizard-previous>Kembali</button>
                                    <button type="button" class="btn btn-primary" data-ppdb-wizard-next>Selanjutnya</button>
                                </div>
                            </div>
                        </section>
                        <span class="flow-wizard-connector" data-ppdb-wizard-connector data-status="pending" aria-hidden="true"></span>

                        <section class="flow-wizard-step" data-ppdb-wizard-step="6" data-status="pending">
                            <span class="flow-wizard-indicator bi bi-circle" data-ppdb-step-indicator aria-label="Langkah 4"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content hidden>
                                <div class="flow-wizard-panel">
                                    <div class="flow-wizard-video" data-ppdb-video-placeholder>
                                        <video src="{{ asset('assets/alur.mp4') }}" controls></video>
                                    </div>
                                    <div>
                                        <h2 class="h4" data-ppdb-step-title>Cara Mengurangi Ukuran Berkas</h2>
                                        <p class="text-muted" data-ppdb-step-description>Ikuti panduan pada video untuk mengurangi ukuran foto atau PDF agar memenuhi batas ukuran berkas.</p>
                                    </div>
                                </div>
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <button type="button" class="btn btn-outline-secondary" data-ppdb-wizard-previous>Kembali</button>
                                    <button type="button" class="btn btn-primary" data-ppdb-wizard-next>Selanjutnya</button>
                                </div>
                            </div>
                        </section>
                        <span class="flow-wizard-connector" data-ppdb-wizard-connector data-status="pending" aria-hidden="true"></span>                        

                        <section class="flow-wizard-step" data-ppdb-wizard-step="7" data-status="pending">
                            <span class="flow-wizard-indicator bi bi-circle" data-ppdb-step-indicator aria-label="Langkah 5"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content hidden>
                                <div class="flow-wizard-panel">
                                    <div class="flow-wizard-image" data-ppdb-image-placeholder>
                                        <img src="{{ asset('assets/images/alur/Screenshot (2819).png') }}">
                                    </div>
                                    <div>
                                        <h2 class="h4" data-ppdb-step-title>Simpan Bukti Pendaftaran</h2>
                                        <p class="text-muted" data-ppdb-step-description>Setelah pendaftaran berhasil, simpan atau cetak bukti pendaftaran dan catat nomor pendaftaran untuk mengecek status pendaftaran.</p>
                                    </div>
                                </div>
                                
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <button type="button" class="btn btn-outline-secondary" data-ppdb-wizard-previous>Kembali</button>
                                    <button type="button" class="btn btn-primary" data-ppdb-wizard-next>Selanjutnya</button>
                                </div>
                            </div>
                        </section>
                        <span class="flow-wizard-connector" data-ppdb-wizard-connector data-status="pending" aria-hidden="true"></span>

                        <section class="flow-wizard-step" data-ppdb-wizard-step="8" data-status="pending">
                            <span class="flow-wizard-indicator bi bi-circle" data-ppdb-step-indicator aria-label="Langkah 5"></span>
                            <div class="flow-wizard-step-content" data-ppdb-step-content hidden>
                                <div class="flow-wizard-panel">
                                    <div class="flow-wizard-image" data-ppdb-image-placeholder>
                                        <img src="{{ asset('assets/images/alur/Screenshot (2840).png') }}">
                                    </div>
                                    <div>
                                        <h2 class="h4" data-ppdb-step-title>Periksa Email anda</h2>
                                        <p class="text-muted" data-ppdb-step-description>Periksa email yang Anda gunakan saat mendaftar. Informasi atau pemberitahuan terkait proses pendaftaran akan dikirim melalui email tersebut.</p>
                                    </div>
                                </div>
                                
                                <div class="flow-wizard-navigation" data-ppdb-step-navigation>
                                    <button type="button" class="btn btn-outline-secondary" data-ppdb-wizard-previous>Kembali</button>
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ppdbWarningModal"
                                    >
                                        Mulai Pendaftaran
                                    </button>                                   
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade ppdb-warning-modal"
    id="ppdbWarningModal"
    tabindex="-1"
    aria-labelledby="ppdbWarningModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header px-4 px-md-5 pt-4 pb-3">
                <div>
                    <div class="text-primary mb-2">
                        <i class="bi bi-info-circle-fill me-2" aria-hidden="true"></i>
                        Informasi Penting
                    </div>
                    <h2 class="modal-title fs-4 fw-bold" id="ppdbWarningModalLabel">
                        Perhatian Sebelum Mendaftar
                    </h2>
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <div class="modal-body px-4 px-md-5 py-4">
                <p class="text-muted mb-4">
                    Sebelum melanjutkan ke formulir pendaftaran, pastikan Anda telah membaca dan memahami informasi serta alur PPDB SDN Mekar Jaya.
                </p>

                <h3 class="fs-6 fw-bold text-dark mb-3">Hal yang perlu diperhatikan</h3>
                <ul class="ppdb-warning-list text-muted mb-4">
                    <li>Pastikan seluruh data yang diisi sesuai dengan dokumen resmi.</li>
                    <li>Pastikan persyaratan pendaftaran telah dipersiapkan.</li>
                    <li>Periksa kembali seluruh data sebelum mengirimkan formulir.</li>
                    <li>Data yang diberikan harus benar dan dapat dipertanggungjawabkan.</li>
                    <li class="fw-semibold text-primary">
                        <i class="bi bi-envelope-check me-2" aria-hidden="true"></i>
                        Perhatikan alamat email yang digunakan. Pastikan email aktif, benar, dan dapat diakses karena feedback, informasi, atau pemberitahuan terkait proses pendaftaran akan disampaikan melalui email tersebut.
                    </li>
                </ul>

                <div class="ppdb-email-note rounded-3 p-3 mb-4">
                    <p class="mb-0 fw-semibold">
                        <i class="bi bi-envelope me-2" aria-hidden="true"></i>
                        Pastikan alamat email yang Anda masukkan aktif dan dapat diakses. Seluruh feedback atau pemberitahuan terkait pendaftaran akan disampaikan melalui email tersebut.
                    </p>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="ppdbWarningAgreement">
                    <label class="form-check-label text-dark" for="ppdbWarningAgreement">
                        Saya telah membaca dan memahami informasi di atas serta siap melanjutkan ke formulir pendaftaran.
                    </label>
                </div>
            </div>

            <div class="modal-footer px-4 px-md-5 pb-4 pt-0">
                <button type="button" class="btn btn-light px-4 mt-3" data-bs-dismiss="modal">Batal</button>
                <button
                    type="button"
                    class="btn btn-primary px-4 mt-3"
                    id="ppdbContinueButton"
                    data-form-url="{{ route('ppdb.create') }}"
                    disabled
                >
                    Lanjut ke Formulir Pendaftaran 
                </button>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wizard = document.querySelector('[data-ppdb-wizard]');
        if (!wizard) return;

        const steps = [...wizard.querySelectorAll('[data-ppdb-wizard-step]')];
        const connectors = [...wizard.querySelectorAll('.flow-wizard-connector')];
        let activeIndex = 0;   
        
        steps.forEach((step, index) => {
            step.style.setProperty(
                '--wizard-step-center',
                `${((index + 0.5) / steps.length) * 100}%`
            );
        });

        connectors.forEach((connector, index) => {
            connector.style.setProperty(
                '--wizard-connector-start',
                `${((index + 0.5) / steps.length) * 100}%`
            );

            connector.style.setProperty(
                '--wizard-connector-width',
                `${100 / steps.length}%`
            );
        });     

        const renderStep = () => {
            steps.forEach((step, index) => {
                const status = index === activeIndex ? 'active' : index < activeIndex ? 'complete' : 'pending';
                const indicator = step.querySelector('[data-ppdb-step-indicator]');
                const content = step.querySelector('[data-ppdb-step-content]');
                const title = step.querySelector('[data-ppdb-step-title]')?.textContent.trim() || '';

                step.dataset.status = status;
                
                if (status === 'active') step.setAttribute('aria-current', 'step');
                else step.removeAttribute('aria-current');
                if (indicator) {
                    indicator.classList.remove(
                        'bi-check-circle-fill',
                        'bi-circle-fill',
                        'bi-circle'
                    );
                    const iconClass = {
                        complete: 'bi-check-circle-fill',
                        active: 'bi-circle-fill',
                        pending: 'bi-circle'
                    };

                    indicator.classList.add(iconClass[status]);

                    indicator.setAttribute(
                        'aria-label',
                        `Langkah ${index + 1}: ${title}, ${status}`
                    );
                }
                if (content) content.hidden = index !== activeIndex;
            });

            connectors.forEach((connector, index) => {
                connector.dataset.status = index < activeIndex ? 'complete' : 'pending';
            });
        };

        wizard.addEventListener('click', (event) => {
            const nextButton = event.target.closest('[data-ppdb-wizard-next]');
            const previousButton = event.target.closest('[data-ppdb-wizard-previous]');
            if (!nextButton && !previousButton) return;

            activeIndex += nextButton ? 1 : -1;
            activeIndex = Math.max(0, Math.min(activeIndex, steps.length - 1));
            renderStep();
        });

        renderStep();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const warningModal = document.getElementById('ppdbWarningModal');
        const agreement = document.getElementById('ppdbWarningAgreement');
        const continueButton = document.getElementById('ppdbContinueButton');

        if (!warningModal || !agreement || !continueButton) {
            return;
        }

        agreement.addEventListener('change', function () {
            continueButton.disabled = !agreement.checked;
        });

        continueButton.addEventListener('click', function () {
            if (agreement.checked) {
                window.location.href = continueButton.dataset.formUrl;
            }
        });

        warningModal.addEventListener('hidden.bs.modal', function () {
            agreement.checked = false;
            continueButton.disabled = true;
        });
    });
</script>

@endpush