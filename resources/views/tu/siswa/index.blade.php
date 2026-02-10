@extends('layouts.tu')

@section('title', 'Manajemen Data Siswa')

@section('content')

{{-- ============================
   FILTER SISWA (TANPA FORM SUBMIT)
============================ --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-end">

            {{-- ANGKATAN --}}
            <div class="col-md-3">
                <label class="form-label fw-semibold">Angkatan</label>
                <div class="input-group">
                    <select id="angkatan" class="form-select">
                        <option value="">-- Pilih Angkatan --</option>
                        @foreach ($listAngkatan as $a)
                            <option value="{{ $a->id }}">
                                {{ $a->tahun }} ({{ $a->keterangan }})
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('tu.angkatan.create') }}" class="btn btn-outline-primary">+</a>
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
                <select id="kelas" class="form-select" disabled>
                    <option value="">-- Pilih Rombel --</option>
                </select>
            </div>

            {{-- BUTTON --}}
            <div class="col-md-3">
                <button id="btnSubmit" type="button" class="btn btn-primary w-100" disabled>
                    Tampilkan Data Siswa
                </button>
            </div>

        </div>
    </div>
</div>

{{-- INFO --}}
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i>
    Pilih <strong>Angkatan → Grup Kelas → Rombel</strong>, lalu klik
    <strong>Tampilkan Data Siswa</strong>.
</div>

{{-- ============================
   HASIL TABEL (MUNCUL DI SINI)
============================ --}}
<div id="result-table" class="mt-4"></div>

@endsection

{{-- ============================
   SCRIPT
============================ --}}
@section('scripts')
<script>
    
document.addEventListener('DOMContentLoaded', () => {
    const angkatan = document.getElementById('angkatan');
    const grup     = document.getElementById('grup_kelas');
    const kelas    = document.getElementById('kelas');
    const btn      = document.getElementById('btnSubmit');
    const result   = document.getElementById('result-table');

    // ============================
    // GLOBAL AJAX TABLE RELOADER
    // ============================
    function reloadTable(extraParams = {}) {
        const kelasId = document.getElementById('kelas')?.value;
        if (!kelasId) return;

        const url = new URL('/tu/ajax/siswa-table', window.location.origin);
        url.searchParams.set('kelas_id', kelasId);

        // ambil state dari URL sekarang
        const current = new URL(window.location.href);
        ['page', 'sort', 'order', 'per_page'].forEach(key => {
            const val = current.searchParams.get(key);
            if (val) url.searchParams.set(key, val);
        });

        // override jika dikirim
        Object.entries(extraParams).forEach(([k, v]) => {
            if (v !== null && v !== undefined) {
                url.searchParams.set(k, v);
            }
        });

        result.innerHTML = '<div class="py-3 text-muted">Memuat data...</div>';

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            result.innerHTML = html;
            if (window.initCrudTable) {
                window.initCrudTable();
            }
        })
        .catch(err => {
            console.error(err);
            result.innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
        });
    }

    // ANGKATAN → GRUP
    angkatan.addEventListener('change', () => {
        grup.innerHTML = '<option>Loading...</option>';
        grup.disabled = true;
        kelas.innerHTML = '<option>-- Pilih Rombel --</option>';
        kelas.disabled = true;
        btn.disabled = true;
        result.innerHTML = '';

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

    // GRUP → KELAS
    grup.addEventListener('change', () => {
        kelas.innerHTML = '<option>Loading...</option>';
        kelas.disabled = true;
        btn.disabled = true;
        result.innerHTML = '';

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

    // ENABLE BUTTON
    kelas.addEventListener('change', () => {
        btn.disabled = !kelas.value;
    });

    // LOAD TABLE (🔥 FIX DI SINI)
    btn.addEventListener('click', () => {
        if (!kelas.value) return;

        btn.disabled = true;
        btn.innerText = 'Memuat...';
        result.innerHTML = '';

        reloadTable();

            .finally(() => {
                btn.disabled = false;
                btn.innerText = 'Tampilkan Data Siswa';
            });
    });
    // ============================
    // AJAX PAGINATION (INTERCEPT)
    // ============================
    result.addEventListener('click', function (e) {
        const link = e.target.closest('.simple-pagination a');
        if (!link) return;

        e.preventDefault();

        const url = link.getAttribute('href');
        if (!url) return;

        result.innerHTML = '<div class="py-3 text-muted">Memuat halaman...</div>';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            result.innerHTML = html;
            if (window.initCrudTable) {
                window.initCrudTable();
            }
        })
        .catch(err => {
            console.error(err);
            result.innerHTML = '<div class="alert alert-danger">Gagal memuat halaman</div>';
        });
    });
    // ============================
    // AJAX SORTING (OVERRIDE)
    // ============================
    window.applySort = function (column) {
        const url = new URL(window.location.href);
        const currentSort = url.searchParams.get('sort');
        const currentOrder = url.searchParams.get('order') || 'asc';

        if (currentSort === column) {
            url.searchParams.set('order', currentOrder === 'asc' ? 'desc' : 'asc');
        } else {
            url.searchParams.set('sort', column);
            url.searchParams.set('order', 'asc');
        }

        // penting: tetap sertakan kelas_id
        const kelasId = document.getElementById('kelas')?.value;
        if (kelasId) {
            url.searchParams.set('kelas_id', kelasId);
        }

        const fetchUrl = `/tu/ajax/siswa-table?${url.searchParams.toString()}`;

        const result = document.getElementById('result-table');
        result.innerHTML = '<div class="py-3 text-muted">Mengurutkan data...</div>';

        fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            result.innerHTML = html;
            if (window.initCrudTable) {
                window.initCrudTable();
            }
        })
        .catch(err => {
            console.error(err);
            result.innerHTML = '<div class="alert alert-danger">Gagal mengurutkan data</div>';
        });
    };
    // ============================
    // AJAX PER PAGE (DELEGATION)
    // ============================
    result.addEventListener('change', function (e) {
        const select = e.target;
        if (!select || select.id !== 'perPage') return;

        const perPage = select.value;
        const kelasId = document.getElementById('kelas')?.value;
        if (!kelasId) return;

        const url = new URL('/tu/ajax/siswa-table', window.location.origin);
        url.searchParams.set('kelas_id', kelasId);
        url.searchParams.set('per_page', perPage);

        // pertahankan sort & order
        const current = new URL(window.location.href);
        ['sort', 'order'].forEach(k => {
            const v = current.searchParams.get(k);
            if (v) url.searchParams.set(k, v);
        });

        result.innerHTML = '<div class="py-3 text-muted">Memuat data...</div>';

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            result.innerHTML = html;
            if (window.initCrudTable) {
                window.initCrudTable();
            }
        })
        .catch(err => {
            console.error(err);
            result.innerHTML = '<div class="alert alert-danger">Gagal memuat data</div>';
        });
    });




});
</script>

@endsection
