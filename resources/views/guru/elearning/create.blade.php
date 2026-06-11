@extends('layouts.app')

@section('title', 'Buat Forum')

@section('content')

<div class="container-fluid py-4">

```
<div class="row justify-content-center">

    <div class="col-lg-10">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h4 class="fw-bold mb-0">
                    Buat Forum Pembelajaran
                </h4>

            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">

                        <div class="fw-semibold mb-2">
                            Terdapat kesalahan:
                        </div>

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>
                @endif

                <form
                    method="POST"
                    enctype="multipart/form-data"
                    action="{{ route('guru.e-learning.store') }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-8 mb-3">

                            <label class="form-label fw-semibold">

                                Judul Forum

                            </label>

                            <input
                                type="text"
                                name="judul"
                                class="form-control"
                                value="{{ old('judul') }}"
                                placeholder="Contoh: Pecahan dan Desimal"
                                required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-semibold">

                                Deadline

                            </label>

                            <input
                                type="datetime-local"
                                name="deadline"
                                value="{{ old('deadline') }}"
                                class="form-control">

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Kelas

                            </label>

                            <select
                                name="kelas_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Pilih Kelas
                                </option>

                                @foreach($kelas as $item)

                                    <option
                                        value="{{ $item->id }}"
                                        @selected(old('kelas_id') == $item->id)>

                                        {{ $item->nama_kelas }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">

                                Mata Pelajaran

                            </label>

                            <select
                                name="mapel_id"
                                class="form-select"
                                required>

                                <option value="">
                                    Pilih Mata Pelajaran
                                </option>

                                @foreach($mapel as $item)

                                    <option
                                        value="{{ $item->id }}"
                                        @selected(old('mapel_id') == $item->id)>

                                        {{ $item->nama_mapel }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Konten Materi

                        </label>

                        <textarea
                            name="konten"
                            id="editor">{{ old('konten') }}</textarea>

                        <small class="text-muted">

                            Anda dapat menambahkan teks,
                            gambar, tabel, link, dan format lainnya.

                        </small>

                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-semibold">

                            Lampiran Materi

                        </label>

                        <input
                            type="file"
                            name="lampiran[]"
                            class="form-control"
                            multiple>

                        <small class="text-muted">

                            Format yang disarankan:
                            PDF, DOCX, PPTX, XLSX,
                            JPG, PNG, MP3, MP4, ZIP.

                        </small>

                    </div>

                    <div
                        class="d-flex justify-content-end gap-2">

                        <a
                            href="{{ route('guru.e-learning.index') }}"
                            class="btn btn-light">

                            Batal

                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-save me-1"></i>

                            Simpan Forum

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
```

</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>

class UploadAdapter {

    constructor(loader) {
        this.loader = loader;
    }

    upload() {

        return this.loader.file
        .then(file => {

            return new Promise(
                (resolve, reject) => {

                let data = new FormData();

                data.append(
                    'upload',
                    file
                );

                fetch(
                    "{{ route('guru.e-learning.upload-image') }}",
                    {
                        method: "POST",

                        headers: {
                            "X-CSRF-TOKEN":
                                "{{ csrf_token() }}"
                        },

                        body: data
                    }
                )

                .then(response =>
                    response.json()
                )

                .then(result => {

                    resolve({
                        default:
                            result.url
                    });

                })

                .catch(error => {

                    reject(error);

                });

            });

        });

    }

    abort() {

    }

}

ClassicEditor

.create(
    document.querySelector('#editor')
)

.then(editor => {

    editor.plugins
    .get('FileRepository')
    .createUploadAdapter =
        loader =>
            new UploadAdapter(loader);

})

.catch(error => {

    console.error(error);

});

</script>

@endsection
