@php

    $user = Auth::user();

@endphp

<div
    class="modal fade"
    id="profileModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 class="modal-title">
                    Profile Saya
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div class="modal-body py-4">

                <div class="text-center mb-4">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}"
                        class="rounded-circle border"
                        width="100"
                        height="100"
                    >

                    <h5 class="fw-bold mt-3 mb-1">
                        {{ $user->name }} <i class="bi bi-patch-check-fill text-success ms-1"></i>
                    </h5>

                    <x-table.role-badge :role="$user->role" />                    

                </div>

                {{-- SISWA --}}
                @if($user->role === 'siswa' && $user->siswa)

                    @php
                        $siswa = $user->siswa;
                    @endphp

                    <div class="row g-3">

                        <div class="col-6">

                            <label class="small text-muted">
                                Nama
                            </label>

                            <div class="fw-semibold">
                                {{ $siswa->nama }}
                            </div>

                        </div>

                        <div class="col-6">

                            <label class="small text-muted">
                                NIS
                            </label>

                            <div class="fw-semibold">
                                {{ $siswa->nis }}
                            </div>

                        </div>

                        <div class="col-6">

                            <label class="small text-muted">
                                NISN
                            </label>

                            <div class="fw-semibold">
                                {{ $siswa->nisn }}
                            </div>

                        </div>

                        <div class="col-6">

                            <label class="small text-muted">
                                Gender
                            </label>

                            <div class="fw-semibold">
                                {{ $siswa->jenis_kelamin }}
                            </div>

                        </div>

                        <div class="col-6">

                            <label class="small text-muted">
                                Tanggal Lahir
                            </label>

                            <div class="fw-semibold">
                                {{ $siswa->tanggal_lahir }}
                            </div>

                        </div>

                        <div class="col-6">

                            <label class="small text-muted">
                                Kelas
                            </label>

                            <div class="fw-semibold">
                                {{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}
                            </div>

                        </div>

                    </div>

                @endif

                {{-- GURU --}}
                @if($user->role === 'guru' && $user->guru)

                    @php
                        $guru = $user->guru;
                    @endphp
                    <div class="text-center">                        
                        <div class="small text-muted">NIP :
                            <span class="fw-semibold">
                                {{ $guru->nip }}
                            </span>          
                        </div>                                     
                    </div>

                @endif

                {{-- TU --}}
                @if($user->role === 'tu')

                    <div class="alert alert-light border mb-0">
                        Staff Tata Usaha
                    </div>

                @endif

                {{-- SUPERADMIN --}}
                @if($user->role === 'superadmin')

                    <div class="alert alert-light border mb-0">
                        Kepala Sekolah
                    </div>

                @endif

                

            </div>

        </div>

    </div>

</div>