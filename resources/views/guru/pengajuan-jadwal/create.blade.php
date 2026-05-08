@extends('layouts.app')

@section('title', 'Tambah Pengajuan Jadwal')

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-body">

        <h4 class="mb-4">
            Tambah Pengajuan Jadwal
        </h4>

        <form method="POST"
              action="{{ route('guru.pengajuan-jadwal.store') }}">

            @csrf

            {{-- KELAS --}}
            <div class="mb-3">

                <label class="form-label">
                    Kelas
                </label>

                <select name="kelas_id"
                        class="form-select">

                    @foreach($kelas as $item)

                        <option value="{{ $item->id }}"
                            {{ request('kelas_id') == $item->id ? 'selected' : '' }}>

                            {{ $item->nama_kelas }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- MAPEL --}}
            <div class="mb-3">

                <label class="form-label">
                    Mata Pelajaran
                </label>

                <select name="mapel_id"
                        class="form-select">

                    @foreach($mapel as $item)

                        <option value="{{ $item->id }}">

                            {{ $item->nama_mapel }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- HARI --}}
            <div class="mb-3">

                <label class="form-label">
                    Hari
                </label>

                <select name="hari_id"
                        class="form-select">

                    @foreach($hari as $item)

                        <option value="{{ $item->id }}"
                            {{ request('hari_id') == $item->id ? 'selected' : '' }}>

                            {{ $item->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- JAM --}}
            <div class="mb-3">

                <label class="form-label">
                    Jam Pelajaran
                </label>

                <select name="jam_pelajaran_id"
                        class="form-select">

                    @foreach($jam as $item)

                        <option value="{{ $item->id }}"
                            {{ request('jam_id') == $item->id ? 'selected' : '' }}>

                            Jam ke {{ $item->jam_ke }}
                            ({{ $item->mulai }} - {{ $item->selesai }})

                        </option>

                    @endforeach

                </select>

            </div>

            <button class="btn btn-primary">
                Simpan
            </button>

        </form>

    </div>

</div>

@endsection