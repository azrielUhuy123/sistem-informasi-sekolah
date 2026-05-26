<div class="sidebar">

    @include('layouts.partials.tahun-ajaran')

    <div class="sidebar-title">
        MAIN SISWA
    </div>

    <a
        class="menu-item
        {{ request()->is('siswa/dashboard*') ? 'active' : '' }}"
        href="{{ route('siswa.dashboard') }}">

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
            href="{{ route('siswa.absensi.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-send-check"></i>
                <span>Absensi</span>
            </div>

        </a>

    </div>

</div>