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

    @include('layouts.partials.guru-mode')

    <div class="sidebar-title">

        MODE PEMBINA EKSKUL

    </div>

    {{-- DASHBOARD --}}
    <a
        class="menu-item
        {{ request()->is('guru/ekskul/dashboard*') ? 'active' : '' }}"
        href="{{ route('guru.ekskul.dashboard') }}"
    >

        <div class="menu-left">

            <i class="bi bi-speedometer2"></i>

            <span>

                Dashboard

            </span>

        </div>

    </a>

    {{-- PROFILE --}}
    <a
        class="menu-item"
        href="{{ route('guru.profile') }}"
    >

        <div class="menu-left">

            <i class="bi bi-person-circle"></i>

            <span>

                My Profile

            </span>

        </div>

    </a>

    {{-- JADWAL --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuJadwalEkskul','arrowJadwalEkskul')"
    >

        <div class="menu-left">

            <i class="bi bi-calendar-event"></i>

            <span>

                Jadwal

            </span>

        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowJadwalEkskul"
        ></i>

    </button>

    <div class="submenu" id="menuJadwalEkskul">

        <a
            class="menu-item
            {{ request()->is('guru/ekskul/jadwal*') ? 'active' : '' }}"
            href="{{ route('guru.ekskul.jadwal.index') }}">
            <div class="menu-left">

                <i class="bi bi-calendar-week"></i>

                <span>

                    Jadwal Ekskul

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('guru/ekskul/status-validasi*') ? 'active' : '' }}"
            href="{{ route('guru.ekskul.validasi.index') }}">

            <div class="menu-left">

                <i class="bi bi-patch-check"></i>

                <span>

                    Status Validasi

                </span>

            </div>

        </a>

    </div>

    {{-- EKSKUL --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuEkskul','arrowEkskul')"
    >

        <div class="menu-left">

            <i class="bi bi-trophy"></i>

            <span>

                Ekskul

            </span>

        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowEkskul"
        ></i>

    </button>

    <div class="submenu show" id="menuEkskul">

        <a
            class="menu-item
            {{ request()->is('guru/ekskul/anggota*') ? 'active' : '' }}"
            href="{{ route('guru.ekskul.anggota.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-people"></i>

                <span>

                    Pendaftaran Anggota

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('guru/ekskul/daftar-anggota*') ? 'active' : '' }}"
            href="{{ route('guru.ekskul.daftar-anggota') }}"
        >

            <div class="menu-left">

                <i class="bi bi-people-fill"></i>

                <span>

                    Daftar Anggota

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('guru/ekskul/absensi*') ? 'active' : '' }}"
            href="{{ route('guru.ekskul.absensi.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-send-check"></i>

                <span>

                    Absensi Ekskul

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('guru/ekskul/absensi*') ? 'active' : '' }}"
            href="{{ route('guru.ekskul.absensi.rekap') }}"
        >

            <div class="menu-left">

                <i class="bi bi-bar-chart"></i>

                <span>

                    Rekap Absensi

                </span>

            </div>

        </a>

            <a
                class="menu-item
                {{ request()->is('guru/ekskul/jurnal*') ? 'active' : '' }}"
                href="{{ route('guru.ekskul.jurnal.index') }}"
            >

            <div class="menu-left">

                <i class="bi bi-journal-text"></i>

                <span>

                    Jurnal Kegiatan

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('guru/ekskul/catatan-pembinaan*') ? 'active' : '' }}"
            href="{{ route('guru.ekskul.pembinaan.index') }}">

            <div class="menu-left">

                <i class="bi bi-award"></i>

                <span>

                    Catatan Pembinaan

                </span>

            </div>

        </a>

    </div>

</div>