@extends('layouts.app')

@section('title', 'Buat Forum')

@section('content')

<<div class="container-fluid py-4">

    <div class="elearning-hero mb-5">

        <div class="row align-items-center">

            <div class="col-lg-8">

                <div class="d-flex align-items-center gap-4">

                    <div class="hero-icon">

                        <i class="bi bi-journal-richtext"></i>

                    </div>

                    <div>

                        <div class="hero-label">
                            E-Learning Guru
                        </div>

                        <h1 class="hero-title">
                            Buat Forum Pembelajaran
                        </h1>

                        <p class="hero-subtitle">

                            Bagikan materi, tugas, video, maupun dokumen pembelajaran kepada siswa dalam satu forum yang rapi dan mudah diakses.

                        </p>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="hero-stat">

                    <div class="hero-box">

                        <i class="bi bi-cloud-upload"></i>

                        <div>

                            <h3>∞</h3>

                            <span>Upload Materi</span>

                        </div>

                    </div>

                    <div class="hero-box">

                        <i class="bi bi-people-fill"></i>

                        <div>

                            <h3>1</h3>

                            <span>Forum Baru</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row justify-content-center">

    <div class="col-xl-11 col-12">

        <div class="card form-card border-0 shadow-sm">

            <div class="card-header form-header">

                <h4 class="fw-bold mb-0">

                    <i class="bi bi-pencil-square me-2 text-primary"></i>

                    Informasi Forum

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

                        <div class="col-lg-8 mb-4">

                            <label class="form-label modern-label">

                                <i class="bi bi-bookmark-star me-2"></i>

                                Judul Forum

                            </label>

                            <input
                                type="text"
                                name="judul"
                                class="form-control modern-input"
                                value="{{ old('judul') }}"
                                placeholder="Contoh : Pecahan dan Desimal"
                                required>

                        </div>

                        <div class="col-lg-4 mb-4">

                            <label class="form-label modern-label">

                                <i class="bi bi-calendar-event me-2"></i>

                                Deadline

                            </label>

                            <input
                                type="datetime-local"
                                name="deadline"
                                value="{{ old('deadline') }}"
                                class="form-control modern-input">

                        </div>

                    <div class="row">

                        <div class="mb-4">

                            <label class="form-label modern-label">

                                <i class="bi bi-diagram-3 me-2"></i>

                                Target Forum

                            </label>

                            <select
                                name="target"
                                id="target"
                                class="form-select modern-input">

                                <option value="">
                                    -- Pilih Target Forum --
                                </option>

                                @if($waliKelas)

                                <optgroup label="Forum Wali Kelas">

                                    @foreach($mapelWali as $mapel)

                                    <option value="wali_{{ $mapel->id }}">

                                        {{ $waliKelas->kelas->nama_kelas }}
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
                                        {{ $item->kelas->nama_kelas }}

                                    </option>

                                    @endforeach

                                </optgroup>

                                @endif

                            </select>

                        </div>

                    </div>

                    <div class="mb-4">

                        <label class="form-label modern-label">

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

                        <label class="form-label modern-label">

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

/*==================================================
CREATE FORUM
==================================================*/

.form-card{

    border-radius:28px;

    overflow:hidden;

    box-shadow:0 18px 45px rgba(15,23,42,.08)!important;

}

.form-header{

    background:#fff;

    padding:22px 30px;

    border-bottom:1px solid #edf2f7;

}

.card-body{

    padding:28px 32px;

}

/*==================================================
HERO
==================================================*/

.elearning-hero{

    background:linear-gradient(
        135deg,
        #2563eb,
        #4f46e5,
        #7c3aed
    );

    border-radius:28px;

    padding:45px;

    color:#fff;

    position:relative;

    overflow:hidden;

    box-shadow:0 20px 45px rgba(37,99,235,.22);

}

.elearning-hero::before{

    content:"";

    position:absolute;

    width:320px;

    height:320px;

    background:rgba(255,255,255,.08);

    border-radius:50%;

    top:-120px;

    right:-120px;

}

.elearning-hero::after{

    content:"";

    position:absolute;

    width:220px;

    height:220px;

    background:rgba(255,255,255,.05);

    border-radius:50%;

    bottom:-80px;

    left:-80px;

}

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
LABEL
==================================================*/

.modern-label{

    font-weight:700;

    margin-bottom:10px;

    color:#0f172a;

    display:flex;

    align-items:center;

}

/*==================================================
INPUT
==================================================*/

.modern-input{

    border-radius:16px;

    border:1px solid #dbe4f0;

    min-height:56px;

    padding:15px 18px;

    font-size:15px;

    transition:.3s;

    background:#fff;

}

.modern-input:focus{

    border-color:#2563eb;

    box-shadow:0 0 0 .2rem rgba(37,99,235,.15);

}

/*==================================================
SELECT
==================================================*/

.form-select.modern-input{

    cursor:pointer;

}

/*==================================================
UPLOAD
==================================================*/

.upload-box{

    border:2px dashed #cbd5e1;

    border-radius:18px;

    padding:24px;

    background:#f8fafc;

    transition:.3s;

}

.upload-box:hover{

    border-color:#2563eb;

    background:#eff6ff;

}

.upload-info{

    margin-top:15px;

    display:flex;

    align-items:center;

    gap:10px;

    color:#64748b;

    font-size:14px;

}

.upload-info i{

    font-size:20px;

    color:#2563eb;

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
BUTTON
==================================================*/

.btn{

    border-radius:14px;

    font-weight:700;

    padding:12px 22px;

}

.btn-primary{

    background:#2563eb;

    border:none;

    box-shadow:0 12px 28px rgba(37,99,235,.25);

}

.btn-primary:hover{

    background:#1d4ed8;

    transform:translateY(-2px);

}

.btn-light{

    background:#f8fafc;

    border:1px solid #e2e8f0;

}

.btn-light:hover{

    background:#eef2ff;

}

/*==================================================
ALERT
==================================================*/

.alert{

    border:none;

    border-radius:18px;

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

    .card-body{

        padding:22px;

    }

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

    .btn{

        width:100%;

    }

}

</style>

@endsection
