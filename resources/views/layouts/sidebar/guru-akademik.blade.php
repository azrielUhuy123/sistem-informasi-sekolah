@php
    $tahun = current_tahun();
@endphp

@php

    $user = auth()->user();
    $canSchedule = $user->hasAnyAssignmentMenu(['guru.schedule', 'guru.schedule_status']);
    $canClass = $user->hasAnyAssignmentMenu([
        'wali.classes', 'guru.attendance', 'wali.attendance',
        'guru.grades', 'wali.grades', 'guru.development', 'wali.development',
        'wali.promotion',
    ]);
    $canReports = $user->hasAnyAssignmentMenu(['guru.monthly_report', 'guru.semester_report']);
    $canDocuments = $user->hasAnyAssignmentMenu(['guru.documents', 'guru.letters', 'guru.letter_numbers']);

@endphp

<div class="sidebar">
    
    @include('layouts.partials.tahun-ajaran')

    @include('layouts.partials.guru-mode')

    <div class="sidebar-title">
        MAIN GURU
    </div>

    @if($user->hasFeature('guru.dashboard') || $user->hasFeature('wali.dashboard'))
    <a
        class="menu-item
        {{ request()->is('guru/dashboard*') ? 'active' : '' }}"
        href="{{ $user->hasFeature('wali.dashboard')
            ? route('guru.dashboard-walikelas')
            : route('guru.dashboard-gurubidang') }}"
    >

        <div class="menu-left">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </div>

    </a>
    @endif

    <a
        class="menu-item
        {{ request()->is('guru/profile*') ?  : '' }}"
        href="{{ route('guru.profile') }}">

        <div class="menu-left">
            <i class="bi bi-person-circle"></i>
            <span>My Profile</span>
        </div>

    </a>
    
    @if($user->hasFeature('guru.elearning'))
    <a
        class="menu-item
        {{ request()->is('guru/e-learning*') ?  : '' }}"
        href="{{ route('guru.e-learning.index') }}">

        <div class="menu-left">
            <i class="bi bi-mortarboard"></i>
            <span>E-Learning</span>
        </div>

    </a>
    @endif

    @if($canSchedule)
    <button class="menu-item border-0 bg-transparent text-start"
            onclick="toggleMenu('menuJadwal','arrowJadwal')">
        <div class="menu-left">
            <i class="bi bi-calendar-week"></i>
            <span>Jadwal</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowJadwal"></i>
    </button>

    <div class="submenu" id="menuJadwal">

        @if($user->hasFeature('guru.schedule'))
        <a class="menu-item {{ request()->is('guru/jadwal-saya*') ?  : '' }}"
           href="{{ route('guru.jadwal-saya.index') }}">
            <div class="menu-left">
                <i class="bi bi-calendar3"></i>
                <span>Jadwal Saya</span>
            </div>
        </a>
        @endif

        @if($user->hasFeature('guru.schedule_status'))
        <a class="menu-item {{ request()->is('guru/status-validasi*') ?  : '' }}"
           href="{{ route('guru.status-validasi.index') }}">
            <div class="menu-left">
                <i class="bi bi-patch-check"></i>
                <span>Status Validasi</span>
            </div>
        </a>
        @endif

    </div>
    @endif

    @if($canClass)
    <button class="menu-item border-0 bg-transparent text-start"
            onclick="toggleMenu('menuKelas','arrowKelas')">
        <div class="menu-left">
            <i class="bi bi-people"></i>
            <span>Kelas</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowKelas"></i>
    </button>

    <div class="submenu" id="menuKelas">
        @if($user->hasFeature('wali.classes'))
            <a class="menu-item {{ request()->is('guru/kelas-saya*') ?  : '' }}"
            href="{{ route('guru.kelas-saya') }}">
                <div class="menu-left">
                    <i class="bi bi-person"></i>
                    <span>Kelas Saya</span>
                </div>
            </a>
        @endif
        @if($user->hasAnyAssignmentMenu(['guru.attendance', 'wali.attendance']))
        <a class="menu-item {{ request()->is('guru/absensi*') ?  : '' }}"
           href="{{ route('guru.absensi.index') }}">
            <div class="menu-left">
                <i class="bi bi-send-check"></i>
                <span>Absensi Kelas</span>
            </div>
        </a>
        @endif

        @if($user->hasAnyAssignmentMenu(['guru.grades', 'wali.grades']))
        <a class="menu-item {{ request()->is('guru/nilai*') ?  : '' }}"
           href="{{ route('guru.nilai.index') }}">
            <div class="menu-left">
                <i class="bi bi-journal-check"></i>
                <span>Nilai</span>
            </div>
        </a>
        @endif

        @if($user->hasAnyAssignmentMenu(['guru.development', 'wali.development']))
        <a class="menu-item {{ request()->is('guru/perkembangan*') ?  : '' }}"
           href="{{ route('guru.perkembangan.index') }}">
            <div class="menu-left">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Pengembangan Siswa</span>
            </div>
        </a>
        @endif

        @if($user->hasFeature('wali.promotion'))
            <a class="menu-item {{ request()->is('guru/kenaikan*') ?  : '' }}"
            href="{{ route('guru.kenaikan.index') }}">
                <div class="menu-left">
                    <i class="bi bi-mortarboard"></i>
                    <span>Kenaikan Kelas</span>
                </div>
            </a>
        @endif

    </div>
    @endif

    @if($canReports)
    <button class="menu-item border-0 bg-transparent text-start"
            onclick="toggleMenu('menuAbsen','arrowAbsen')">
        <div class="menu-left">
            <i class="bi bi-journal-text"></i>
            <span>Rekapitulasi Absen</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowAbsen"></i>
    </button>
    <div class="submenu" id="menuAbsen">
        @if($user->hasFeature('guru.monthly_report'))
        <a
            class="menu-item
            {{ request()->is('guru/rekap-bulanan*') ?  : '' }}"
            href="{{ route('guru.absensi.rekap-bulanan') }}"
        >
            <div class="menu-left">
                <i class="bi bi-calendar3"></i>
                <span>Rekap Bulanan</span>
            </div>
        </a>
        @endif
        @if($user->hasFeature('guru.semester_report'))
        <a
            class="menu-item
            {{ request()->is('guru/rekap-semester*') ? 'active' : '' }}"
            href="{{ route('guru.absensi.rekap-semester') }}"
        >
            <div class="menu-left">
                <i class="bi bi-calendar-range"></i>
                <span>Rekap Semester</span>
            </div>
        </a>
        @endif
    </div>
    @endif

    {{-- DOKUMEN --}}
    @if($canDocuments)
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuDokumen','arrowDokumen')"
    >

        <div class="menu-left">
            <i class="bi bi-folder2-open"></i>
            <span>Dokumen</span>
        </div>
        <i class="bi bi-chevron-right dropdown-arrow"
        id="arrowDokumen"></i>

    </button>

    <div class="submenu show"
        id="menuDokumen">

        @if($user->hasFeature('guru.documents'))
        <a
            class="menu-item
            {{ request()->is('guru/dokumen*') ?  : '' }}"
            href="{{ route('guru.dokumen.surat') }}"
        >

            <div class="menu-left">
                <i class="bi bi-file-earmark-text"></i>
                <span>Arsip Dokumen</span>
            </div>

        </a>
        @endif

        @if($user->hasFeature('guru.letters'))
            <a
                class="menu-item
                {{ request()->is('guru/pengajuan-surat*') ?  : '' }}"
                href="{{ route('guru.pengajuan-surat.index') }}"
            >

                <div class="menu-left">
                    <i class="bi bi-send"></i>
                    <span>Pengajuan Surat</span>
                </div>

            </a>
        @endif

        @if($user->hasFeature('guru.letter_numbers'))
            <a
                class="menu-item
                {{ request()->is('guru/nomor-surat*') ?  : '' }}"
                href="{{ route('guru.nomor-surat.index') }}">

                <div class="menu-left">
                    <i class="bi bi-hash"></i>
                    <span>Nomor Surat</span>
                </div>

            </a>
        @endif

    </div>
    @endif

</div>