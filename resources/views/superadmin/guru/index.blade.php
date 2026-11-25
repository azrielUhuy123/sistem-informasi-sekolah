
@extends('layouts.app')

@section('title', 'Guru & Wali Kelas')

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
                'label' => 'Guru & Wali Kelas',                
            ],
        ]"
    />

    <x-table.page-header        
        title="Guru & Wali Kelas"
        description="Kelola data guru dan wali kelas sekolah."
    />

    <x-table.card
        title="Daftar Guru"
        subtitle="Daftar seluruh guru pada SDN Mekar Jaya"
        :count="$gurus->total()"
        count-label="data"
    >

        <x-slot:toolbar>
            <x-table.toolbar
                table-id="dt-superadmin-guru"
                :action="route('superadmin.guru.index')"
                :searchable="true"
                search-name="q"
                :search-value="request('q')"
                search-label="Cari guru"
                search-placeholder="Cari nama atau NIP guru..."
                :filters="true"
                filter-label="Filter"
                filter-menu-title="Filter data guru"
                :column-toggle="true"
                :refreshable="true"
                :refresh-url="request()->fullUrl()"
            >

                {{-- FILTER BERTINGKAT --}}
                <div
                    class="dropdown"
                    data-bs-auto-close="outside"
                >
                    <button
                        type="button"
                        class="btn btn-outline-secondary dt-toolbar__button dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                        @if (
                            request()->has('status')
                            || request()->has('mapel_count')
                            || request()->has('class_count')
                        )
                            <span class="badge bg-primary ms-1">
                                {{
                                    count((array) request('status', []))
                                    + count((array) request('mapel_count', []))
                                    + count((array) request('class_count', []))
                                }}
                            </span>
                        @endif
                    </button>

                    <div
                        class="dropdown-menu dt-column-menu p-3"
                        style="width: 280px; max-height: 480px; overflow-y: auto;"
                    >

                        {{-- STATUS AKADEMIK --}}
                        <fieldset class="mb-3">
                            <legend class="dt-column-menu__title">
                                Status Akademik
                            </legend>

                            @foreach ([
                                'Wali Kelas',
                                'Guru Bidang',
                            ] as $status)
                                <label class="dt-column-menu__option">
                                    <input
                                        type="checkbox"
                                        name="status[]"
                                        value="{{ $status }}"
                                        @checked(
                                            in_array(
                                                $status,
                                                (array) request('status', [])
                                            )
                                        )
                                    >
                                    <span>{{ $status }}</span>
                                </label>
                            @endforeach
                        </fieldset>

                        <hr>

                        {{-- JUMLAH MAPEL --}}
                        <fieldset class="mb-3">
                            <legend class="dt-column-menu__title">
                                Mata Pelajaran
                            </legend>

                            @foreach ([
                                '0 Mapel',
                                '1 Mapel',
                                '2 Mapel',
                                '3 Mapel',
                                '4+ Mapel',
                            ] as $mapel)
                                <label class="dt-column-menu__option">
                                    <input
                                        type="checkbox"
                                        name="mapel_count[]"
                                        value="{{ $mapel }}"
                                        @checked(
                                            in_array(
                                                $mapel,
                                                (array) request('mapel_count', [])
                                            )
                                        )
                                    >
                                    <span>{{ $mapel }}</span>
                                </label>
                            @endforeach
                        </fieldset>

                        <hr>

                        {{-- TOTAL KELAS --}}
                        <fieldset class="mb-3">
                            <legend class="dt-column-menu__title">
                                Total Kelas
                            </legend>

                            @foreach ([
                                '0 Kelas',
                                '1–4 Kelas',
                                '5–7 Kelas',
                                '8–14 Kelas',
                                '15+ Kelas',
                            ] as $kelas)
                                <label class="dt-column-menu__option">
                                    <input
                                        type="checkbox"
                                        name="class_count[]"
                                        value="{{ $kelas }}"
                                        @checked(
                                            in_array(
                                                $kelas,
                                                (array) request('class_count', [])
                                            )
                                        )
                                    >
                                    <span>{{ $kelas }}</span>
                                </label>
                            @endforeach
                        </fieldset>

                        <hr>

                        <div class="d-flex justify-content-between gap-2">
                            <a
                                href="{{ route('superadmin.guru.index', request()->only('q')) }}"
                                class="btn btn-outline-secondary btn-sm"
                            >
                                Reset
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary btn-sm"
                            >
                                Terapkan
                            </button>
                        </div>

                    </div>
                </div>

                <x-slot:columnMenu>
                    @foreach ([
                        'nip' => 'NIP',
                        'teacher' => 'Nama Guru',
                        'academic_status' => 'Status Akademik',
                        'subjects' => 'Mata Pelajaran',
                        'classes' => 'Total Kelas',
                        'action' => 'Aksi',
                    ] as $column => $label)
                        <label class="dt-column-menu__option">
                            <input
                                type="checkbox"
                                data-dt-column="{{ $column }}"
                                checked
                            >
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </x-slot:columnMenu>

            </x-table.toolbar>
        </x-slot:toolbar>

        {{-- FILTER CHIPS --}}
        <x-table.chips
            :criteria="[
                [
                    'name' => 'q',
                    'label' => 'Pencarian',
                    'value' => request('q'),
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'value' => request('status', []),
                ],
                [
                    'name' => 'mapel_count',
                    'label' => 'Mata Pelajaran',
                    'value' => request('mapel_count', []),
                ],
                [
                    'name' => 'class_count',
                    'label' => 'Total Kelas',
                    'value' => request('class_count', []),
                ],
            ]"
        />

        <div
            class="table-responsive dt-siswa-table-wrap"
            role="region"
            aria-label="Daftar guru"
            tabindex="0"
        >
            <table
                id="dt-superadmin-guru"
                class="table table-hover align-middle dt-table dt-siswa-table"
            >
                <caption class="visually-hidden">
                    Daftar seluruh guru
                </caption>

                <colgroup>
                    <col class="dt-siswa-col-action" data-dt-column="nip">
                    <col class="dt-siswa-col-name" data-dt-column="teacher">
                    <col class="dt-siswa-col-status" data-dt-column="academic_status">
                    <col class="dt-siswa-col-class" data-dt-column="subjects">
                    <col class="dt-siswa-col-nisn" data-dt-column="classes">
                    <col class="dt-siswa-col-action" data-dt-column="action">
                </colgroup>

                <thead>
                    <tr>
                        <x-table.heading column="nip">NIP</x-table.heading>
                        <x-table.heading width="21rem" column="teacher">Nama Guru</x-table.heading>
                        <x-table.heading width="8.5rem" column="academic_status">Status Akademik</x-table.heading>
                        <x-table.heading width="8rem" column="subjects">Mata Pelajaran</x-table.heading>
                        <x-table.heading width="7.5rem" column="classes">Total Kelas</x-table.heading>                        
                        <x-table.heading width="7.5rem" column="action">Aksi</x-table.heading>                                      
                    </tr>
                </thead>

                <tbody id="tableGuru">

                    @forelse ($gurus as $guru)

                        <tr>

                            {{-- NOMOR --}}
                            <td                                
                                data-dt-column="nip"
                            >
                                {{ $guru->pegawai?->nip ?? $guru->nip ?? '-' }}
                            </td>

                            {{-- NAMA GURU --}}
                            <th scope="row" class="dt-siswa-identity-cell" data-dt-column="teacher">
                                <div class="dt-siswa-identity">
                                    <span class="dt-siswa-avatar" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                   
                                    {{ $guru->pegawai?->nama ?? $guru->nama ?? '-' }}                                     
                                </div>
                            </th>

                            {{-- STATUS AKADEMIK --}}
                            <td data-dt-column="academic_status">
                                @if ($guru->filter_status === 'Wali Kelas')                                    
                                    Wali Kelas
                                @elseif ($guru->filter_status === 'Guru Bidang')                                   
                                    Guru Bidang                                    
                                @else
                                    -
                                @endif
                            </td>

                            {{-- MATA PELAJARAN --}}
                            <td data-dt-column="subjects">                                
                                {{ $guru->jumlah_mapel }} Mapel                                
                            </td>

                            {{-- TOTAL KELAS --}}
                            <td data-dt-column="classes">
                                @if ($guru->jumlah_kelas === 0)
                                    <span class="badge-status selesai">
                                        0 kelas
                                    </span>
                                @elseif ($guru->jumlah_kelas >= 15)
                                    <span class="badge-status nonaktif">
                                        {{ $guru->jumlah_kelas }} Kelas
                                    </span>
                                @elseif ($guru->jumlah_kelas >= 8)
                                    <span class="badge-status pending">
                                        {{ $guru->jumlah_kelas }} Kelas
                                    </span>
                                @else
                                    <span class="badge-status aktif">
                                        {{ $guru->jumlah_kelas }} Kelas
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td data-dt-column="action">
                                <a
                                    href="{{ route('superadmin.guru.show', $guru) }}"
                                    class="btn btn-sm btn-outline-primary dt-siswa-detail"
                                >
                                    Detail
                                    <i class="bi bi-arrow-up-right" aria-hidden="true"></i>
                                </a>
                            </td>

                        </tr>

                    @empty

                        @if (
                            request()->filled('q')
                            || request()->has('status')
                            || request()->has('mapel_count')
                            || request()->has('class_count')
                        )
                            <x-table.empty
                                :colspan="6"
                                state="no-results"
                                title="Tidak ada guru yang cocok"
                                description="Ubah kata kunci atau kriteria filter, lalu coba lagi."
                            />
                        @else
                            <x-table.empty
                                :colspan="6"
                                state="empty"
                                title="Belum ada data guru"
                                description="Data guru belum tersedia."
                            />
                        @endif

                    @endforelse

                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="dt-siswa-pagination">
            <x-table.pagination
                :paginator="$gurus"
                :show-summary="true"
            />
        </div>

    </x-table.card>

</div>

@endsection
