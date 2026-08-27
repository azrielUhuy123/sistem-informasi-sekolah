@extends('layouts.app')

@section('title', 'Pindah Kelas')

@section('content')

<div class="container-fluid py-4">

    {{-- STATUS --}}
    @if($tahunAktif->status !== 'aktif')
        <div class="alert alert-warning">
            Mutasi siswa hanya dapat dilakukan pada tahun ajaran aktif.
        </div>
    @endif

    <div class="card border-0 shadow-sm">

        <div class="card-body">
            <div class="mb-4">
                <h4 class="text-primary fw-bold text-center mb-1">Pindah Kelas Siswa</h4>
                <p class="text-muted text-center">
                    Tahun Ajaran :
                    <strong>{{ $tahunAktif->tahun }}</strong>
                </p>
            </div>
            {{-- ALERT --}}            

            <x-alert.flash key="error" type="danger"/>

            <form
                action="{{ route('tu.penempatan-siswa.update', $penempatan) }}"
                method="POST"
                {{ $tahunAktif->status !== 'aktif' ? 'onsubmit=return false;' : '' }}
            >

                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">

                    <label class="form-label">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $penempatan->siswa->nama }}"
                        readonly
                    >

                </div>

                {{-- Kelas sekarang --}}
                <div class="mb-3">

                    <label class="form-label">
                        Kelas Saat Ini
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $penempatan->kelas->nama_kelas_label }}"
                        readonly
                    >

                </div>

                {{-- Kelas tujuan --}}
                <div class="mb-5">

                    <label class="form-label">
                        Pindah ke Kelas
                    </label>

                    <select
                        name="kelas_id"
                        class="form-control"
                        required
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >

                        <option value="">
                            -- Pilih Kelas --
                        </option>

                        @foreach($kelas as $k)

                            @continue($k->id == $penempatan->kelas_id)

                            <option
                                value="{{ $k->id }}"
                                @selected(old('kelas_id') == $k->id)
                            >
                                {{ $k->nama_kelas_label }}
                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Pilih rombel tujuan untuk memindahkan siswa.
                    </small>

                </div>

                {{-- ACTION --}}
                <div class="d-flex justify-content-between align-items-center mt-4">

                    <a
                        href="{{ route('tu.siswa.index') }}"
                        class="btn btn-outline-secondary rounded-3"
                    >
                        <i class="bi bi-chevron-left me-1"></i>
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-3 btn-confirm"
                        data-title="Perbarui Kelas Siswa"
                        data-message="Penempatan Kelas Siswa akan diperbarui."
                        data-button="btn-warning"
                        {{ $tahunAktif->status !== 'aktif' ? 'disabled' : '' }}
                    >
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection