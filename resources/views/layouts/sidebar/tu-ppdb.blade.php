@php
    $tahun = current_tahun();
@endphp

<div class="sidebar">

    @include('layouts.partials.tahun-ajaran')

    @include('layouts.partials.tu-mode')

    <div class="sidebar-title">

        MODE PPDB

    </div>

    {{-- DASHBOARD PPDB --}}
    <a
        class="menu-item
        {{ request()->is('tu.ppdb/dashboard*') ? 'active' : '' }}"
        href="{{ route('tu.ppdb.dashboard') }}"
    >

        <div class="menu-left">

            <i class="bi bi-speedometer2"></i>

            <span>

                Dashboard PPDB

            </span>

        </div>

    </a>

    {{-- PPDB --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuPpdb','arrowPpdb')"
    >

        <div class="menu-left">

            <i class="bi bi-mortarboard"></i>

            <span>

                PPDB

            </span>

        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowPpdb"
        ></i>

    </button>

    <div
        class="submenu
        {{
            request()->is('tu.ppdb/pendaftar*') ||
            request()->is('tu.ppdb/verifikasi*') ||
            request()->is('tu.ppdb/diterima*') ||
            request()->is('tu.ppdb/ditolak*') ||
            request()->is('tu.ppdb/generate-akun*')
            ? 'show'
            : ''
        }}"
        id="menuPpdb"
    >

        <a
            class="menu-item
            {{ request()->is('tu.ppdb/pendaftar*') ? 'active' : '' }}"
            href="{{ route('tu.ppdb.pendaftar.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-people"></i>

                <span>

                    Data Pendaftar

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('tu.ppdb/verifikasi*') ? 'active' : '' }}"
            href="{{ route('tu.ppdb.verifikasi.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-patch-check"></i>

                <span>

                    Verifikasi Berkas

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('tu.ppdb/diterima*') ? 'active' : '' }}"
            href="{{ route('tu.ppdb.diterima.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-check-circle"></i>

                <span>

                    Data Diterima

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('tu.ppdb/ditolak*') ? 'active' : '' }}"
            href="{{ route('tu.ppdb.ditolak.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-x-circle"></i>

                <span>

                    Data Ditolak

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('tu.ppdb/daftar-ulang*') ? 'active' : '' }}"
            href="#"
        >

            <div class="menu-left">

                <i class="bi bi-arrow-repeat"></i>

                <span>

                    Daftar Ulang

                </span>

            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('tu.ppdb/generate-akun*') ? 'active' : '' }}"
            href="#"
        >

            <div class="menu-left">

                <i class="bi bi-person-plus"></i>

                <span>

                    Generate Akun

                </span>

            </div>

        </a>

    </div>

    {{-- PENGATURAN --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuPengaturanPpdb','arrowPengaturanPpdb')"
    >

        <div class="menu-left">

            <i class="bi bi-gear"></i>

            <span>

                Pengaturan

            </span>

        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowPengaturanPpdb"
        ></i>

    </button>

    <div
        class="submenu
        {{ request()->is('tu.ppdb/pengaturan*') ? 'show' : '' }}"
        id="menuPengaturanPpdb"
    >

        <a
            class="menu-item"
            href="{{ route('tu.ppdb.pengaturan.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-sliders"></i>

                <span>

                    Pengaturan PPDB

                </span>

            </div>

        </a>

    </div>

    {{-- LAPORAN --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuLaporanPpdb','arrowLaporanPpdb')"
    >

        <div class="menu-left">

            <i class="bi bi-bar-chart"></i>

            <span>

                Laporan

            </span>

        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowLaporanPpdb"
        ></i>

    </button>

    <div
        class="submenu"
        id="menuLaporanPpdb"
    >

        <a
            class="menu-item"
            href="#"
        >

            <div class="menu-left">

                <i class="bi bi-graph-up"></i>

                <span>

                    Statistik PPDB

                </span>

            </div>

        </a>

        <a
            class="menu-item"
            href="#"
        >

            <div class="menu-left">

                <i class="bi bi-file-earmark-excel"></i>

                <span>

                    Export Excel

                </span>

            </div>

        </a>

        <a
            class="menu-item"
            href="#"
        >

            <div class="menu-left">

                <i class="bi bi-file-earmark-pdf"></i>

                <span>

                    Export PDF

                </span>

            </div>

        </a>

    </div>

</div>