@php
    $tahun = current_tahun();
@endphp
<div class="sidebar">

    @include('layouts.partials.tahun-ajaran')
    @include('layouts.partials.tu-mode')

    <div class="sidebar-title">
        MAIN OPERATOR
    </div>

    {{-- DASHBOARD --}}
    <a
        class="menu-item"
        href="{{ route('tu.dashboard') }}"
    >

        <div class="menu-left">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </div>

    </a>

    {{-- PROSES AKADEMIK --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuProses','arrowProses')"
    >

        <div class="menu-left">
            <i class="bi bi-arrow-repeat"></i>
            <span>Proses Akademik</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowProses"
        ></i>

    </button>

    <div
        class="submenu
            {{ request()->is('tu/tahun*') ||
               request()->is('tu/penempatan-siswa*')
               ? 'show'
               : '' }}"
        id="menuProses"
    >

        <a
            class="menu-item"
            href="{{ route('tu.tahun.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-calendar3"></i>
                <span>Tahun Ajaran</span>
            </div>

        </a>

        <a
            class="menu-item"
            href="{{ route('tu.penempatan-siswa.naik-kelas.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-arrow-up-circle"></i>
                <span>Naik Kelas</span>
            </div>

        </a>

    </div>

    {{-- MANAJEMEN SEKOLAH --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuSekolah','arrowSekolah')"
    >

        <div class="menu-left">
            <i class="bi bi-building"></i>
            <span>Manajemen Sekolah</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowSekolah"
        ></i>

    </button>

    <div
        class="submenu
            {{ request()->is('tu/kelas*') ||
               request()->is('tu/siswa*') ||
               request()->is('tu/guru*') ||
               request()->is('tu/jadwal*') ||
               request()->is('tu/mapel*') ||
               request()->is('tu/ruangan*') ||
               request()->is('tu/walikelas*')
               ? 'show'
               : '' }}"
        id="menuSekolah"
    >

        <a
            class="menu-item"
            href="{{ route('tu.kelas.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-people"></i>
                <span>Kelas</span>
            </div>

        </a>

        <a
            class="menu-item"
            href="{{ route('tu.siswa.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-mortarboard"></i>
                <span>Siswa</span>
            </div>

        </a>

        <a
            class="menu-item"
            href="{{ route('tu.guru.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-person-badge"></i>
                <span>Guru</span>
            </div>

        </a>

        <a
            class="menu-item"
            href="{{ route('tu.jadwal.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-calendar-week"></i>
                <span>Jadwal</span>
            </div>

        </a>

        <a
            class="menu-item"
            href="{{ route('tu.mapel.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-journal-text"></i>
                <span>Mata Pelajaran</span>
            </div>

        </a>

        <a
            class="menu-item"
            href="{{ route('tu.ruangan.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-door-closed"></i>
                <span>Ruangan</span>
            </div>

        </a>

        <a
            class="menu-item"
            href="{{ route('tu.ekskul.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-dribbble"></i>

                <span>Ekskul</span>

            </div>

        </a>

    </div>

    {{-- ARSIP & DOKUMEN --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuArsip','arrowArsip')"
    >

        <div class="menu-left">
            <i class="bi bi-folder2-open"></i>
            <span>Arsip & Dokumen</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowArsip"
        ></i>

    </button>

    <div
        class="submenu
            {{ request()->is('tu/dokumen-sekolah*') ||
            request()->is('tu/dokumen-siswa*') ||
            request()->is('tu/pengajuan-surat*') ||
            request()->is('tu/datakepegawaian*') ||
            request()->is('tu/nomor-surat*')
            ? 'show': '' }}"id="menuArsip">

        {{-- DOKUMEN SEKOLAH --}}
            <a
                class="menu-item"
                href="{{ route('tu.dokumen.sekolah') }}"
            >

                <div class="menu-left">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Arsip Dokumen</span>
                </div>

            </a>

            {{-- DOKUMEN SISWA --}}
            <a
                class="menu-item"
                href="{{ route('tu.dokumen.siswa') }}">

                <div class="menu-left">
                    <i class="bi bi-mortarboard-fill"></i>
                    <span>Arsip Dokumen Siswa</span>
                </div>

            </a>

            {{-- PENGAJUAN SURAT  --}}
            <a
                class="menu-item"
                href="{{ route('tu.pengajuan-surat.index') }}">

                <div class="menu-left">
                    <i class="bi bi-send-check"></i>
                    <span>Pengajuan Surat</span>
                </div>

            </a>

            {{-- DATA KEPEGAWAIAN --}}
            <a
                class="menu-item"
                href="{{ route('tu.datakepegawaian.index') }}">

                <div class="menu-left">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Kepegawaian</span>
                </div>

            </a>

            <a
            class="menu-item"
            href="{{ route('tu.nomor-surat.index') }}">

            <div class="menu-left">
                <i class="bi bi-hash"></i>
                <span>Nomor Surat</span>
            </div>

        </a>

    </div>
    {{-- WEBSITE SEKOLAH --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuWebsite','arrowWebsite')"
    >

        <div class="menu-left">
            <i class="bi bi-globe"></i>
            <span>Website Sekolah</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowWebsite"
        ></i>

    </button>

    <div
        class="submenu"
        id="menuWebsite"
    >

        <a
            class="menu-item"
            href="{{ route('tu.website.berita.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-newspaper"></i>
                <span>Berita</span>
            </div>

        </a>

    </div>

    {{-- AKUN --}}
    <a
        class="menu-item"
        href="{{ route('tu.akun.index') }}"
    >

        <div class="menu-left">
            <i class="bi bi-speedometer2"></i>
            <span>Kelola Akun</span>
        </div>

    </a>

</div>