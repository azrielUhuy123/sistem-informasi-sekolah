@extends('layouts.app')

@section('title', 'Download Dokumen')

@section('content')

<div class="container-fluid py-4">

    @php

        $totalDokumen = $dokumen->count();

        $totalKategori = $dokumen->unique('kategori_surat')->count();

    @endphp

    {{-- ================= HERO ================= --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

        <div
            class="card-body p-4 p-lg-5 text-white"
            style="background:linear-gradient(135deg,#0f4c81 0%,#2563eb 100%);">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <h2 class="fw-bold mb-2">

                        Download Dokumen

                    </h2>

                    <p class="mb-0 opacity-75">

                        Unduh berbagai dokumen sekolah yang telah disediakan,
                        seperti surat, formulir, maupun dokumen administrasi lainnya.

                    </p>

                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-primary mb-1">

                                    {{ $totalDokumen }}

                                </h4>

                                <small class="text-muted">

                                    Dokumen

                                </small>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="bg-white rounded-4 p-3 text-center">

                                <h4 class="fw-bold text-success mb-1">

                                    {{ $totalKategori }}

                                </h4>

                                <small class="text-muted">

                                    Kategori

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ================= TABLE ================= --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-4">

            <div class="row align-items-center g-3">

                <div class="col-lg-7">

                    <h5 class="fw-bold mb-1">

                        Daftar Dokumen

                    </h5>

                    <small class="text-muted">

                        Pilih kategori untuk mempermudah pencarian dokumen.

                    </small>

                </div>

                <div class="col-lg-5 text-lg-end">

                    <select
                        class="form-select rounded-3 d-inline-block"
                        id="filterKategori"
                        style="max-width:280px;">

                        <option value="">

                            Semua Kategori

                        </option>

                        @foreach($dokumen->unique('kategori_surat') as $item)

                            <option value="{{ $item->kategori_surat }}">

                                {{ $item->kategori_surat }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="70" class="text-center">

                                No

                            </th>

                            <th>

                                Nama Dokumen

                            </th>

                            <th width="220">

                                Kategori

                            </th>

                            <th width="170" class="text-center">

                                Download

                            </th>

                        </tr>

                    </thead>

                <tbody id="tableDokumen">

    @forelse($dokumen as $row)

        <tr data-kategori="{{ $row->kategori_surat }}">

            <td class="text-center">

                <span class="badge bg-light text-dark rounded-pill px-3 py-2">

                    {{ $loop->iteration }}

                </span>

            </td>

            <td>

                <div class="d-flex align-items-center">

                    <div class="doc-icon me-3">

                        <i class="bi bi-file-earmark-text-fill"></i>

                    </div>

                    <div>

                        <div class="fw-bold">

                            {{ $row->nama_surat }}

                        </div>

                        <small class="text-muted">

                            Dokumen Sekolah

                        </small>

                    </div>

                </div>

            </td>

            <td>

                <span class="badge bg-primary rounded-pill px-3 py-2">

                    {{ $row->kategori_surat }}

                </span>

            </td>

            <td class="text-center">

                <a
                    href="{{ asset('uploads/dokumen/'.$row->file) }}"
                    class="btn btn-success rounded-pill btn-sm px-3"
                    download>

                    <i class="bi bi-cloud-arrow-down-fill me-1"></i>

                    Download

                </a>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4">

                <div class="text-center py-5">

                    <i class="bi bi-folder2-open display-3 text-secondary"></i>

                    <h5 class="fw-bold mt-3">

                        Belum Ada Dokumen

                    </h5>

                    <p class="text-muted mb-0">

                        Saat ini belum tersedia dokumen yang dapat diunduh.

                    </p>

                </div>

            </td>

        </tr>

    @endforelse

</tbody>

</table>

</div>

</div>

</div>

<style>

.card{

    transition:.25s;

}

.card:hover{

    box-shadow:0 18px 45px rgba(37,99,235,.08)!important;

}

.table thead th{

    background:#f8fafc;

    color:#64748b;

    text-transform:uppercase;

    font-size:.82rem;

    font-weight:700;

    letter-spacing:.5px;

    border:0;

    padding:18px;

}

.table tbody td{

    padding:18px;

    vertical-align:middle;

    border-color:#eef2f7;

}

.table-hover tbody tr{

    transition:.2s;

}

.table-hover tbody tr:hover{

    background:#f8fbff;

}

.doc-icon{

    width:50px;

    height:50px;

    border-radius:16px;

    background:linear-gradient(135deg,#2563eb,#0f4c81);

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:1.2rem;

    flex-shrink:0;

}

.badge{

    font-weight:600;

    letter-spacing:.3px;

}

.btn{

    font-weight:600;

}

.btn-success{

    box-shadow:0 8px 20px rgba(25,135,84,.18);

}

.btn-success:hover{

    transform:translateY(-2px);

}

.form-select{

    border-radius:12px;

    min-height:46px;

}

@media(max-width:768px){

    .doc-icon{

        width:42px;

        height:42px;

        font-size:1rem;

    }

}

</style>

{{-- FILTER --}}
<script>

    const filterKategori =
        document.getElementById('filterKategori');

    const rows =
        document.querySelectorAll('#tableDokumen tr');

    filterKategori.addEventListener('change', function () {

        const value = this.value;

        rows.forEach(row => {

            const kategori =
                row.getAttribute('data-kategori');

            if (
                value === '' ||
                kategori === value
            ) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }
        });
    });

</script>

@endsection