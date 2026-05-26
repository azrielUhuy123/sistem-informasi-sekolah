@extends('layouts.app')

@section('title', 'Dokumen Siswa')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div
        class="d-flex justify-content-between align-items-center mb-4"
    >

        <div>

            <h2 class="fw-bold mb-1">
                Dokumen Siswa
            </h2>

            <p class="text-muted mb-0">
                Kelola seluruh dokumen siswa dengan mudah
            </p>

        </div>

        <a
            href="{{ route('tu.dokumen.create', 'siswa') }}"
            class="btn btn-primary rounded-3 px-4 py-2 shadow-sm"
        >

            <i class="bi bi-plus-circle me-1"></i>
            Tambah Dokumen

        </a>

    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div
            class="alert alert-success border-0 rounded-4 shadow-sm"
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div
            class="alert alert-danger border-0 rounded-4 shadow-sm"
        >

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}

        </div>

    @endif

    {{-- CARD --}}
    <div
        class="card border-0 shadow-sm rounded-4 overflow-hidden"
    >

        {{-- TOP BAR --}}
        <div
            class="card-header bg-white border-0 p-4"
        >

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3"
            >

                <div>

                    <h5 class="fw-semibold mb-1">
                        Daftar Dokumen
                    </h5>

                    <span class="text-muted small">
                        Total:
                        {{ $dokumen->count() }}
                        dokumen
                    </span>

                </div>

                <div style="max-width: 280px; width:100%;">

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control rounded-3"
                        placeholder="Cari dokumen..."
                    >

                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="table-responsive">

            <table
                class="table align-middle mb-0"
            >

                <thead
                    style="
                        background:#f8fafc;
                    "
                >

                    <tr>

                        <th
                            class="py-3 px-4 text-muted fw-semibold"
                            width="70"
                        >
                            No
                        </th>

                        <th
                            class="py-3 text-muted fw-semibold"
                        >
                            Nama Surat
                        </th>

                        <th
                            class="py-3 text-muted fw-semibold"
                        >
                            Kategori
                        </th>

                        <th
                            class="py-3 text-muted fw-semibold text-center"
                            width="140"
                        >
                            Preview
                        </th>

                        <th
                            class="py-3 text-muted fw-semibold text-center"
                            width="220"
                        >
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody id="tableDokumen">

                    @forelse($dokumen as $row)

                        <tr>

                            <td class="px-4">

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                <div
                                    class="fw-semibold"
                                >

                                    {{ $row->nama_surat }}

                                </div>

                            </td>

                            <td>

                                <span
                                    class="badge rounded-pill bg-light text-dark border px-3 py-2"
                                >

                                    {{ $row->kategori_surat }}

                                </span>

                            </td>

                            <td class="text-center">

                                @if(
                                    pathinfo(
                                        $row->file,
                                        PATHINFO_EXTENSION
                                    ) === 'pdf'
                                )

                                    <a
                                        href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                        target="_blank"
                                        class="btn btn-light border rounded-3 btn-sm px-3"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>

                                @else

                                    <span class="text-muted small">
                                        -
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                <div
                                    class="d-flex justify-content-center gap-2"
                                >

                                    {{-- DOWNLOAD --}}
                                    <a
                                        href="{{ asset('uploads/dokumen/' . $row->file) }}"
                                        download
                                        class="btn btn-success btn-sm rounded-3 px-3"
                                    >

                                        <i class="bi bi-download"></i>

                                    </a>

                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('tu.dokumen.destroy', $row->id) }}"
                                        method="POST"
                                        onsubmit="
                                            return confirm(
                                                'Hapus dokumen ini?'
                                            )
                                        "
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm rounded-3 px-3"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5 text-muted"
                            >

                                <i
                                    class="bi bi-folder2-open d-block mb-2"
                                    style="font-size:40px;"
                                ></i>

                                Tidak ada dokumen siswa

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tableDokumen tr'
            );

        rows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                ? ''
                : 'none';

        });
    });

</script>

@endsection