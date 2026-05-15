@extends('layouts.app')

@section('title', 'Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-0">
                Wali Kelas
            </h4>

            <small class="text-muted">
                Penentuan wali kelas
            </small>

        </div>

        <a
            href="{{ route('superadmin.wali-kelas.create') }}"
            class="btn btn-primary"
        >

            + Assign Wali Kelas

        </a>

    </div>

    {{-- TABLE --}}
    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                Guru
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th>
                                Tahun Ajaran
                            </th>

                            <th width="120">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($waliKelas as $item)

                            <tr>

                                <td>
                                    {{ $item->guru->nama }}
                                </td>

                                <td>
                                    {{ $item->kelas->nama_kelas }}
                                </td>

                                <td>
                                    @if($item->tahunAjaran)

                                    {{ $item->tahunAjaran->tahun }}
                                    -
                                    {{ $item->tahunAjaran->semester }}

                                    @else

                                        -

                                    @endif
                                </td>

                                <td>

                                    <form
                                        action="{{ route(
                                            'superadmin.wali-kelas.destroy',
                                            $item->id
                                        ) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm(
                                                'Hapus wali kelas ini?'
                                            )"
                                        >

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="4"
                                    class="text-center text-muted py-4"
                                >

                                    Belum ada wali kelas

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection