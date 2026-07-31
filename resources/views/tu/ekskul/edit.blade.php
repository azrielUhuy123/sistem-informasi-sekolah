@extends('layouts.app')

@section('title', 'Tambah Ekskul')

@section('content')

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Ekskul</h4>
            <p class="text-muted mb-0">Perbarui informasi kegiatan ekstrakurikuler</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('tu.ekskul.update', $ekskul) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Ekskul</label>
                        <input type="text" name="nama" class="form-control rounded-3" value="{{ old('nama', $ekskul->nama) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Guru Pembina</label>
                        <select name="guru_id" class="form-select">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}" @selected(old('guru_id', $ekskul->guru_id) == $g->id)>{{ $g->pegawai->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-select">
                            <option value="Senin" @selected(old('hari', $ekskul->hari) == 'Senin')>Senin</option>
                            <option value="Selasa" @selected(old('hari', $ekskul->hari) == 'Selasa')>Selasa</option>
                            <option value="Rabu" @selected(old('hari', $ekskul->hari) == 'Rabu')>Rabu</option>
                            <option value="Kamis" @selected(old('hari', $ekskul->hari) == 'Kamis')>Kamis</option>
                            <option value="Jumat" @selected(old('hari', $ekskul->hari) == 'Jumat')>Jumat</option>
                            <option value="Sabtu" @selected(old('hari', $ekskul->hari) == 'Sabtu')>Sabtu</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control rounded-3" value="{{ old('jam_mulai', $ekskul->jam_mulai) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control rounded-3" value="{{ old('jam_selesai', $ekskul->jam_selesai) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kuota</label>
                        <input type="number" name="kuota" class="form-control rounded-3" value="{{ old('kuota', $ekskul->kuota) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" @selected(old('status', $ekskul->status) == 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $ekskul->status) == 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" class="form-control rounded-3">{{ old('deskripsi', $ekskul->deskripsi) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.ekskul.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection