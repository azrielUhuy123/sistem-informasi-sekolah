@extends('layouts.app')

@section('title', 'Kelola Berita')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header d-flex justify-content-between">

        <h5 class="mb-0">
            Kelola Berita
        </h5>

        <button
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalTambah"
        >
            Tambah Berita
        </button>

    </div>

    <div class="card-body">

        <table class="table">

            <thead>

                <tr>

                    <th>Judul</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th width="150">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @foreach($beritas as $berita)

                <tr>

                    <td>
                        {{ $berita->judul }}
                    </td>

                    <td>

                        @if($berita->is_publish)

                            <span class="badge bg-success">
                                Publish
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Draft
                            </span>

                        @endif

                    </td>

                    <td>
                        {{ $berita->created_at->format('d M Y') }}
                    </td>

                    <td>

                        <button
                            class="btn btn-warning btn-sm"
                        >
                            Edit
                        </button>

                        <form
                            action="{{ route('tu.website.berita.destroy', $berita) }}"
                            method="POST"
                            class="d-inline"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm"
                            >
                                Hapus
                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        {{ $beritas->links() }}

    </div>

</div>
<div
    class="modal fade"
    id="modalTambah"
>

    <div class="modal-dialog modal-lg">

        <form
            action="{{ route('tu.website.berita.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="modal-content"
        >

            @csrf

            <div class="modal-header">

                <h5>
                    Tambah Berita
                </h5>

            </div>

            <div class="modal-body">

                <input
                    type="text"
                    name="judul"
                    class="form-control mb-3"
                    placeholder="Judul Berita"
                >

                <textarea
                    name="ringkasan"
                    class="form-control mb-3"
                    rows="3"
                    placeholder="Ringkasan"
                ></textarea>

                <textarea
                    name="isi"
                    class="form-control mb-3"
                    rows="6"
                    placeholder="Isi Berita"
                ></textarea>

                <input
                    type="file"
                    name="gambar"
                    class="form-control"
                >

            </div>

            <div class="modal-footer">

                <button
                    class="btn btn-primary"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection