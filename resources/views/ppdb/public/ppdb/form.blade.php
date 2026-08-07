    @extends('layouts.public')

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
            background: white;
        }
        .card-header h3 {
            margin-bottom: 0;
            font-size: 1.25rem;
        }
        .card-body {
            padding: 1.5rem 1.25rem;
        }
        .progress {
            height: 14px;
            border-radius: 999px;
            background: rgba(23,65,110,.12);
            overflow: hidden;
        }
        .progress-bar {
            border-radius: 999px;
            background: linear-gradient(90deg, #3a7edd, #17416e);
        }
        .wizard-steps {
            gap: 0.75rem;
        }
        .wizard-steps .col {
            max-width: 20%;
        }
        .wizard-steps small {
            display: block;
            color: #5b636e;
            font-size: 0.88rem;
            line-height: 1.3;
            white-space: normal;
        }
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem 1.25rem;
            }
            .wizard-steps .col {
                flex: 0 0 50%;
                max-width: 50%;
            }
            .wizard-steps small {
                display: none;
            }
        }
        @media (max-width: 576px) {
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            .card {
                box-shadow: 0 16px 40px rgba(0,0,0,.08);
            }
            .progress {
                height: 12px;
            }
            .wizard-steps .col {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .wizard-steps small {
                
                color: #3f4a5a;
            }
        }
    </style>

    {{-- ALERT --}}
        <x-alert.flash key="success" type="success"/>

        <x-alert.flash key="error" type="danger"/>

    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-1 mt-2">Form Pendaftaran PPDB</h3>
            </div>

            <div class="card-body">
                {{-- Progress Wizard --}}
                <div class="mb-4">
                    <div class="progress">
                        <div id="wizardBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:20%"></div>
                    </div>
                    

                    <div class="row text-center mt-3 wizard-steps">
                        <div class="col">
                            <small id="labelStep1">Data Pribadi</small>
                        </div>

                        <div class="col">
                            <small id="labelStep2">Alamat</small>
                        </div>

                        <div class="col">
                            <small id="labelStep3">Orang Tua</small>
                        </div>

                        <div class="col">
                            <small id="labelStep4">Kontak</small>
                        </div>

                        <div class="col">
                            <small id="labelStep5">Berkas</small>
                        </div>
                    </div>
                </div>
                

                <form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @include('ppdb.public.ppdb.steps.step1')
                    @include('ppdb.public.ppdb.steps.step2')
                    @include('ppdb.public.ppdb.steps.step3')
                    @include('ppdb.public.ppdb.steps.step4')
                    @include('ppdb.public.ppdb.steps.step5')
                </form>
            </div>
        </div>
    </div>

    @endsection

    @push('scripts')
    <script>
        function showStep(step) {
            document.querySelectorAll('.wizard-step').forEach(item => {
                item.classList.add('d-none');
            });

            document.querySelectorAll('.wizard-step').forEach(stepElement => {

                stepElement.querySelectorAll('input, select, textarea').forEach(field => {

                    if (field.hasAttribute('required')) {
                        field.dataset.required = 'true';
                    }

                    field.required = false;

                });

            });

            const currentStep = document.getElementById('step' + step);

            currentStep.classList.remove('d-none');

            currentStep.querySelectorAll('input, select, textarea').forEach(field => {

                if (field.dataset.required === 'true') {
                    field.required = true;
                }

            });

            let width = 20;
            if (step == 2) width = 40;
            if (step == 3) width = 60;
            if (step == 4) width = 80;
            if (step == 5) width = 100;

            document.getElementById('wizardBar').style.width = width + '%';
        }
        document.addEventListener('DOMContentLoaded', () => {
            showStep(1);
        });
    </script>
    @endpush
