@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <h5 class="mb-0">

                Kelola Akun

            </h5>

        </div>

        <div class="card-body">

            <form
                method="GET"
                class="row g-3 mb-4"
            >

                <div class="col-md-3">

                    <select
                        name="role"
                        class="form-select"
                    >

                        <option value="">
                            Semua Role
                        </option>

                        <option
                            value="guru"
                            @selected(request('role') == 'guru')
                        >
                            Guru
                        </option>

                        <option
                            value="siswa"
                            @selected(request('role') == 'siswa')
                        >
                            Siswa
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="1"
                            @selected(request('status') === '1')
                        >
                            Aktif
                        </option>

                        <option
                            value="0"
                            @selected(request('status') === '0')
                        >
                            Nonaktif
                        </option>

                    </select>

                </div>

                <div class="col-md-3">

                    <button
                        class="btn btn-primary"
                    >

                        Filter

                    </button>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>

                            <th>Nama</th>

                            <th>Username</th>

                            <th>Role</th>

                            <th>Status</th>

                            <th width="250">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $item)

                            <tr>

                                <td>

                                    {{ $item->name }}

                                </td>

                                <td>

                                    {{ $item->username }}

                                </td>

                                <td>

                                    {{ strtoupper($item->role) }}

                                </td>

                                <td>

                                    @if($item->is_active)

                                        <span class="badge bg-success">

                                            Aktif

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Nonaktif

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        <a
                                            href="{{ route('tu.akun.show',$item) }}"
                                            class="btn btn-info btn-sm"
                                        >

                                            Detail

                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('tu.akun.reset-password',$item) }}"
                                        >
                                            @csrf

                                            <button
                                                class="btn btn-warning btn-sm"
                                            >

                                                Reset Password

                                            </button>

                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('tu.akun.toggle-status',$item) }}"
                                        >
                                            @csrf

                                            <button
                                                class="btn btn-secondary btn-sm"
                                            >

                                                {{ $item->is_active
                                                    ? 'Nonaktifkan'
                                                    : 'Aktifkan' }}

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center"
                                >

                                    Data tidak ditemukan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $data->links() }}

        </div>

    </div>

</div>

@endsection