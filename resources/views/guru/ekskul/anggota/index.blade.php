@extends('layouts.app')

@section('title', 'Anggota Ekskul')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Pendaftaran Anggota Ekskul

        </h3>

        <p class="text-muted mb-0">

            {{ $ekskul->nama }}

        </p>

    </div>

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