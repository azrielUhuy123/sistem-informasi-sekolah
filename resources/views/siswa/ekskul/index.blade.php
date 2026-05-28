@extends('layouts.app')

@section('title', 'Ekskul')

@section('content')

<div class="container-fluid py-4">

    <div class="mb-4">

        <h3 class="fw-bold mb-1">

            Ekstrakurikuler

        </h3>

        <p class="text-muted mb-0">

            Daftar kegiatan ekstrakurikuler sekolah

        </p>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>No</th>
                            <th>Ekskul</th>
                            <th>Pembina</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Kuota</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($ekskul as $i => $e)

                            <tr>

                                <td>

                                    {{ $i + 1 }}

                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ $e->nama }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $e->deskripsi }}

                                    </small>

                                </td>

                                <td>

                                    {{ $e->guru->nama ?? '-' }}

                                </td>

                                <td>

                                    {{ $e->hari }}

                                </td>

                                <td>

                                    {{ $e->jam_mulai }}
                                    -
                                    {{ $e->jam_selesai }}

                                </td>

                                <td>

                                    {{ $e->kuota }}

                                </td>

                                <td>

                                    @if(in_array($e->id, $pendaftaran))

                                        <form
                                            action="{{ route('siswa.ekskul.batal', $e->id) }}"
                                            method="POST"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm">

                                                Batal

                                            </button>

                                        </form>

                                    @else

                                        <form
                                            action="{{ route('siswa.ekskul.daftar', $e->id) }}"
                                            method="POST"
                                        >

                                            @csrf

                                            <button class="btn btn-dark btn-sm">

                                                Daftar

                                            </button>

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada ekskul

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