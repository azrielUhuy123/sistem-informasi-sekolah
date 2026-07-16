@extends('layouts.app')

@section('title', 'Kelas Saya')

@section('content')

{{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Manajemen Kelas Saya</h3>
                    <p class="mb-0 opacity-75">Kelola siswa dan jadwal kelas yang diajar</p>
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

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="80">
                                No
                            </th>

                            <th>
                                NIS
                            </th>

                            <th>
                                NISN
                            </th>

                            <th>
                                Nama Siswa
                            </th>
                            <th>
                                Tanggal Lahir
                            <th>
                                Jenis Kelamin
                            </th>

                            <th width="150">

                                Aksi

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($penempatan as $i => $p)

                            <tr>

                                <td>

                                    {{ $i + 1 }}

                                </td>

                                <td>

                                    {{ $p->siswa->nis }}

                                </td>

                                <td>

                                    {{ $p->siswa->nisn }}

                                </td>

                                <td>

                                    {{ $p->siswa->nama }}

                                </td>

                                <td>

                                    {{ $p->siswa->tanggal_lahir }}

                                </td>

                                <td>

                                    {{ $p->siswa->jenis_kelamin }}

                                </td>
                                <td class="d-flex gap-2">

                                    <a
                                        href="{{ route('guru.kelas-saya.show', $p->siswa->id) }}"
                                        class="btn btn-primary btn-sm"
                                    >

                                        Detail

                                    </a>

                                    <a
                                        href="{{ route('guru.raport.show', $p->siswa->id) }}"
                                        class="btn btn-success btn-sm"
                                    >

                                        Raport

                                    </a>

                                </td>
                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada siswa

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection