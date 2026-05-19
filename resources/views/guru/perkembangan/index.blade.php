@extends('layouts.app')

@section('title', 'Perkembangan Siswa')

@section('content')

<div class="container-fluid py-3">

    <div class="mb-4">

        <h4 class="fw-bold mb-1">

            Perkembangan Siswa

        </h4>

        <small class="text-muted">

            Kelas {{ $kelas->nama_kelas }}

        </small>

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

                        @foreach($penempatan as $i => $p)

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
                                        data-bs-target="#modal-{{ $p->siswa->id }}"
                                    >

                                        Isi / Edit

                                    </button>

                                </td>

                            </tr>

                            {{-- MODAL --}}
                            <div
                                class="modal fade"
                                id="modal-{{ $p->siswa->id }}"
                                tabindex="-1"
                            >

                                <div class="modal-dialog modal-lg modal-dialog-scrollable">

                                    <div class="modal-content">

                                        <form
                                            action="{{ route('guru.perkembangan.store') }}"
                                            method="POST"
                                        >

                                            @csrf

                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    {{ $p->siswa->nama }}

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>

                                            <div class="modal-body">

                                                {{-- KARAKTER --}}
                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Karakter

                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][karakter]"
                                                        class="form-control"
                                                        rows="3"
                                                    >{{ $p->perkembangan->karakter ?? '' }}</textarea>

                                                </div>

                                                {{-- SOSIAL --}}
                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Sosial

                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][sosial]"
                                                        class="form-control"
                                                        rows="3"
                                                    >{{ $p->perkembangan->sosial ?? '' }}</textarea>

                                                </div>

                                                {{-- DISIPLIN --}}
                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Disiplin

                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][disiplin]"
                                                        class="form-control"
                                                        rows="3"
                                                    >{{ $p->perkembangan->disiplin ?? '' }}</textarea>

                                                </div>

                                                {{-- KEBERSIHAN --}}
                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Kebersihan

                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][kebersihan]"
                                                        class="form-control"
                                                        rows="3"
                                                    >{{ $p->perkembangan->kebersihan ?? '' }}</textarea>

                                                </div>

                                                {{-- CATATAN --}}
                                                <div class="mb-3">

                                                    <label class="form-label">

                                                        Catatan Umum

                                                    </label>

                                                    <textarea
                                                        name="perkembangan[{{ $p->siswa->id }}][catatan]"
                                                        class="form-control"
                                                        rows="4"
                                                    >{{ $p->perkembangan->catatan ?? '' }}</textarea>

                                                </div>

                                            </div>

                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >

                                                    Tutup

                                                </button>

                                                <button
                                                    class="btn btn-success"
                                                >

                                                    Simpan

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection