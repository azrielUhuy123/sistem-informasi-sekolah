@extends('layouts.app')

@section('title', 'Detail Siswa')

@section('content')

    <div class="container-fluid dt-siswa-page">

        <x-table.breadcrumb
            :items="[
                [
                    'label' => 'Kepala Sekolah',                                
                ],
                [
                    'label' => 'Master Data',                
                ],
                [
                    'label' => 'Data Siswa',    
                    'url' => route('superadmin.siswa.index'),            
                ],
                [
                    'label' => 'Detail Siswa',                
                ],
            ]"
        />

        <div class="card shadow-sm border-0 overflow-hidden">
            <div class="card-body p-4 p-lg-5">

                <div class="row g-4 align-items-start">

                    <div class="col-lg-4">
                        <div class="bg-primary bg-opacity-10 rounded-4 p-4 border h-100">
                            <div class="d-flex flex-column align-items-center text-center gap-3">
                                <img
                                    src="{{ $siswa->foto
                                        ? asset('storage/foto_siswa/'.$siswa->foto)
                                        : 'https://ui-avatars.com/api/?name='.urlencode($siswa->nama).'&background=E9ECEF&color=495057&size=120' }}"
                                    class="rounded-circle"
                                    width="110"
                                    height="110"
                                    style="object-fit:cover;"
                                >

                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $siswa->nama }}</h4>
                                    <p class="text-muted mb-2">Siswa</p>

                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle">
                                            <i class="bi bi-person-badge me-1"></i> NIS {{ $siswa->nis }}
                                        </span>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                            <i class="bi bi-credit-card-2-front me-1"></i> NISN {{ $siswa->nisn ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="w-100">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Status Siswa</div>
                                                <div class="fw-semibold">
                                                    @if($siswa->penempatanAktif)
                                                        <span class="badge-status aktif">
                                                        <i class="bi bi-circle-fill me-2"></i>
                                                        Aktif
                                                    @else
                                                        <span class="badge-status nonaktif">
                                                        <i class="bi bi-circle-fill me-2"></i>
                                                        Tidak Aktif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded-3 p-2 bg-white">
                                                <div class="small text-muted">Rombel</div>
                                                <div class="fw-semibold">
                                                    <span class="badge bg-purple rounded-3">{{ $siswa->penempatanAktif?->kelas?->nama_kelas_label ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0">Profil Siswa</h5>
                                    <x-table.role-badge :role="$siswa->user?->role" /> 
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Tempat, Tanggal Lahir</div>
                                        <div class="fw-semibold">
                                            {{ $siswa->ppdb?->tempat_lahir ?? '-' }},
                                            {{ $siswa->ppdb?->tanggal_lahir
                                            ? \Carbon\Carbon::parse($siswa->ppdb?->tanggal_lahir)->format('d-m-Y')
                                            : '-' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Agama</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->agama }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Alamat</div>
                                        <div class="fw-semibold">{{ $siswa->alamat }}</div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="small text-muted mb-1">Jenis Kelamin</div>
                                        <div class="fw-semibold">
                                            @if($siswa->jenis_kelamin == 'L')
                                                <span class="badge bg-indigo rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-male me-1"></i>
                                                    Laki-laki
                                                </span>
                                            @else
                                                <span class="badge bg-rose rounded-pill px-2 py-1">
                                                    <i class="bi bi-gender-female me-1"></i>
                                                    Perempuan
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <hr class="my-4">

                <div class="row g-4">

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-file-earmark-text fs-5 text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Informasi Akademik</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor Pendaftaran</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->no_pendaftaran }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Tahun Ajaran</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->tahunAjaran?->tahun }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Tanggal Pendaftaran</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->tanggal_pendaftaran }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Status Daftar Ulang</div>
                                        <div class="fw-semibold">{{ ucfirst($siswa->ppdb?->status_daftar_ulang ?? '-') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-info bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-briefcase fs-5 text-info"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Data Orang Tua</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nama Wali</div>
                                        <div class="fw-semibold">{{ $siswa->nama_wali }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Telepon Wali</div>
                                        <div class="fw-semibold">{{ $siswa->telepon_wali }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Telepon Ayah</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->hp_ayah }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Telepon Ibu</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->hp_ibu }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-award fs-5 text-secondary"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Data Kependudukan</h5>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">NIK</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->nik }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor KK</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->nomor_kk }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="small text-muted mb-1">Nomor Akta</div>
                                        <div class="fw-semibold">{{ $siswa->ppdb?->nomor_akta }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <x-table.card
                            title="Riwayat Kelas"
                            :count="$siswa->penempatan->count()"
                            count-label="riwayat"
                        >
                            <x-slot:toolbar>
                                <x-table.local-toolbar
                                    search-id="searchRiwayatKelasSiswa"
                                    search-label="Cari riwayat kelas"
                                    search-placeholder="Cari tahun atau kelas..."
                                    table-id="dt-superadmin-riwayat-kelas-siswa"
                                    :column-toggle="true"
                                    :refreshable="true"
                                    :refresh-url="request()->fullUrl()"
                                >
                                    <x-slot:columnMenu>
                                        @foreach ([
                                            'tahun' => 'Tahun Ajaran',
                                            'kelas' => 'Kelas',
                                            'tanggal_masuk' => 'Tanggal Masuk',
                                            'tanggal_keluar' => 'Tanggal Keluar',
                                            'status' => 'Status',
                                        ] as $column => $label)
                                            <label class="dt-column-menu__option">
                                                <input type="checkbox" data-dt-column="{{ $column }}" checked>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </x-slot:columnMenu>
                                </x-table.local-toolbar>
                            </x-slot:toolbar>

                            <div class="table-responsive dt-siswa-table-wrap">

                                <table id="dt-superadmin-riwayat-kelas-siswa" class="table table-hover align-middle mb-0 dt-table">
                                    <colgroup>
                                        <col data-dt-column="tahun">
                                        <col data-dt-column="kelas">
                                        <col data-dt-column="tanggal_masuk">
                                        <col data-dt-column="tanggal_keluar">
                                        <col data-dt-column="status">
                                    </colgroup>

                                    <thead>
                                        <tr>
                                            <x-table.heading column="tahun">Tahun Ajaran</x-table.heading>
                                            <x-table.heading column="kelas">Kelas</x-table.heading>
                                            <x-table.heading column="tanggal_masuk">Tanggal Masuk</x-table.heading>
                                            <x-table.heading column="tanggal_keluar">Tanggal Keluar</x-table.heading>
                                            <x-table.heading column="status">Status</x-table.heading>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($siswa->penempatan as $item)

                                            <tr>

                                                <td data-dt-column="tahun">
                                                    {{ $item->tahunAjaran?->tahun ?? '-' }}
                                                    {{ $item->tahunAjaran?->semester ? '- '.$item->tahunAjaran->semester : '' }}
                                                </td>

                                                <td data-dt-column="kelas">
                                                    <span class="badge bg-purple rounded-3">
                                                        {{ $item->kelas?->nama_kelas_label ?? '-' }}
                                                    </span>
                                                </td>

                                                <td data-dt-column="tanggal_masuk">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d M Y') }}
                                                </td>

                                                <td data-dt-column="tanggal_keluar">
                                                    {{ $item->tanggal_keluar
                                                        ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d M Y')
                                                        : '-' }}
                                                </td>

                                                <td data-dt-column="status">

                                                    @if($item->tanggal_keluar === null)

                                                        <span class="badge-status aktif">
                                                            <i class="bi bi-circle-fill me-2"></i>
                                                            Aktif
                                                        </span>

                                                    @else

                                                        <span class="badge-status selesai">
                                                            <i class="bi bi-check-circle me-2"></i>
                                                            Selesai
                                                        </span>

                                                    @endif

                                                </td>

                                            </tr>

                                        @empty
                                            <x-table.empty
                                                :colspan="5"
                                                title="Belum ada riwayat kelas"
                                                description="Riwayat penempatan siswa belum tersedia."
                                            />

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </x-table.card>
                    </div>

                </div>
 
            </div>

        </div>
        <div class="sticky-bottom bg-white border border-light-subtle py-3 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('superadmin.siswa.index') }}" class="btn btn-outline-secondary rounded-3 ms-4">
                    <i class="bi bi-chevron-left me-1"></i> Kembali
                </a>
            </div>
        </div>

    </div>
@endsection
