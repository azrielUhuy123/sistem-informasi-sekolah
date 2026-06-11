@extends('layouts.public')

@section('title', 'Form Pendaftaran PPDB')

@section('content')

<div class="container py-5">
    <div class="card shadow">
        <div class="card-header">
            <h3 class="mb-0">Form Pendaftaran PPDB</h3>
        </div>

        <div class="card-body">
            {{-- Progress Wizard --}}
            <div class="mb-4">
                <div class="progress" style="height:12px;">
                    <div id="wizardBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:20%"></div>
                </div>

                <div class="row text-center mt-3">
                    <div class="col">
                        <small id="labelStep1" class="fw-bold text-primary">Data Pribadi</small>
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

        document.getElementById('step' + step).classList.remove('d-none');

        let width = 20;
        if (step == 2) width = 40;
        if (step == 3) width = 60;
        if (step == 4) width = 80;
        if (step == 5) width = 100;

        document.getElementById('wizardBar').style.width = width + '%';
    }
</script>
@endpush
