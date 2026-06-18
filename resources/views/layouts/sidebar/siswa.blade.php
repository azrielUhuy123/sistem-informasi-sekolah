<div class="sidebar">

    @include('layouts.partials.tahun-ajaran')

    <div class="sidebar-title">
        MAIN SISWA
    </div>

    <a
        class="menu-item
        {{ request()->is('siswa/dashboard*') ? 'active' : '' }}"
        href="{{ route('siswa.dashboard.index') }}">

        <div class="menu-left">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </div>
    </a>

    <a
        class="menu-item
        {{ request()->is('siswa/profile*') ? 'active' : '' }}"
        href="{{ route('siswa.profile') }}">

        <div class="menu-left">
            <i class="bi bi-person-circle"></i>
            <span>My Profile</span>
        </div>

    </a>

    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuAkademik','arrowAkademik')">

        <div class="menu-left">
            <i class="bi bi-mortarboard"></i>
            <span>Akademik</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowAkademik"
        ></i>

    </button>

    <div class="submenu show" id="menuAkademik">

        <a
            class="menu-item
            {{ request()->is('siswa/jadwal*') ? 'active' : '' }}"
            href="{{ route('siswa.jadwal.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-calendar-week"></i>
                <span>Jadwal Pelajaran</span>
            </div>

        </a>


        <a
            class="menu-item
            {{ request()->is('siswa/nilai*') ? 'active' : '' }}"
            href="{{ route('siswa.nilai.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-journal-check"></i>
                <span>Nilai</span>
            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('siswa/absensi*') ? 'active' : '' }}"
            href="{{ route('siswa.absensi.index') }}">

            <div class="menu-left">
                <i class="bi bi-send-check"></i>
                <span>Absensi</span>
            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('siswa/ekskul*') ? 'active' : '' }}"
            href="{{ route('siswa.ekskul.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-trophy"></i>
                <span>Ekskul</span>
            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('siswa/e-learning*') ? 'active' : '' }}"
            href="{{ route('siswa.e-learning.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-mortarboard"></i>
                <span>E-learning</span>
            </div>

        </a>

    </div>
    {{-- ARSIP & DOKUMEN --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuDokumen','arrowDokumen')"
    >

        <div class="menu-left">
            <i class="bi bi-folder2-open"></i>
            <span>Arsip & Dokumen</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowDokumen"
        ></i>

    </button>

    <div class="submenu" id="menuDokumen">

        {{-- DOWNLOAD DOKUMEN --}}
        <a
            class="menu-item
            {{ request()->is('siswa/download-dokumen*') ? 'active' : '' }}"
            href="{{ route('siswa.download-dokumen.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-download"></i>
                <span>Download Dokumen</span>
            </div>

        </a>

        {{-- NOMOR SURAT --}}
        <a
            class="menu-item
            {{ request()->is('siswa/nomor-surat*') ? 'active' : '' }}"
            href="{{ route('siswa.nomor-surat.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-hash"></i>
                <span>Nomor Surat</span>
            </div>

        </a>

    </div>

</div>