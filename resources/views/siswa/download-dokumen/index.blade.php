@extends('layouts.app')

@section('title', 'Download Dokumen')

@section('content')

<div class="container-fluid py-4">

    @php
    $totalDokumen = $dokumen->count();
    $totalKategori = $dokumen->unique('kategori_surat')->count();
    @endphp

    {{-- ================= HERO ================= --}}
    <x-hero.page
        title="Download Dokumen"
        description="Unduh berbagai dokumen sekolah yang telah disediakan, seperti surat, formulir, maupun dokumen administrasi lainnya.">
        
        <div class="row g-3">
            <div class="col-6">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-primary mb-1">{{ $totalDokumen }}</h4>
                    <small class="text-muted">Dokumen</small>
                </div>
            </div>

            <div class="col-6">
                <div class="bg-white rounded-4 p-3 text-center">
                    <h4 class="fw-bold text-success mb-1">{{ $totalKategori }}</h4>
                    <small class="text-muted">Kategori</small>
                </div>
            </div>
        </div>

    </x-hero.page>

    {{-- ================= TABLE ================= --}}
    <x-table.card
        title="Daftar Dokumen"
        subtitle="Pilih kategori untuk mempermudah pencarian dokumen.">

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                {{ $totalDokumen }} Dokumen
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <select class="form-select rounded-3 d-inline-block" id="filterKategori" style="max-width:280px;">
                <option value="">Semua Kategori</option>
                @foreach($dokumen->unique('kategori_surat') as $item)
                <option value="{{ $item->kategori_surat }}">{{ $item->kategori_surat }}</option>
                @endforeach
            </select>
        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70" class="text-center">No</th>
                        <th>Nama Dokumen</th>
                        <th width="220">Kategori</th>
                        <th width="170" class="text-center">Download</th>
                    </tr>
                </thead>

                <tbody id="tableDokumen">
                    @forelse($dokumen as $row)
                    <tr data-kategori="{{ $row->kategori_surat }}">
                        <td class="text-center">
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">{{ $loop->iteration }}</span>
                        </td>

                        <td>
                            <div class="d-flex align-items-center">
                                <div class="doc-icon me-3"><i class="bi bi-file-earmark-text-fill"></i></div>
                                <div>
                                    <div class="fw-bold">{{ $row->nama_surat }}</div>
                                    <small class="text-muted">Dokumen Sekolah</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $row->kategori_surat }}</span>
                        </td>

                        <td class="text-center">
                            <a href="{{ asset('uploads/dokumen/'.$row->file) }}" class="btn btn-success rounded-pill btn-sm px-3" download>
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
                                <h5 class="fw-bold mt-3">Belum Ada Dokumen</h5>
                                <p class="text-muted mb-0">Saat ini belum tersedia dokumen yang dapat diunduh.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-table.card>
</div>

<style>
    .doc-icon{
        width:50px;height:50px;border-radius:16px;background:linear-gradient(135deg,#2563eb,#0f4c81);
        color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;
    }
    .btn{font-weight:600;}
    .btn-success{box-shadow:0 8px 20px rgba(25,135,84,.18);}    
    .btn-success:hover{transform:translateY(-2px);}

    .form-select{border-radius:12px;min-height:46px;}

    @media(max-width:768px){
        .doc-icon{width:42px;height:42px;font-size:1rem;}
    }
</style>

{{-- FILTER --}}
<script>
    const filterKategori = document.getElementById('filterKategori');
    const rows = document.querySelectorAll('#tableDokumen tr');

    filterKategori.addEventListener('change', function () {
        const value = this.value;
        rows.forEach(row => {
            const kategori = row.getAttribute('data-kategori');
            if (value === '' || kategori === value) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection