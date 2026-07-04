@extends('layouts.app')

@section('title', 'Detail Akun')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                Detail Akun

            </h5>

        </div>

        <div class="card-body">

            <table class="table">

                <tr>

                    <th width="250">

                        Nama

                    </th>

                    <td>

                        {{ $user->name }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Username

                    </th>

                    <td>

                        {{ $user->username }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Role

                    </th>

                    <td>

                        {{ strtoupper($user->role) }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Status

                    </th>

                    <td>

                        @if($user->is_active)

                            <span class="badge bg-success">

                                Aktif

                            </span>

                        @else

                            <span class="badge bg-danger">

                                Nonaktif

                            </span>

                        @endif

                    </td>

                </tr>

                <tr>

                    <th>

                        Dibuat

                    </th>

                    <td>

                        {{ $user->created_at }}

                    </td>

                </tr>

            </table>

            @if($user->role == 'siswa' && $user->siswa)

                <hr>

                <h5>

                    Data Siswa

                </h5>

                <table class="table">

                    <tr>

                        <th width="250">

                            NIS

                        </th>

                        <td>

                            {{ $user->siswa->nis }}

                        </td>

                    </tr>

                    <tr>

                        <th>

                            Kelas

                        </th>

                        <td>

                            {{ $user->siswa->penempatanAktif?->kelas?->nama_kelas ?? '-' }}

                        </td>

                    </tr>

                </table>

            @endif

            @if($user->role == 'guru' && $user->guru)

                <hr>

                <h5>

                    Data Guru

                </h5>

                <table class="table">

                    <tr>

                        <th width="250">

                            NIP

                        </th>

                        <td>

                            {{ $user->guru->nip }}

                        </td>

                    </tr>

                </table>

            @endif

            <a
                href="{{ route('tu.akun.index') }}"
                class="btn btn-light"
            >

                Kembali

            </a>

        </div>

    </div>

</div>

@endsection