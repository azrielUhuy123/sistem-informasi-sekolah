@extends('layouts.tu')

@section('title', 'Detail Rombel')

@section('content')
<div class="rombel-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">
                Rombel {{ $rombel->nama_kelas }}
            </h4>
            <small class="text-muted">
                Angkatan {{ $rombel->kelasGrup->angkatan->tahun }}
            </small>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('tu.kelas.rombel.index', $rombel->kelas_grup_id) }}"
            class="btn btn-secondary btn-sm">
                Kembali
            </a>

            

            <a href="#"
            class="btn  btn-warning btn-sm">
                Export Siswa
            </a>
            <button class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambahSiswa">
                Tambah Siswa
            </button>
            <a href="{{ route('tu.penempatan-siswa.create', ['kelas_id' => $rombel->id]) }}"
            class="btn btn-primary btn-sm mb-3">
                + Tambah Siswa ke Rombel
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Statistik Rombel</h6>

            <div class="row mb-4">

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Total Siswa</h6>
                            <h3 class="fw-bold">{{ $totalSiswa }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Laki-laki</h6>
                            <h3 class="fw-bold text-primary">{{ $totalLaki }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Perempuan</h6>
                            <h3 class="fw-bold text-danger">{{ $totalPerempuan }}</h3>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h6 class="fw-semibold mb-3">Komposisi Gender</h6>

                    {{-- Laki-laki --}}
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <small>Laki-laki</small>
                            <small>{{ $persenLaki }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary"
                                role="progressbar"
                                style="width: {{ $persenLaki }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Perempuan --}}
                    <div>
                        <div class="d-flex justify-content-between">
                            <small>Perempuan</small>
                            <small>{{ $persenPerempuan }}%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger"
                                role="progressbar"
                                style="width: {{ $persenPerempuan }}%">
                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Daftar Siswa</h6>
            <small class="text-muted">
                {{ $siswa->total() }} siswa
            </small>
            <div id="tableSiswaWrapper">
                @if($siswa->count())
                    <table class="table table-hover table-sm align-middle rombel-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIS</th>
                                <th>NISN</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Wali</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa as $item)
                                <tr>
                                    <td>{{ $item->siswa->nama }}</td>
                                    <td>{{ $item->siswa->nis }}</td>
                                    <td>{{ $item->siswa->nisn ?? '-' }}</td>

                                    <td>
                                        @if($item->siswa->jenis_kelamin == 'L')
                                            <span class="badge bg-primary">Laki-laki</span>
                                        @else
                                            <span class="badge bg-danger">Perempuan</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $item->siswa->tanggal_lahir
                                            ? \Carbon\Carbon::parse($item->siswa->tanggal_lahir)->format('d/m/Y')
                                            : '-' }}
                                    </td>

                                    <td>{{ $item->siswa->nama_wali ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('tu.siswa.detail', $item->id) }}"
                                            class="btn btn-sm btn-outline-info">
                                                Detail
                                            </a>

                                            <a href="{{ route('tu.siswa.edit', $item->id) }}"
                                            class="btn btn-sm btn-outline-warning">
                                                Edit
                                            </a>
                                            <form action="{{ route('tu.penempatan-siswa.keluarkan', $item->id) }}"
                                                method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Keluarkan siswa dari rombel?')">
                                                    Keluarkan
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $siswa->links() }}
                @else
                    <div class="text-muted text-center py-4">
                        Belum ada siswa di rombel ini
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahSiswa" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form id="formTambahSiswa" method="POST" action="{{ route('tu.siswa.store') }}">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $rombel->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Tambah Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- ================= DATA SISWA ================= --}}
                        <h6 class="fw-semibold mb-3">Data Siswa</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis" class="form-control fast-input" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NISN</label>
                                <input type="text" name="nisn" class="form-control fast-input">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control fast-input" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control fast-input" max="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control fast-input">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control fast-input" rows="2"></textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ================= DATA WALI ================= --}}
                        <h6 class="fw-semibold mb-3">Data Wali</h6>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Wali</label>
                                <input type="text" name="nama_wali" class="form-control fast-input">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Telepon Wali</label>
                                <input type="text" name="telepon_wali" class="form-control fast-input">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit"
                                id="btnSaveSiswa"
                                class="btn btn-primary">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <div id="successOverlay"
        style="
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        ">

        <div class="card shadow-lg border-0 text-center p-4"
            style="width: 300px; border-radius: 16px;">

            <div class="mb-3">
                <div style="
                    width: 70px;
                    height: 70px;
                    background: #198754;
                    color: white;
                    font-size: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto;
                ">
                    ✓
                </div>
            </div>

            <h5 class="fw-bold mb-1">Berhasil!</h5>
            <p class="text-muted mb-0" id="successMessage">
                Data berhasil disimpan
            </p>

        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('formTambahSiswa');
        const btn  = document.getElementById('btnSaveSiswa');

        form.addEventListener('submit', function(e) {

            e.preventDefault();

            btn.innerText = "Menyimpan...";
            btn.disabled = true;

            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            })
            .then(async res => {

                if (!res.ok) {
                    const error = await res.json();
                    throw error;
                }

                return res.json();
            })
            .then(data => {

                showSuccessPopup("Siswa berhasil ditambahkan");

                const modal = bootstrap.Modal.getInstance(
                    document.getElementById('modalTambahSiswa')
                );
                modal.hide();

                setTimeout(() => location.reload(), 800);
            })
            .catch(err => {

                if (err.errors) {
                    const firstError = Object.values(err.errors)[0][0];
                    showToast(firstError, "error");
                } else {
                    showToast("Terjadi kesalahan server", "error");
                }

            })
            .finally(() => {
                btn.innerText = "Simpan";
                btn.disabled = false;
            });
        });
    });


    // RELOAD TABLE FUNCTION
    function reloadRombelTable() {
        fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTable = doc.querySelector('#tableSiswaWrapper');
                const currentTable = document.querySelector('#tableSiswaWrapper');

                if (newTable && currentTable) {
                    currentTable.innerHTML = newTable.innerHTML;
                }
            });
    }
    function showSuccessPopup(message = "Data berhasil disimpan") {

        const overlay = document.getElementById("successOverlay");
        const text = document.getElementById("successMessage");

        text.innerText = message;

        overlay.style.display = "flex";

        setTimeout(() => {
            overlay.style.display = "none";
            reloadRombelTable();
        }, 1200);
    }

    </script>

</div>
@endsection
