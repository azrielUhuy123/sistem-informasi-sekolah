@extends('layouts.app')

@section('title', 'Tambah Ekskul')

@section('content')

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="mb-4"> 
                <h3 class="text-primary fw-bold text-center mb-1">Edit Ekskul</h4>
                <p class="text-muted text-center">Perbarui informasi kegiatan ekstrakurikuler</p>
            </div>

            {{-- ALERT --}}            
 
            <x-alert.flash key="error" type="danger"/>
            <form action="{{ route('tu.ekskul.update', $ekskul) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Ekskul</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $ekskul->nama) }}"
                        oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Guru Pembina</label>
                        <select name="guru_id" class="form-control">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}" @selected(old('guru_id', $ekskul->guru_id) == $g->id)>{{ $g->pegawai->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-control">
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
                        <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai', $ekskul->jam_mulai) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai', $ekskul->jam_selesai) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kuota</label>
                        <input type="number" name="kuota" class="form-control" value="{{ old('kuota', $ekskul->kuota) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="aktif" @selected(old('status', $ekskul->status) == 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected(old('status', $ekskul->status) == 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-12 mb-5">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" class="form-control">{{ old('deskripsi', $ekskul->deskripsi) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('tu.ekskul.index') }}" class="btn btn-outline-secondary rounded-3">
                        <i class="bi bi-chevron-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3">
                        Simpan <i class="bi bi-save ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection