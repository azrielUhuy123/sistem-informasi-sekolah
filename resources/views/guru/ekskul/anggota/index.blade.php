@extends('layouts.app')

@section('title', 'Anggota Ekskul')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Pendafaran Anggota</h3>
                    <p class="mb-0 opacity-75">Kelola pendaftaran anggota ekstrakurikuler</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sistem Terhubung
                </div>
            </div>
        </div>
    </div>


    {{-- ALERT SUCCESS --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

        </div>

    @endif

    {{-- ALERT ERROR --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

        </div>

    @endif

    {{-- SEARCH --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-md-10">

                        <label class="form-label">

                            Cari Siswa

                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Nama / NIS"
                            value="{{ $search }}"
                        >

                    </div>

                    <div class="col-md-2">

                        <button class="btn btn-dark w-100">

                            Cari

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- FORM --}}
    <form
        action="{{ route('guru.ekskul.anggota.store') }}"
        method="POST"
    >

        @csrf
        <div class="d-flex justify-content-end align-items-center mb-3">

            <button class="btn btn-dark">
                Tambahkan Anggota
            </button>

        </div>


        <div class="card border-0 shadow-sm">

            <div class="card-body">

                {{-- PREVIEW --}}
                    <div
                        id="previewBox"
                        class="mb-4 d-none"
                    >

                        <h6 class="fw-bold mb-3">

                            Siswa Dipilih

                        </h6>

                        <div
                            id="previewList"
                            class="d-flex flex-wrap gap-2"
                        ></div>

                    </div>

                {{-- TABLE --}}
                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="50">

                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="checkAll"
                                    >

                                </th>

                                <th>No</th>

                                <th>Nama</th>
                                <th>NIS</th>
                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($siswa as $s)

                                @php
                                    $sudahJoin = in_array(
                                        $s->id,
                                        $anggotaIds
                                    );
                                @endphp

                                <tr>

                                    <td>

                                        @if(!$sudahJoin)

                                            <input
                                                type="checkbox"
                                                name="siswa_id[]"
                                                value="{{ $s->id }}"
                                                class="form-check-input checkSiswa"
                                                data-nama="{{ $s->nama }}"
                                            >

                                        @endif

                                    </td>

                                    <td>
                                        {{ $siswa->firstItem() + $loop->index }}
                                    </td>

                                    <td>

                                        {{ $s->nama }}

                                    </td>

                                    <td>

                                        {{ $s->nis }}

                                    </td>

                                    <td>

                                        @if($sudahJoin)

                                            <span class="badge bg-success">

                                                Sudah Join

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                Belum Join

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>
                    <div class="mt-4">
                        {{ $siswa->links() }}
                    </div>

                </div>

            </div>

        </div>


    </form>

</div>

{{-- SCRIPT --}}
<script>

    const checkAll = document.getElementById('checkAll');

    const checks = document.querySelectorAll('.checkSiswa');

    const previewBox = document.getElementById('previewBox');

    const previewList = document.getElementById('previewList');

    /*
    |--------------------------------------------------------------------------
    | CHECK ALL
    |--------------------------------------------------------------------------
    */

    checkAll?.addEventListener('change', function () {

        checks.forEach(c => {

            c.checked = this.checked;

        });

        renderPreview();

    });

    /*
    |--------------------------------------------------------------------------
    | SINGLE CHECK
    |--------------------------------------------------------------------------
    */

    checks.forEach(c => {

        c.addEventListener(
            'change',
            renderPreview
        );

    });

    /*
    |--------------------------------------------------------------------------
    | RENDER PREVIEW
    |--------------------------------------------------------------------------
    */

    function renderPreview()
    {
        previewList.innerHTML = '';

        const selected = document.querySelectorAll(
            '.checkSiswa:checked'
        );

        if (selected.length > 0) {

            previewBox.classList.remove('d-none');

        } else {

            previewBox.classList.add('d-none');

        }

        selected.forEach(item => {

            previewList.innerHTML += `
                <span class="badge bg-dark">
                    ${item.dataset.nama}
                </span>
            `;

        });
    }

</script>

@endsection