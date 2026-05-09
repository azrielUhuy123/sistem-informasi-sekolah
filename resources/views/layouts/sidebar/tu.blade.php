@php
    $tahun = current_tahun();
@endphp
<div class="sidebar">

    @include('layouts.partials.tahun-ajaran')

    <div class="sidebar-title">
        MAIN OPERATOR
    </div>

    {{-- DASHBOARD --}}
    <a
        class="menu-item
            {{ request()->is('tu/dashboard') ? 'active' : '' }}"
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
            class="menu-item
                {{ request()->is('tu/tahun*') ? 'active' : '' }}"
            href="{{ route('tu.tahun.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-calendar3"></i>
                <span>Tahun Ajaran</span>
            </div>

        </a>

        <a
            class="menu-item
                {{ request()->is('tu/penempatan-siswa*') ? 'active' : '' }}"
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
            class="menu-item
                {{ request()->is('tu/kelas*') ? 'active' : '' }}"
            href="{{ route('tu.kelas.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-people"></i>
                <span>Kelas</span>
            </div>

        </a>

        <a
            class="menu-item
                {{ request()->is('tu/siswa*') ? 'active' : '' }}"
            href="{{ route('tu.siswa.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-mortarboard"></i>
                <span>Siswa</span>
            </div>

        </a>

        <a
            class="menu-item
                {{ request()->is('tu/guru*') ? 'active' : '' }}"
            href="{{ route('tu.guru.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-person-badge"></i>
                <span>Guru</span>
            </div>

        </a>

        <a
            class="menu-item
                {{ request()->is('tu/jadwal*') ? 'active' : '' }}"
            href="{{ route('tu.jadwal.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-calendar-week"></i>
                <span>Jadwal</span>
            </div>

        </a>

        <a
            class="menu-item
                {{ request()->is('tu/mapel*') ? 'active' : '' }}"
            href="{{ route('tu.mapel.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-journal-text"></i>
                <span>Mata Pelajaran</span>
            </div>

        </a>

        <a
            class="menu-item
                {{ request()->is('tu/ruangan*') ? 'active' : '' }}"
            href="{{ route('tu.ruangan.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-door-closed"></i>
                <span>Ruangan</span>
            </div>

        </a>

        <a
            class="menu-item
                {{ request()->is('tu/walikelas*') ? 'active' : '' }}"
            href="{{ route('tu.walikelas.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-person-workspace"></i>
                <span>Wali Kelas</span>
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
           request()->is('tu/datakepegawaian*')
           ? 'show': '' }}"id="menuArsip">

    {{-- DOKUMEN SEKOLAH --}}
    <a
        class="menu-item
            {{ request()->is('tu/dokumen-sekolah*') ? 'active' : '' }}"
        href="{{ route('tu.dokumen.sekolah') }}"
    >

        <div class="menu-left">
            <i class="bi bi-file-earmark-text"></i>
            <span>Arsip Surat</span>
        </div>

    </a>

    {{-- DOKUMEN SISWA --}}
    <a
        class="menu-item
            {{ request()->is('tu/dokumen-siswa*') ? 'active' : '' }}"
        href="{{ route('tu.dokumen.siswa') }}">

        <div class="menu-left">
            <i class="bi bi-mortarboard-fill"></i>
            <span>Arsip Surat Siswa</span>
        </div>

    </a>

    {{-- PENGAJUAN SURAT --}}
    <a
        class="menu-item
            {{ request()->is('tu/pengajuan-surat*') ? 'active' : '' }}"
        href="{{ route('tu.pengajuan-surat.index') }}">

        <div class="menu-left">
            <i class="bi bi-send-check"></i>
            <span>Pengajuan Surat</span>
        </div>

    </a>

    {{-- DATA KEPEGAWAIAN --}}
    <a
        class="menu-item
            {{ request()->is('tu/datakepegawaian*') ? 'active' : '' }}"
        href="{{ route('tu.datakepegawaian.index') }}"
    >

        <div class="menu-left">
            <i class="bi bi-people-fill"></i>
            <span>Data Kepegawaian</span>
        </div>

    </a>

</div>

</div>