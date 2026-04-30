@extends('layouts.tu')

@section('title', 'Tambah Tahun Ajaran')

@section('content')

<div class="container-fluid">

    <h4 class="mb-4">Tambah Tahun Ajaran</h4>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('tu.tahun.store') }}" method="POST">
                @csrf

                <!-- 🔘 TOGGLE -->
                <div class="form-check mb-3">
                    <input type="checkbox" id="manualToggle" class="form-check-input">
                    <label class="form-check-label">
                        Input manual (override sistem)
                    </label>
                </div>

                <!-- 📅 TAHUN -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tahun Ajaran</label>

                    <input
                        type="text"
                        name="tahun"
                        id="tahunInput"
                        class="form-control"
                        value="{{ $tahunAuto }}"
                        readonly
                        required
                    >

                    <small class="text-muted">
                        Otomatis mengikuti urutan sistem
                    </small>
                </div>

                <!-- 📘 SEMESTER -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Semester</label>

                    <select
                        name="semester"
                        id="semesterInput"
                        class="form-select"
                        disabled
                    >
                        <option value="Ganjil" {{ $semesterAuto === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ $semesterAuto === 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>

                    <!-- hidden agar tetap terkirim saat disabled -->
                    <input type="hidden" name="semester" id="semesterHidden" value="{{ $semesterAuto }}">
                </div>

                <!-- 🔘 ACTION -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('tu.tahun.index') }}" class="btn btn-secondary">
                        ← Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const toggle = document.getElementById('manualToggle');
    const tahunInput = document.getElementById('tahunInput');
    const semesterInput = document.getElementById('semesterInput');
    const semesterHidden = document.getElementById('semesterHidden');

    toggle.addEventListener('change', function() {

        if (this.checked) {
            // MODE MANUAL
            tahunInput.removeAttribute('readonly');
            semesterInput.removeAttribute('disabled');

        } else {
            // MODE AUTO
            tahunInput.setAttribute('readonly', true);
            semesterInput.setAttribute('disabled', true);
        }

        // sync hidden
        semesterHidden.value = semesterInput.value;
    });
</script>
@endpush