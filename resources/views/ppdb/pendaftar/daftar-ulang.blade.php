@extends('layouts.app')

@section('title','Daftar Ulang')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Daftar Ulang</h3>
                        <p class="mb-0 opacity-75">Kelola daftar ulang siswa baru</p>
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
                        Daftar Ulang Siswa Baru
                    </h5>

                    <span class="text-muted small">
                        Total:
                        {{ $data->count() }}
                        daftar ulang   
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
                        <th>Status Daftar Ulang</th>
                        <th>NIS</th>
                        <th>Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableDaftarUlang">

                    @foreach($data as $item)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->no_pendaftaran }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>
                            @if($item->status_daftar_ulang == 'sudah')
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Sudah
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Belum
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->siswa)
                                {{ $item->siswa->nis }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->siswa?->user)
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">
                                    <i class="bi bi-person-check me-1"></i>
                                    Akun Terdaftar
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                    <i class="bi bi-person-x me-1"></i>
                                    Akun Belum Terdaftar
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->status_daftar_ulang != 'sudah')

                            <form
                                action="{{ route('tu.ppdb.daftar-ulang.konfirmasi',$item) }}"
                                method="POST"
                            >

                            @csrf

                            <button
                                class="btn btn-success btn-sm"
                                onclick="return confirm('Konfirmasi daftar ulang?')"
                            >

                            <i class="bi bi-check-circle"></i>

                            Konfirmasi

                            </button>

                            </form>

                            @else

                            <span class="text-success">

                            <i class="bi bi-check-circle-fill"></i>

                            Selesai

                            </span>

                            @endif
                        </td>
                    </tr>

                    @endforeach

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
                '#tableDaftarUlang tr'
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