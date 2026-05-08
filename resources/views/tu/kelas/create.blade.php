@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')

<style>
/* ——————————————————
   BUTTON STYLE
—————————————————— */
.btn-main {
    background: #4d5bd8;
    color: white !important;
    border: none;
    padding: 8px 18px;
    font-weight: 600;
    border-radius: 8px;
    transition: background .15s ease;
}
.btn-main:hover { background:#3c48b7; }
.btn-main:active { background:#3c48b7 !important; }

.btn-grey {
    background: #b7b7b7;
    color: #fff !important;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 8px;
}

/* ——————————————————
   CARD FORM
—————————————————— */
.card-input {
    border: 1px solid #dfe3f1;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 15px;
    background: #fff;
    opacity: 0;
    transform: translateY(10px);
    animation: fadeSlideIn .25s forwards;
}

@keyframes fadeSlideIn {
    to { opacity: 1; transform: translateY(0); }
}

.fade-out {
    animation: fadeSlideOut .25s forwards;
}
@keyframes fadeSlideOut {
    to { opacity:0; transform:translateY(-10px); }
}

/* Hapus form */
.remove-btn {
    float: right;
    cursor: pointer;
    color: #d03434;
    font-size: 22px;
    font-weight: bold;
}
.remove-btn:hover { color:#a40000; }

/* Duplikasi */
.duplicate-btn {
    cursor: pointer;
    background: #eeeeff;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 14px;
    margin-bottom: 10px;
    display: inline-block;
}
.duplicate-btn:hover { background:#d7d7ff; }

/* Dark Mode */
.dark-mode .card-input { background:#2f3240; border-color:#44485d; }
.dark-mode .remove-btn { color:#ff8a8a; }

.btn-back {
    background: #e6e6e6;
    color: #444 !important;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 10px;
    border: none;
    transition: 0.2s ease;
    text-decoration: none !important;  /* <— Tambahkan ini */
}

.btn-back:hover {
    background: #d4d4d4;
    color: #222 !important;
    transform: translateY(-1px);
    text-decoration: none !important;  /* <— Tambahkan ini */
}

.btn-back:active {
    background: #c8c8c8 !important;
    transform: scale(0.98);
    text-decoration: none !important;  /* <— Tambahkan ini */
}

.template-select {
    appearance: none;
    -moz-appearance: none;
    -webkit-appearance: none;
    max-width: 220px;
    margin-left: 10px;
    border-radius: 10px;
    padding: 8px 12px;
    border: 1px solid #d4d7e5;
    font-weight: 600;
    color: #444;
    background: #f8f9ff;
    transition: 0.2s;
}

.template-select:hover {
    background: #eef0ff;
    border-color: #c3c8f0;
}

.template-select:focus {
    border-color: #4d5bd8;
    box-shadow: 0 0 0 3px rgba(77,91,216,0.2);
}



</style>

<h2 class="mb-4 fw-bold">Tambah Data Kelas</h2>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Cek kembali data Anda:</strong>
    <ul class="mb-0">
        @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('tu.kelas.store') }}" method="POST">
    @csrf

    <div id="formContainer">

        {{-- Form Pertama --}}
        <div class="card-input" data-index="0">
            <h5><strong>Form Kelas 1</strong></h5>

            <label>Nama Kelas</label>
            <input type="text" name="kelas[0][nama_kelas]" class="form-control mb-2 kelas-nama">

            <label>Tingkat</label>
            <select name="kelas[0][tingkat]" class="form-select mb-2 tingkat-select" onchange="autoGenerateName(0)">
                <option value="">Pilih tingkat</option>
                @for($i=1; $i<=6; $i++)
                    <option value="{{ $i }}">{{ $i }} (SD)</option>
                @endfor
            </select>

            <label>Tahun Ajaran</label>
            <input type="text" name="kelas[0][tahun_ajaran]" class="form-control tahun" required>
        </div>

    </div>

    <button type="button" class="btn-main mt-2" onclick="addForm()">+ Tambah Form</button>

    <select id="templateSelector" class="template-select mt-2;">
        <option value="">Tambah lebih dari 2</option>
        <option value="2">Tambahkan 2 form</option>
        <option value="3">Tambahkan 3 form</option>
        <option value="5">Tambahkan 5 form</option>
    </select>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('tu.kelas.index') }}" class="btn-back">← Kembali</a>
        <button type="submit" class="btn-main">Simpan Semua</button>
    </div>

</form>
@endsection


@section('scripts')
<script>

let index = 1;

/* ——————————————————
   AUTO TAHUN AJARAN
—————————————————— */
function autoSetYear(input) {
    let y = new Date().getFullYear();
    input.value = `${y}/${y+1}`;
}

document.querySelectorAll('.tahun').forEach(t => autoSetYear(t));

/* ——————————————————
   AUTO GENERATE KELAS NAMA
—————————————————— */
function autoGenerateName(i) {
    const tingkat = document.querySelector(
        `[name="kelas[${i}][tingkat]"]`
    )?.value;

    const huruf = document
        .querySelectorAll('.card-input')
        [i]?.querySelector('.huruf-select')?.value;

    const nama = document.querySelector(
        `[name="kelas[${i}][nama_kelas]"]`
    );

    if (!tingkat || !huruf) return;

    nama.value = `Kelas ${tingkat}${huruf}`;
}


function renumberForms() {
    let forms = document.querySelectorAll(".card-input");
    let newIndex = 0;

    forms.forEach(f => {
        f.dataset.index = newIndex;

        // Update header
        let title = f.querySelector("h5 strong");
        if (title) title.innerText = `Form Kelas ${newIndex + 1}`;

        // Update name attributes (nama_kelas / tingkat / tahun_ajaran)
        f.querySelector(".kelas-nama").name = `kelas[${newIndex}][nama_kelas]`;
        f.querySelector(".tingkat-select").name = `kelas[${newIndex}][tingkat]`;
        f.querySelector(".tahun").name = `kelas[${newIndex}][tahun_ajaran]`;

        newIndex++;
    });

    index = newIndex;
}

/* ——————————————————
   ADD FORM DINAMIS
—————————————————— */
function addForm() {

    let html = `
    <div class="card-input" data-index="${index}">
        <span class="remove-btn" onclick="removeForm(${index})">✖</span>

        <span class="duplicate-btn" onclick="duplicateForm(${index})">⧉ Duplikasi Form Sebelumnya</span>

        <h5><strong>Form Kelas ?</strong></h5>

        <label>Nama Kelas</label>
        <input type="text" name="kelas[${index}][nama_kelas]" class="form-control mb-2 kelas-nama">

        <label>Tingkat</label>
        <select name="kelas[${index}][tingkat]" class="form-select mb-2 tingkat-select"
            onchange="autoGenerateName(${index})">
            <option value="">Pilih tingkat</option>
            @for($i=1; $i<=6; $i++)
                <option value="{{ $i }}">{{ $i }} (SD)</option>
            @endfor
        </select>

        <label>Tahun Ajaran</label>
        <input type="text" name="kelas[${index}][tahun_ajaran]" class="form-control tahun">
    </div>`;

    const container = document.getElementById("formContainer");
    container.insertAdjacentHTML("beforeend", html);

    autoSetYear(document.querySelector(`[name="kelas[${index}][tahun_ajaran]"]`));

    index++;
    updateTitles(); // hanya update judul
}


function updateTitles() {
    let forms = document.querySelectorAll(".card-input");
    let num = 1;

    forms.forEach(f => {
        let title = f.querySelector("h5 strong");
        if (title) title.innerText = `Form Kelas ${num++}`;
    });
}

/* ——————————————————
   REMOVE FORM (Fade-out)
—————————————————— */
function removeForm(i) {
    let f = document.querySelector(`.card-input[data-index="${i}"]`);
    if (!f) return;

    f.classList.add("fade-out");
    setTimeout(() => {
        f.remove();
        updateTitles(); // hanya update judul, bukan index
    }, 200);
}


/* ——————————————————
   DUPLICATE FORM
—————————————————— */
function duplicateForm(i) {
    let prev = document.querySelector(`.card-input[data-index="${i-1}"]`);
    let curr = document.querySelector(`.card-input[data-index="${i}"]`);

    if (!prev || !curr) return;

    curr.querySelector(`[name="kelas[${i}][tingkat]"]`).value =
        prev.querySelector(`[name="kelas[${i-1}][tingkat]"]`).value;

    curr.querySelector(`[name="kelas[${i}][tahun_ajaran]"]`).value =
        prev.querySelector(`[name="kelas[${i-1}][tahun_ajaran]"]`).value;

    autoGenerateName(i);
}


/* ——————————————————
   TEMPLATE QUICK ADD
—————————————————— */
document.getElementById('templateSelector').addEventListener('change', function() {
    let total = parseInt(this.value);
    if (!total) return;

    for (let i = 0; i < total; i++) addForm();
    this.value = "";
});
</script>
@endsection
