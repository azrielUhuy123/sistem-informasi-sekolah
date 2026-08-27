@extends('layouts.app')

@section('title', 'Daftar Ulang Peserta Didik Baru')

@section('content')

<div class="container-fluid">
{{-- HEADER --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div>
                        <h3 class="fw-bold mb-2">Daftar Ulang Peserta Didik Baru</h3>
                        <p class="mb-0 opacity-75">Kelola daftar ulang peserta didik baru</p>
                    </div> 
                    <div class="badge bg-white text-primary px-3 py-2 fs-6">
                        <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                    </div>
                </div>
            </div>
        </div>

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    {{-- CARD --}}
    <x-table.card
        title="Daftar Ulang Peserta Didik Baru"
        subtitle="Seluruh pendaftar ulang peserta didik baru pada SDN Mekar Jaya."
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $data->count() }} <span class="text-badge">Daftar Ulang</span>
            </span>
        </x-slot:badge>

        <x-slot:actions>            
            <input
                type="text"
                id="searchInput"
                class="form-control table-search"
                placeholder="Cari Pendaftar...."                
            >       
        </x-slot:actions>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="180">No Pendaftaran</th>
                        <th>Nama</th>
                        <th width="150">NIS</th>
                        <th width="150">Akun</th>
                        <th width="150">Tanggal Lahir</th>
                        <th width="150">Status</th>                        
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tablePPDB">
                    @foreach($data as $item)
                    <tr>
                        <td>
                            <span>
                                {{ $item->no_pendaftaran }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                    <i class="bi bi-person-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ $item->nama }}
                                    </div>                                    
                                </div>
                            </div>
                            
                        </td>
                        
                        <td>
                            @if($item->siswa)
                                <span class="fw-semibold">
                                    {{ $item->siswa->nis }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($item->siswa?->user)
                                <span class="badge-status aktif">
                                    <i class="bi bi-person-check me-2"></i>
                                    Akun Terdaftar
                                </span>
                            @else
                                <span class="badge-status nonaktif">
                                    <i class="bi bi-person-x me-2"></i>
                                    Akun Belum Terdaftar
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $item->tanggal_lahir  }}
                            </span>
                        </td>
                        <td>
                           @if($item->status_daftar_ulang == 'sudah')
                                <span class="badge-status selesai">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Sudah Daftar Ulang
                                </span>
                            @else
                                <span class="badge-status pending">
                                    <i class="bi bi-clock-history me-2"></i>
                                    Belum Daftar Ulang
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->status_daftar_ulang != 'sudah')

                            <form
                                action="{{ route('tu.ppdb.daftar-ulang.konfirmasi',$item) }}"
                                method="POST"
                            >

                            @csrf

                            <button
                                class="btn btn-success btn-sm badge rounded-pill border px-2 py-1 btn-confirm"
                                data-title="KONFIRMASI DAFTAR ULANG?"
                                data-message="Daftar Ulang Peserta didik baru akan Terkonfirmasi"
                                data-button="btn-primary"
                            >

                            <i class="bi bi-check-circle me-1"></i>

                            Konfirmasi

                            </button>

                            </form>

                            @else

                            <span class="text-success">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            Selesai

                            </span>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>   
            <div class="mt-4 d-flex justify-content-end">
                {{ $data->links() }} 
            </div>
        </div>
    </x-table.card>
</div>
{{-- SEARCH --}}
<script>

    const searchInput =
        document.getElementById('searchInput');

    searchInput.addEventListener('keyup', function () {

        const keyword =
            this.value.toLowerCase();

        const rows =
            document.querySelectorAll(
                '#tablePPDB tr'
            );

        rows.forEach(row => {

            const text =
                row.innerText.toLowerCase();

            row.style.display =
                text.includes(keyword)
                ? ''
                : 'none';

        });
    });

</script>
@endsection