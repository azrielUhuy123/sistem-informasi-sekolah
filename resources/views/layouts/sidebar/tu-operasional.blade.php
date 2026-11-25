@php
    $user = auth()->user();
    $canYear = $user->hasFeature('operator.school_year');
    $canClasses = $user->hasFeature('operator.classes');
    $canStudents = $user->hasFeature('operator.students');
    $canTeachers = $user->hasFeature('operator.teachers');
    $canSubjects = $user->hasFeature('operator.subjects');
    $canSchedule = $user->hasFeature('operator.schedule');
    $canExtracurriculars = $user->hasFeature('operator.extracurriculars');
    $canDocuments = $user->hasFeature('operator.documents');
    $canEmployees = $user->hasFeature('operator.employees');
    $canAccounts = $user->hasFeature('operator.accounts');
    $canSearch = $user->hasFeature('operator.search');
    $showSchoolMenu = $canClasses || $canStudents || $canTeachers || $canSubjects || $canSchedule || $canExtracurriculars;
    $hasOperatorAccess = $user->hasAnyAssignmentMenu(array_keys(config('assignment_menus.types.operator.menus', [])));
@endphp

@if(empty($sidebarEmbedded))
<div class="sidebar">
    @include('layouts.partials.tahun-ajaran')
    @include('layouts.partials.tu-mode')
@endif

@if($hasOperatorAccess)
    <div class="sidebar-title">MAIN OPERATOR</div>

    @if($user->hasFeature('operator.dashboard'))
        <a class="menu-item" href="{{ route('tu.dashboard') }}">
            <div class="menu-left">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard Operator</span>
            </div>
        </a>
    @endif

    @if($canYear)
        <a class="menu-item" href="{{ route('tu.tahun.index') }}">
            <div class="menu-left">
                <i class="bi bi-calendar3"></i>
                <span>Kelola Tahun Ajaran</span>
            </div>
        </a>
    @endif

    @if($showSchoolMenu)
        <button class="menu-item border-0 bg-transparent text-start" onclick="toggleMenu('menuSekolah','arrowSekolah')">
            <div class="menu-left">
                <i class="bi bi-building"></i>
                <span>Kelola Akademik</span>
            </div>
            <i class="bi bi-chevron-right dropdown-arrow" id="arrowSekolah"></i>
        </button>

        <div class="submenu {{ request()->is('tu/kelas*') || request()->is('tu/siswa*') || request()->is('tu/guru*') || request()->is('tu/jadwal-final*') || request()->is('tu/mapel*') || request()->is('tu/ekskul*') ? 'show' : '' }}" id="menuSekolah">
            @if($canClasses)
                <a class="menu-item" href="{{ route('tu.kelas.index') }}">
                    <div class="menu-left"><i class="bi bi-people"></i><span>Kelas</span></div>
                </a>
            @endif
            @if($canStudents)
                <a class="menu-item" href="{{ route('tu.siswa.index') }}">
                    <div class="menu-left"><i class="bi bi-mortarboard"></i><span>Siswa</span></div>
                </a>
            @endif
            @if($canTeachers)
                <a class="menu-item" href="{{ route('tu.guru.index') }}">
                    <div class="menu-left"><i class="bi bi-person-badge"></i><span>Guru</span></div>
                </a>
            @endif
            @if($canSubjects)
                <a class="menu-item" href="{{ route('tu.mapel.index') }}">
                    <div class="menu-left"><i class="bi bi-journal-text"></i><span>Mata Pelajaran</span></div>
                </a>
            @endif
            @if($canExtracurriculars)
                <a class="menu-item" href="{{ route('tu.ekskul.index') }}">
                    <div class="menu-left"><i class="bi bi-dribbble"></i><span>Ekskul</span></div>
                </a>
            @endif
            @if($canSchedule)
                <a class="menu-item" href="{{ route('tu.jadwal-final.index') }}">
                    <div class="menu-left"><i class="bi bi-calendar-week"></i><span>Jadwal Kelas</span></div>
                </a>
            @endif
        </div>
    @endif

    @if($canDocuments)
        <button class="menu-item border-0 bg-transparent text-start" onclick="toggleMenu('menuArsip','arrowArsip')">
            <div class="menu-left">
                <i class="bi bi-folder2-open"></i>
                <span>Arsip &amp; Dokumen</span>
            </div>
            <i class="bi bi-chevron-right dropdown-arrow" id="arrowArsip"></i>
        </button>

        <div class="submenu {{ request()->is('tu/dokumen*') || request()->is('tu/pengajuan-surat*') || request()->is('tu/nomor-surat*') ? 'show' : '' }}" id="menuArsip">
            <a class="menu-item" href="{{ route('tu.dokumen.sekolah') }}">
                <div class="menu-left"><i class="bi bi-file-earmark-text"></i><span>Arsip Dokumen</span></div>
            </a>
            <a class="menu-item" href="{{ route('tu.dokumen.siswa') }}">
                <div class="menu-left"><i class="bi bi-mortarboard-fill"></i><span>Arsip Dokumen Siswa</span></div>
            </a>
            <a class="menu-item" href="{{ route('tu.pengajuan-surat.index') }}">
                <div class="menu-left"><i class="bi bi-send-check"></i><span>Pengajuan Surat</span></div>
            </a>
            <a class="menu-item" href="{{ route('tu.nomor-surat.index') }}">
                <div class="menu-left"><i class="bi bi-hash"></i><span>Nomor Surat</span></div>
            </a>
        </div>
    @endif

    @if($canEmployees)
        <a class="menu-item" href="{{ route('tu.datakepegawaian.index') }}">
            <div class="menu-left"><i class="bi bi-people-fill"></i><span>Data Kepegawaian</span></div>
        </a>
        <a class="menu-item {{ request()->is('tu/pegawai/perubahan*') ? 'active' : '' }}"
            href="{{ route('tu.pegawai-changes.index') }}">
            <div class="menu-left"><i class="bi bi-person-check"></i><span>Verifikasi Perubahan</span></div>
        </a>
    @endif

    @if($canAccounts)
        <a class="menu-item" href="{{ route('tu.akun.index') }}">
            <div class="menu-left"><i class="bi bi-person-gear"></i><span>Kelola Akun</span></div>
        </a>
    @endif

    @if($canSearch)
        <a class="menu-item" href="{{ route('tu.search') }}">
            <div class="menu-left"><i class="bi bi-search"></i><span>Pencarian Data</span></div>
        </a>
    @endif
@endif

@if(empty($sidebarEmbedded))
</div>
@endif
