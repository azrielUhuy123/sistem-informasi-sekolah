@extends('layouts.app')

@section('title', 'Download Dokumen')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">
            Download Dokumen
        </h3>

        <p class="text-muted">
            Download dokumen siswa yang tersedia
        </p>

    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-end">

                <select
                    class="form-select w-auto"
                    id="filterKategori"
                >

                    <option value="">
                        Pilih Kategori
                    </option>

                    @foreach($dokumen->unique('kategori_surat') as $item)

                        <option
                            value="{{ $item->kategori_surat }}"
                        >

                            {{ $item->kategori_surat }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th width="60">
                            No
                        </th>

                        <th>
                            Nama Surat
                        </th>

                        <th width="220">
                            Kategori
                        </th>

                        <th width="180" class="text-center">
                            Unduh
                        </th>

                    </tr>

                </thead>

                <tbody id="tableDokumen">

                    @forelse($dokumen as $row)

                        <tr
                            data-kategori="{{ $row->kategori_surat }}"
                        >

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td class="fw-semibold">

                                {{ $row->nama_surat }}

                            </td>

                            <td>

                                {{ $row->kategori_surat }}

                            </td>

                            <td class="text-center">

                                <a
                                    href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                    class="btn btn-success btn-sm rounded-3"
                                    download
                                >

                                    <i class="bi bi-cloud-download-fill"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center text-muted py-4"
                            >

                                Belum ada dokumen tersedia

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

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