@extends('layouts.app')

@section('title', 'Wali Kelas')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-person-check"
        eyebrow="Data Akademik / Wali Kelas"
        title="Wali Kelas"
        description="Kelola penugasan guru sebagai wali kelas pada setiap rombel aktif."
    />

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Wali Kelas"
        subtitle="Daftar guru yang ditugaskan sebagai wali kelas"
        :count="$waliKelas->count()"
    >

        <x-slot:pageActions>
            <x-button.add
                :href="route('superadmin.wali-kelas.create')"
                label="Assign Wali Kelas"
            />
        </x-slot:pageActions>

        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <caption class="visually-hidden">Daftar guru yang ditugaskan sebagai wali kelas</caption>

                <thead>
                    <tr>
                        <x-table.heading width="70px">No</x-table.heading>
                        <x-table.heading>Nama Guru</x-table.heading>
                        <x-table.heading>Kelas</x-table.heading>
                        <x-table.heading width="220px">Tahun Ajaran</x-table.heading>
                        <x-table.heading width="130px">Aksi</x-table.heading>
                    </tr>
                </thead>

                <tbody>

                    @forelse($waliKelas as $item)

                        <tr>

                            {{-- NO --}}
                            <td>
                                <span class="badge bg-light text-dark rounded-circle">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- GURU --}}
                            <td>
                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width: 48px; height: 48px;"
                                    >
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $item->guru->pegawai?->nama ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            NIP : {{ $item->guru->pegawai?->nip ?? '-' }}
                                        </small>

                                    </div>

                                </div>
                            </td>

                            {{-- KELAS --}}
                            <td> 
                                <div class="fw-bold border text-label text-center bg-light rounded-3 px-2 py-2">
                                    {{ $item->kelas->nama_kelas_label ?? '-' }}
                                </div>
                            </td>

                            {{-- TAHUN AJARAN --}}
                            <td>

                                @if($item->tahunAjaran)

                                    <span class="text-muted">                                        
                                        {{ $item->tahunAjaran->tahun }}
                                        -
                                        {{ ucfirst($item->tahunAjaran->semester) }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td>

                                <form
                                    action="{{ route('superadmin.wali-kelas.destroy', $item->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm rounded-pill px-3 py-0 btn-confirm"
                                        data-title="Hapus Wali Kelas"
                                        data-message="Penugasan guru sebagai wali kelas akan dihapus dari sistem."
                                        data-button="btn-danger"
                                    >                                        
                                        Hapus <i class="bi bi-trash ms-2"></i>
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <x-table.empty
                            :colspan="5"
                            title="Belum ada guru yang ditetapkan sebagai wali kelas."
                            description=""
                        />

                    @endforelse

                </tbody>

            </table>
        </div>

    </x-table.card>

</div>

@endsection