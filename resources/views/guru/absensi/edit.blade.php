@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')

<div class="container-fluid py-3">

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="mb-4">

                <h4 class="fw-bold mb-1">

                    Edit Absensi

                </h4>

                <small class="text-muted">

                    Anda sedang mengubah absensi pertemuan sebelumnya

                </small>

            </div>

            <form
                action="{{ route(
                    'guru.absensi.update',
                    $absensi->id
                ) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">

                        Nama Siswa

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $absensi->siswa->nama }}"
                        disabled
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option
                            value="hadir"
                            {{
                                $absensi->status == 'hadir'
                                ? 'selected'
                                : ''
                            }}
                        >

                            Hadir

                        </option>

                        <option
                            value="izin"
                            {{
                                $absensi->status == 'izin'
                                ? 'selected'
                                : ''
                            }}
                        >

                            Izin

                        </option>

                        <option
                            value="sakit"
                            {{
                                $absensi->status == 'sakit'
                                ? 'selected'
                                : ''
                            }}
                        >

                            Sakit

                        </option>

                        <option
                            value="alpha"
                            {{
                                $absensi->status == 'alpha'
                                ? 'selected'
                                : ''
                            }}
                        >

                            Alpha

                        </option>

                    </select>

                </div>

                <button class="btn btn-primary px-4">

                    Simpan Perubahan

                </button>

            </form>

        </div>

    </div>

</div>

@endsection