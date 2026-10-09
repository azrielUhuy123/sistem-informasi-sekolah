@extends('layouts.app')

@section('title', 'Wali Kelas')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Wali Kelas</h3>
                    <p class="mb-0 opacity-75">
                        Kelola penugasan guru sebagai wali kelas pada setiap rombel aktif.
                    </p>
                </div>

                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <x-table.card
        title="Daftar Wali Kelas"
        subtitle="Daftar guru yang ditugaskan sebagai wali kelas"
    >

        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $waliKelas->count() }}
                <span class="text-badge">Wali Kelas</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>
            <x-button.add
                :href="route('superadmin.wali-kelas.create')"
                label="Assign Wali Kelas"
            />
        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama Guru</th>
                        <th>Kelas</th>
                        <th width="220">Tahun Ajaran</th>
                        <th width="130">Aksi</th>
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

                        <tr>
                            <td colspan="5" class="text-center py-5">

                                <i class="bi bi-person-x display-5 text-secondary"></i>

                                <p class="mt-3 mb-0 text-muted">
                                    Belum ada guru yang ditetapkan sebagai wali kelas.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </x-table.card>

</div>

@endsection