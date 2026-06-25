@extends('layouts.app')

@section('title', 'Detail Guru')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="row">

                {{-- KIRI (AVATAR + NAMA) --}}
                <div class="col-md-4 d-flex align-items-center gap-3">

    {{-- FOTO --}}
    @if($guru->foto)
        <img src="{{ asset('foto-guru/'.$guru->foto) }}"
             class="rounded-circle"
             style="
                width:120px;
                height:120px;
                object-fit:cover;
                border:3px solid #dee2e6;
             ">
    @else
        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
             style="width:120px;height:120px;font-size:28px;">
            {{ strtoupper(substr($guru->pegawai->nama,0,2)) }}
        </div>
    @endif

    <div>
        <h5 class="mb-0 fw-bold">{{ $guru->pegawai->nama }}</h5>
        <small class="text-muted">NIP {{ $guru->nip ?? '-' }}</small><br>
        <small class="text-muted">{{ $guru->email ?? '-' }}</small>
    </div>

</div>

                {{-- KANAN (DETAIL DATA) --}}
                <div class="col-md-8">

                    <h6 class="fw-bold mb-3">Informasi Guru</h6>

                    <table class="table table-sm align-middle">

                        <tr>
                            <th width="200">Jenis Kelamin</th>
                            <td>
                                @if($guru->jenis_kelamin == 'L')
                                    <span class="badge bg-primary">Laki-laki</span>
                                @else
                                    <span class="badge bg-danger">Perempuan</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Telepon</th>
                            <td>{{ $guru->telepon ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Alamat</th>
                            <td>{{ $guru->alamat ?? '-' }}</td>
                        </tr>

                    </table>

                    {{-- BUTTON --}}
                    <div class="mt-3">
                        <a href="{{ route('tu.guru.edit', $guru->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit Data
                        </a>

                        <a href="{{ route('tu.guru.index') }}"
                           class="btn btn-secondary btn-sm">
                            Kembali
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection