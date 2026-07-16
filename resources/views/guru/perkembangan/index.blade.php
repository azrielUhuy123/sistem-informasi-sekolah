@extends('layouts.app')

@section('title', 'Perkembangan Siswa')

@section('content')

<div class="container-fluid py-3">

        {{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Manajemen Perkembangan Siswa</h3>
                        <p class="mb-0 opacity-75">Kelola perkembangan siswa dalam setiap mata pelajaran</p>
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

        @if(isset($kelasPengampu) && $kelasPengampu->count())

            <form method="GET">

                <select
                    name="kelas_id"
                    class="form-select"
                    onchange="this.form.submit()"
                    style="min-width:220px">

                    @foreach($kelasPengampu as $item)

                        <option
                            value="{{ $item->kelas_id }}"
                            {{ request('kelas_id', $kelas->id) == $item->kelas_id ? 'selected' : '' }}>

                            {{ $item->kelas->nama_kelas }}

                        </option>

                    @endforeach

                </select>

            </form>

        @endif

    </div>

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
                                Nama Siswa
                            </th>

                            <th width="220">
                                Status
                            </th>

                            <th width="180">
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
                                    {{ $p->siswa->nama }}
                                </td>

                                <td>

                                    @if($p->status_perkembangan == 'Lengkap')

                                        <span class="badge bg-success">
                                            Lengkap
                                        </span>

                                    @elseif($p->status_perkembangan == 'Belum Lengkap')

                                        <span class="badge bg-warning text-dark">
                                            Belum Lengkap
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Belum Ada
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <button
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modal-{{ $p->siswa->id }}">

                                        Isi / Edit

                                    </button>

                                </td>

                            </tr>

                            {{-- MODAL --}}
                            <div
                                class="modal fade"
                                id="modal-{{ $p->siswa->id }}"
                                tabindex="-1">

                                <div class="modal-dialog modal-lg modal-dialog-scrollable">

                                    <div class="modal-content">

                                        <form
                                            action="{{ route('guru.perkembangan.store') }}"
                                            method="POST">

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    {{ $p->siswa->nama }}

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                                </button>

                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Karakter
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][karakter]"
                                                        class="form-control"
                                                        rows="3">{{ $p->perkembangan->karakter ?? '' }}</textarea>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Sosial
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][sosial]"
                                                        class="form-control"
                                                        rows="3">{{ $p->perkembangan->sosial ?? '' }}</textarea>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Disiplin
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][disiplin]"
                                                        class="form-control"
                                                        rows="3">{{ $p->perkembangan->disiplin ?? '' }}</textarea>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Kebersihan
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][kebersihan]"
                                                        class="form-control"
                                                        rows="3">{{ $p->perkembangan->kebersihan ?? '' }}</textarea>

                                                </div>

                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Catatan Umum
                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][catatan]"
                                                        class="form-control"
                                                        rows="4">{{ $p->perkembangan->catatan ?? '' }}</textarea>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">

                                                    Tutup

                                                </button>

                                                <button
                                                    class="btn btn-success">

                                                    Simpan

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center text-muted py-4">

                                    Tidak ada data siswa.

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