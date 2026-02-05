@extends('layouts.tu')

@section('title', 'Manajemen Data Siswa')

@section('content')

{{-- ============================
   STEP SELECTOR SISWA
============================ --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('tu.siswa.index') }}" class="row g-3 align-items-end">

            {{-- ANGKATAN --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">Angkatan</label>
                <div class="input-group">
                    <select id="angkatan" name="angkatan" class="form-select">
                        <option value="">-- Pilih Angkatan --</option>
                        @foreach ($listAngkatan as $a)
                            <option value="{{ $a->id }}"
                                {{ request('angkatan') == $a->id ? 'selected' : '' }}>
                                {{ $a->tahun }} ({{ $a->keterangan }})
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('tu.angkatan.create') }}" class="btn btn-outline-primary">
                        +
                    </a>
                </div>
            </div>

            {{-- GRUP KELAS --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">Grup Kelas</label>
                <select id="grup_kelas" class="form-select" disabled>
                    <option value="">-- Pilih Grup Kelas --</option>
                </select>
            </div>

            {{-- ROMBEL --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kelas (Rombel)</label>
                <select id="kelas" name="kelas_id" class="form-select" disabled>
                    <option value="">-- Pilih Rombel --</option>
                </select>
            </div>

            {{-- SUBMIT --}}
            <div class="col-md-3">
                <button id="btnSubmit" type="submit" class="btn btn-primary w-100" disabled>
                    Tampilkan Data Siswa
                </button>
            </div>

        </form>
    </div>
</div>

{{-- INFO --}}
@if (!request()->has('kelas_id'))
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Pilih <strong>Angkatan → Grup Kelas → Rombel</strong> untuk mulai mengelola data siswa.
    </div>
@endif

{{-- ============================
   TABEL SISWA (LOCKED)
============================ --}}
@if (request()->has('kelas_id'))
    @include('components.crud.index', [

        'title' => 'Data Siswa',

        'rows'  => $siswa,

        'columns' => [
            [
                'key' => 'nama',
                'label' => 'Nama Siswa',
                'sortable' => true,
            ],
            [
                'key' => 'nis',
                'label' => 'NIS',
                'sortable' => true,
            ],
            [
                'key' => '__kelas',
                'label' => 'Rombel',
                'value' => fn ($row) =>
                    '<span class="badge bg-primary">'
                    . e($row->kelas->nama_kelas)
                    . '</span>',
                'raw' => true,
            ],
            [
                'key' => '__angkatan',
                'label' => 'Angkatan',
                'value' => fn ($row) =>
                    $row->kelas->kelasGrup->angkatan->tahun ?? '-',
            ],
            [
                'key' => '__status',
                'label' => 'Status',
                'value' => '<span class="badge bg-success">Aktif</span>',
                'raw' => true,
            ],
        ],

        'actions' => [
            'detail' => 'tu.siswa.detail',
            'edit' => 'tu.siswa.edit',
            'delete' => 'tu.siswa.destroy',
            'restore' => 'tu.siswa.restore',
            'forceDelete' => 'tu.siswa.forceDelete',
        ],

        'routeBulk' => 'tu.siswa.bulk',
    ])
@endif

{{-- ============================
   AJAX CASCADE SELECT
============================ --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const angkatan = document.getElementById('angkatan');

    // 🔥 AUTO LOAD GRUP KELAS JIKA ANGKATAN SUDAH TERISI
    if (angkatan.value) {
        angkatan.dispatchEvent(new Event('change'));
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const angkatan = document.getElementById('angkatan');
    const grup = document.getElementById('grup_kelas');
    const kelas = document.getElementById('kelas');
    const btn = document.getElementById('btnSubmit');

    angkatan.addEventListener('change', () => {
        grup.innerHTML = '<option>Loading...</option>';
        grup.disabled = true;
        kelas.innerHTML = '<option>-- Pilih Rombel --</option>';
        kelas.disabled = true;
        btn.disabled = true;

        if (!angkatan.value) return;

        fetch(`/tu/ajax/kelas-grup?angkatan_id=${angkatan.value}`)
            .then(res => res.json())
            .then(data => {
                grup.innerHTML = '<option value="">-- Pilih Grup Kelas --</option>';
                data.forEach(item => {
                    grup.innerHTML += `<option value="${item.id}">${item.label}</option>`;
                });
                grup.disabled = false;
            });
    });

    grup.addEventListener('change', () => {
        kelas.innerHTML = '<option>Loading...</option>';
        kelas.disabled = true;
        btn.disabled = true;

        if (!grup.value) return;

        fetch(`/tu/ajax/kelas?kelas_grup_id=${grup.value}`)
            .then(res => res.json())
            .then(data => {
                kelas.innerHTML = '<option value="">-- Pilih Rombel --</option>';
                data.forEach(item => {
                    kelas.innerHTML += `<option value="${item.id}">${item.nama_kelas}</option>`;
                });
                kelas.disabled = false;
            });
    });

    kelas.addEventListener('change', () => {
        btn.disabled = !kelas.value;
    });
});
</script>

@endsection
