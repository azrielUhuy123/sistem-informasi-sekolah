@extends('layouts.app')

@section('title','Data Diterima')

@section('content')

<div class="container-fluid">

{{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Data Pendaftar Diterima</h3>
                        <p class="mb-0 opacity-75">Kelola data pendaftar yang diterima</p>
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


    <div class="card border-0 shadow-sm">

        {{-- TOP BAR --}}

        <div
            class="card-header bg-light border-0 p-4"
        >

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3"
            >

                <div>

                    <h5 class="fw-semibold mb-1">
                        Daftar Pendaftar Diterima
                    </h5>

                    <span class="text-muted small">
                        Total:
                        {{ $data->count() }}
                        pendaftar diterima   
                    </span>

                </div>

                <div style="max-width: 280px; width:100%;">

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control rounded-3"
                        placeholder="Cari pendaftar..."
                    >

                </div>

            </div>

        </div>

        <div class="card-body">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>No Pendaftaran</th>
                            <th>Nama</th>
                            <th>Tanggal Daftar</th>
                            <th>NIS</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody id="tableTerima">

                        @forelse($data as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                {{ $item->no_pendaftaran }}
                            </td>

                            <td>
                                {{ $item->nama }}
                            </td>

                            <td>
                                {{ $item->tanggal_pendaftaran }}
                            </td>

                            <td>
                                {{ $item->siswa?->nis }}
                            </td>

                            <td>

                                <a href="{{ route('tu.ppdb.pendaftar.show', $item) }}"
                                    class="btn btn-sm btn-dark">
                                        Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="6" class="text-center">

                                Tidak ada data

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>



            {{ $data->links() }}

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
                '#tableTerima tr'
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