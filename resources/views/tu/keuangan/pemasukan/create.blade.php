@extends('layouts.tu')

@section('title','Tambah Pemasukan')

@section('content')

<h1 class="mb-4">Tambah Pemasukan</h1>

<div class="card">
    <div class="card-body">

        <form action="{{ route('tu.keuangan.pemasukan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Sumber Dana</label>
                <input type="text" name="sumber_dana" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Periode Anggaran</label>
                <input type="text" name="periode_anggaran" class="form-control"
                       placeholder="BOS Tahap 1 2025" required>
            </div>

            <div class="mb-3">
                <label>Nominal</label>
                <input type="number" name="nominal" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Dokumen</label>
                <input type="file" name="dokumen" class="form-control">
            </div>

            <button class="btn btn-primary">Simpan</button>

        </form>

    </div>
</div>

@endsection
