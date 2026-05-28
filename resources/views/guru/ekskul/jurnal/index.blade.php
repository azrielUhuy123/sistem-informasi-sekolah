@extends('layouts.app')

@section('title', 'Jurnal Kegiatan')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Jurnal Kegiatan Ekskul

        </h4>

        <small class="text-muted">

            {{ $ekskul->nama }}

        </small>

    </div>

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form
                action="{{ route('guru.ekskul.jurnal.store') }}"
                method="POST"
            >

                @csrf

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">

                            Tanggal

                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="col-md-9">

                        <label class="form-label">

                            Materi

                        </label>

                        <input
                            type="text"
                            name="materi"
                            class="form-control"
                            placeholder="Contoh: Latihan Dribbling Dasar"
                            required
                        >

                    </div>

                    <div class="col-12">

                        <label class="form-label">

                            Kegiatan

                        </label>

                        <textarea
                            name="kegiatan"
                            rows="4"
                            class="form-control"
                            required
                        ></textarea>

                    </div>

                    <div class="col-12">

                        <label class="form-label">

                            Catatan

                        </label>

                        <textarea
                            name="catatan"
                            rows="3"
                            class="form-control"
                        ></textarea>

                    </div>

                </div>

                <div class="mt-4 text-end">

                    <button class="btn btn-dark">

                        Simpan Jurnal

                    </button>

                </div>

            </form>

        </div>

    </div>
    <div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table align-middle">

                <thead class="table-light">

                    <tr>

                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Materi</th>
                        <th>Kegiatan</th>
                        <th>Catatan</th>
                        <th width="120">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($jurnal as $item)

                        <tr>

                            <td>

                                {{ $jurnal->firstItem() + $loop->index }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}

                            </td>

                            <td>

                                {{ $item->materi }}

                            </td>

                            <td>

                                {{ $item->kegiatan }}

                            </td>

                            <td>

                                {{ $item->catatan }}

                            </td>

                            <td>

                                <form
                                    action="{{ route('guru.ekskul.jurnal.destroy', $item->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus jurnal?')"
                                    >

                                        Hapus

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >

                                Belum ada jurnal kegiatan

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $jurnal->links() }}

        </div>

    </div>

</div>

</div>

@endsection