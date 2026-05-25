@php
    $tahun = current_tahun();
@endphp

@php

    $guru = auth()->user()->guru;

    $isWaliKelas = \App\Models\WaliKelas::where(
        'guru_id',
        $guru?->id
    )
    ->where(
        'tahun_ajaran_id',
        current_tahun_id()
    )
    ->exists();

@endphp

<div class="sidebar">

    @include('layouts.partials.tahun-ajaran')

    <div class="sidebar-title">
        MAIN GURU
    </div>

    <a class="menu-item {{ request()->is('guru/dashboard-walikelas') ? 'active' : '' }}"
       href="{{ route('guru.dashboard-walikelas') }}">
        <div class="menu-left">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </div>
    </a>

    

    <button class="menu-item border-0 bg-transparent text-start"
            onclick="toggleMenu('menuJadwal','arrowJadwal')">
        <div class="menu-left">
            <i class="bi bi-calendar-week"></i>
            <span>Jadwal</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowJadwal"></i>
    </button>

    <div class="submenu" id="menuJadwal">

        <a class="menu-item {{ request()->is('guru/jadwal-saya*') ? 'active' : '' }}"
           href="{{ route('guru.jadwal-saya.index') }}">
            <div class="menu-left">
                <i class="bi bi-calendar3"></i>
                <span>Jadwal Saya</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('guru/status-validasi*') ? 'active' : '' }}"
           href="{{ route('guru.status-validasi.index') }}">
            <div class="menu-left">
                <i class="bi bi-patch-check"></i>
                <span>Status Validasi</span>
            </div>
        </a>

    </div>

    <button class="menu-item border-0 bg-transparent text-start"
            onclick="toggleMenu('menuKelas','arrowKelas')">
        <div class="menu-left">
            <i class="bi bi-people"></i>
            <span>Kelas</span>
        </div>

        <i class="bi bi-chevron-right dropdown-arrow" id="arrowKelas"></i>
    </button>

    <div class="submenu" id="menuKelas">
        @if($isWaliKelas)
            <a class="menu-item {{ request()->is('guru/kelas-saya*') ? 'active' : '' }}"
            href="{{ route('guru.kelas-saya') }}">
                <div class="menu-left">
                    <i class="bi bi-people"></i>
                    <span>Kelas Saya</span>
                </div>
            </a>
        @endif

        <a class="menu-item {{ request()->is('guru/absensi*') ? 'active' : '' }}"
           href="{{ route('guru.absensi.index') }}">
            <div class="menu-left">
                <i class="bi bi-send-check"></i>
                <span>Absensi</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('guru/rekap-absensi*') ? 'active' : '' }}"
           href="{{ route('guru.absensi.rekap') }}">
            <div class="menu-left">
                <i class="bi bi-bar-chart"></i>
                <span>Rekap Absensi</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('guru/nilai*') ? 'active' : '' }}"
           href="{{ route('guru.nilai.index') }}">
            <div class="menu-left">
                <i class="bi bi-journal-check"></i>
                <span>Nilai</span>
            </div>
        </a>

        <a class="menu-item {{ request()->is('guru/perkembangan*') ? 'active' : '' }}"
           href="{{ route('guru.perkembangan.index') }}">
            <div class="menu-left">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Pengembangan Siswa</span>
            </div>
        </a>

        @if($isWaliKelas)
            <a class="menu-item {{ request()->is('guru/kenaikan*') ? 'active' : '' }}"
            href="{{ route('guru.kenaikan.index') }}">
                <div class="menu-left">
                    <i class="bi bi-mortarboard"></i>
                    <span>Kenaikan Kelas</span>
                </div>
            </a>
        @endif

    </div>

    {{-- DOKUMEN --}}
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

        <a
            class="menu-item
            {{ request()->is('guru/dokumen*') ? 'active' : '' }}"
            href="{{ route('guru.dokumen.surat') }}"
        >

            <div class="menu-left">
                <i class="bi bi-file-earmark-text"></i>
                <span>Arsip Dokumen</span>
            </div>

        </a>

            <a
                class="menu-item
                {{ request()->is('guru/pengajuan-surat*') ? 'active' : '' }}"
                href="{{ route('guru.pengajuan-surat.index') }}"
            >

                <div class="menu-left">
                    <i class="bi bi-send"></i>
                    <span>Pengajuan Surat</span>
                </div>

            </a>

            <a
                class="menu-item
                {{ request()->is('guru/nomor-surat*') ? 'active' : '' }}"
                href="{{ route('guru.nomor-surat.index') }}">

                <div class="menu-left">
                    <i class="bi bi-hash"></i>
                    <span>Nomor Surat</span>
                </div>

            </a>

    </div>

</div>