@extends('layouts.app')

@section('title', 'Tambah Ekskul')

@section('content')

<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="mb-4">
                <h3 class="text-primary fw-bold text-center mb-1">Tambah Ekskul</h4>
                <p class="text-muted text-center">Tambahkan kegiatan ekstrakurikuler baru</p>
            </div>
            {{-- ALERT --}}            
 
            <x-alert.flash key="error" type="danger"/>
            <form action="{{ route('tu.ekskul.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Ekskul</label>
                        <input type="text" name="nama" class="form-control" 
                        oninput="this.value = this.value.replace(/\s+/g, ' ').replace(/(^|\s)\S/g, c => c.toUpperCase())"
                        required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Guru Pembina</label>
                        <select name="guru_id" class="form-control">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->id }}">{{ $g->pegawai->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-control">
                            <option>Senin</option>
                            <option>Selasa</option>
                            <option>Rabu</option>
                            <option>Kamis</option>
                            <option>Jumat</option>
                            <option>Sabtu</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kuota</label>
                        <input type="number" name="kuota" class="form-control" value="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-12 mb-5">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" class="form-control"></textarea>
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