@php
    $user = auth()->user();
    $canApplicants = $user->hasFeature('ppdb.applicants');
    $canAccepted = $user->hasFeature('ppdb.accepted');
    $canRejected = $user->hasFeature('ppdb.rejected');
    $canReenrollment = $user->hasFeature('ppdb.reenrollment');
    $canPlacement = $user->hasFeature('ppdb.class_placement');
    $canVerification = $user->hasFeature('ppdb.verification');
    $canSettings = $user->hasFeature('ppdb.settings');
    $showPpdbMenu = $canApplicants || $canAccepted || $canRejected || $canReenrollment || $canPlacement || $canVerification;
@endphp

<div class="sidebar">
    @include('layouts.partials.tahun-ajaran')
    @include('layouts.partials.tu-mode')

    <div class="sidebar-title">MODE PPDB</div>

    @if($user->hasFeature('ppdb.dashboard'))
        <a class="menu-item {{ request()->is('tu/ppdb/dashboard*') ? 'active' : '' }}" href="{{ route('tu.ppdb.dashboard') }}">
            <div class="menu-left"><i class="bi bi-speedometer2"></i><span>Dashboard PPDB</span></div>
        </a>
    @endif

    @if($showPpdbMenu)
        <button class="menu-item border-0 bg-transparent text-start" onclick="toggleMenu('menuPpdb','arrowPpdb')">
            <div class="menu-left"><i class="bi bi-mortarboard"></i><span>PPDB</span></div>
            <i class="bi bi-chevron-right dropdown-arrow" id="arrowPpdb"></i>
        </button>

        <div class="submenu {{ request()->is('tu/ppdb/pendaftar*') || request()->is('tu/ppdb/verifikasi*') || request()->is('tu/ppdb/diterima*') || request()->is('tu/ppdb/ditolak*') || request()->is('tu/ppdb/daftar-ulang*') || request()->is('tu/ppdb/penempatan-kelas*') ? 'show' : '' }}" id="menuPpdb">
            @if($canApplicants)
                <a class="menu-item" href="{{ route('tu.ppdb.pendaftar.index') }}">
                    <div class="menu-left"><i class="bi bi-people"></i><span>Data Pendaftar</span></div>
                </a>
            @endif
            @if($canAccepted)
                <a class="menu-item" href="{{ route('tu.ppdb.diterima.index') }}">
                    <div class="menu-left"><i class="bi bi-check-circle"></i><span>Data Diterima</span></div>
                </a>
            @endif
            @if($canRejected)
                <a class="menu-item" href="{{ route('tu.ppdb.ditolak.index') }}">
                    <div class="menu-left"><i class="bi bi-x-circle"></i><span>Data Ditolak</span></div>
                </a>
            @endif
            @if($canVerification)
                <a class="menu-item" href="{{ route('tu.ppdb.verifikasi.index') }}">
                    <div class="menu-left"><i class="bi bi-clipboard-check"></i><span>Verifikasi Pendaftar</span></div>
                </a>
            @endif
            @if($canReenrollment)
                <a class="menu-item" href="{{ route('tu.ppdb.daftar-ulang.index') }}">
                    <div class="menu-left"><i class="bi bi-arrow-repeat"></i><span>Daftar Ulang</span></div>
                </a>
            @endif
            @if($canPlacement)
                <a class="menu-item" href="{{ route('tu.ppdb.penempatan-kelas.index') }}">
                    <div class="menu-left"><i class="bi bi-building"></i><span>Penempatan Kelas</span></div>
                </a>
            @endif
        </div>
    @endif

    @if($canSettings)
        <a class="menu-item" href="{{ route('tu.ppdb.pengaturan.index') }}">
            <div class="menu-left"><i class="bi bi-sliders"></i><span>Pengaturan PPDB</span></div>
        </a>
    @endif
</div>
