@extends('layouts.app')
@section('title', 'Buat Forum')
@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">            
        <div class="card-body">
            <div class="mb-4">
                <h3 class="text-primary fw-bold text-center mb-1">
                    Buat Forum
                </h3>
                <p class="text-muted text-center">
                    Buat forum untuk diskusi dan pembelajaran
                </p>
            </div>

            {{-- ALERT --}}
            <x-alert.flash key="error" type="danger"/>
            <form
                method="POST"
                enctype="multipart/form-data"
                action="{{ route('guru.e-learning.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-8 mb-4">
                        <label class="form-label">
                            <i class="bi bi-bookmark-star me-2"></i>
                            Judul Forum
                        </label>
                        <input
                            type="text"
                            name="judul"
                            class="form-control"
                            value="{{ old('judul') }}"
                            placeholder="Contoh : Pecahan dan Desimal"
                            required>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label class="form-label">
                            <i class="bi bi-calendar-event me-2"></i>
                            Deadline
                        </label>
                        <input
                            type="datetime-local"
                            name="deadline"
                            value="{{ old('deadline') }}"
                            class="form-control">
                    </div>                
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-diagram-3 me-2"></i>
                        Target Forum
                    </label>
                    <select
                        name="target"
                        id="target"
                        class="form-select">
                        <option value="">
                            -- Pilih Target Forum --
                        </option>
                        @if($waliKelas)
                            <optgroup label="Forum Wali Kelas">
                                @foreach($mapelWali as $mapel)
                                <option value="wali_{{ $mapel->id }}">
                                    {{ $waliKelas->kelas->nama_kelas_label }}
                                    -
                                    {{ $mapel->nama_mapel }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endif
                        @if($pengampu->count())
                            <optgroup label="Forum Guru Mata Pelajaran">
                                @foreach($pengampu as $item)
                                    <option value="pengampu_{{ $item->id }}">
                                        {{ $item->mapel->nama_mapel }}
                                        -
                                        {{ $item->kelas->nama_kelas_label }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>                    
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-journal-text me-2"></i>
                        Konten Materi
                    </label>
                    <textarea
                        name="konten"
                        id="editor">{{ old('konten') }}</textarea>
                    <div class="form-text mt-2">
                        Tambahkan teks, gambar, video, tabel, tautan, maupun format pembelajaran lainnya menggunakan editor di bawah.
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-cloud-arrow-up me-2"></i>
                        Lampiran Materi
                    </label>
                    <div class="upload-box">
                        <input
                            type="file"
                            name="lampiran[]"
                            class="form-control modern-input"
                            multiple>
                        <div class="upload-info">
                            <i class="bi bi-file-earmark-arrow-up"></i>
                            <span>
                                PDF, DOCX, PPTX, XLSX, JPG, PNG, MP3, MP4, ZIP
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a
                        href="{{ route('guru.e-learning.index') }}"
                        class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-chevron-left me-1"></i>
                        Kembali
                    </a>
                    <button
                        type="submit"
                        class="btn btn-primary rounded-3">
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>
                </div>
            </form>            
        </div>
    </div>
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
<style>

.hero-icon{
    width:90px;
    height:90px;
    border-radius:22px;
    display:flex;
    justify-content:center;
    align-items:center;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(10px);
    font-size:40px;
}
.hero-label{
    font-size:14px;
    letter-spacing:3px;
    text-transform:uppercase;
    opacity:.9;
    font-weight:600;
}
.hero-title{
    font-size:42px;
    font-weight:800;
    color:#fff;
    margin:8px 0;
}
.hero-subtitle{
    font-size:17px;
    max-width:620px;
    opacity:.95;
}
.hero-stat{
    display:flex;
    flex-direction:column;
    gap:18px;
}
.hero-box{
    display:flex;
    align-items:center;
    gap:18px;
    background:rgba(255,255,255,.13);
    backdrop-filter:blur(12px);
    border-radius:18px;
    padding:18px;
}
.hero-box i{
    font-size:28px;
}
.hero-box h3{
    margin:0;
    color:#fff;
    font-size:32px;
    font-weight:800;
}
.hero-box span{
    opacity:.9;
}
/*==================================================
CKEDITOR
==================================================*/
.ck-editor__editable{
    min-height:240px;
}
.ck.ck-editor{
    border-radius:18px;
}
.ck.ck-editor__main>.ck-editor__editable{
    border-radius:0 0 18px 18px!important;
}
.ck.ck-toolbar{
    border-radius:18px 18px 0 0!important;
}

/*==================================================
TEXT
==================================================*/
.form-text{
    color:#64748b;
    line-height:1.8;
}
/*==================================================
RESPONSIVE
==================================================*/
@media(max-width:992px){
    .elearning-hero{
        padding:35px;
    }
    .hero-title{
        font-size:34px;
    }
    .hero-stat{
        margin-top:30px;
    }
}
@media(max-width:768px){

    .hero-title{
        font-size:28px;
    }
    .hero-icon{
        width:70px;
        height:70px;
        font-size:30px;
    }
    .hero-subtitle{
        font-size:15px;
    }
    .hero-box{
        padding:15px;
    }
    .hero-box h3{
        font-size:26px;
    }
}
</style>
@endsection
