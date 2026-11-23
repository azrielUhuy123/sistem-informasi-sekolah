@extends('layouts.app')

@section('title', 'Detail Rombel')

@section('content')

<div class="container-fluid dt-siswa-page">

    <x-table.page-header
        icon="bi-people"
        eyebrow="Data Akademik / Rombongan Belajar"
        :title="'Kelas ' . $rombel->nama_kelas_label"
        :description="'Angkatan ' . $rombel->kelasGrup->angkatan->tahun"
    />

    {{-- ALERT --}}
    <x-alert.flash key="success" type="success"/>

    <x-alert.flash key="error" type="danger"/>

    <x-table.card
        title="Daftar Siswa {{ $rombel->nama_kelas_label }}"
        subtitle="{{ $totalLaki }} Pria - {{ $totalPerempuan }} Perempuan"
        :count="$totalSiswa"
        count-label="data"
    >

        <x-slot:toolbar>
            <x-table.local-toolbar
                search-id="searchInput"
                search-label="Cari siswa"
                search-placeholder="Cari siswa..."
            >
                <x-button.add
                    :href="route('tu.penempatan-siswa.create', ['kelas_id' => $rombel->id])"
                    label="Tambah Siswa ke Rombel"
                />
            </x-table.local-toolbar>
        </x-slot:toolbar>
        <div class="table-responsive dt-siswa-table-wrap">
            <table class="table table-hover align-middle dt-table">
                <thead>
                    <tr>
                        <x-table.heading width="70px">Absen</x-table.heading>
                        <x-table.heading>Nama Siswa</x-table.heading>
                        <x-table.heading width="150px">NISN</x-table.heading>
                        <x-table.heading width="150px">Jenis Kelamin</x-table.heading>
                        <x-table.heading width="150px">Tanggal Lahir</x-table.heading>
                        <x-table.heading width="250px">Wali</x-table.heading>
                        <x-table.heading width="90px">Aksi</x-table.heading>
                    </tr>
                </thead>
                <tbody id="tableSiswaKelas">
                    @forelse($siswa as $item)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark rounded-circle">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                            <i class="bi bi-person-fill fs-4"></i>
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $item->siswa->nama ?? '-' }}
                                            </div>
                                            <small class="text-muted">
                                                NIS : {{ $item->siswa->nis ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->siswa->nisn ?? '-' }}</td>
                                <td>
                                    @if(optional($item->siswa)->jenis_kelamin == 'L')
                                        <span class="badge bg-indigo rounded-pill px-2 py-1">
                                            <i class="bi bi-gender-male me-1"></i>
                                            Laki-Laki
                                        </span>
                                    @else
                                        <span class="badge bg-rose rounded-pill px-2 py-1">
                                            <i class="bi bi-gender-female me-1"></i>
                                            Perempuan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ optional($item->siswa)->tanggal_lahir
                                        ? \Carbon\Carbon::parse($item->siswa->tanggal_lahir)->format('d M Y')
                                        : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $item->siswa->nama_wali ?? '-' }}
                                            </div>
                                            <div class="mt-1">
                                                <span class="text-muted">
                                                    <i class="bi bi-telephone-fill me-1"></i>
                                                    {{ $item->siswa->telepon_wali ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <x-table.actions>
                                        <li>
                                            <a
                                                class="dropdown-item"
                                                href="{{ route('tu.siswa.show', $item->siswa->id) }}"
                                            >
                                                <i class="bi bi-eye"></i>
                                                Detail
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                class="dropdown-item"
                                                href="{{ route('tu.siswa.edit', $item->siswa->id) }}"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <form
                                                action="{{ route('tu.penempatan-siswa.keluarkan', $item->id) }}"
                                                method="POST"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-danger btn-confirm"
                                                    data-title="KELUARKAN SISWA INI?"
                                                    data-message="Siswa akan dikeluarkan dari rombel ini."
                                                    data-button="btn-danger"
                                                >
                                                    <i class="bi bi-box-arrow-right"></i>
                                                    Keluarkan
                                                </button>
                                            </form>
                                        </li>
                                    </x-table.actions>                                    
                                </td>
                            </tr>
                    @empty
                        <x-table.empty
                            :colspan="7"
                            title="Belum ada siswa di rombel ini"
                            description="Belum ada siswa aktif pada rombongan belajar ini."
                        />
                    @endforelse
                </tbody>
            </table>
            <x-table.pagination :paginator="$siswa" :show-summary="true" />
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('tu.kelas.rombel.index', $rombel->kelas_grup_id) }}" 
            class="btn btn-outline-secondary rounded-3">
                <i class="bi bi-chevron-left me-1"></i> Kembali
            </a>    
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
                '#tableSiswaKelas tr'
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
