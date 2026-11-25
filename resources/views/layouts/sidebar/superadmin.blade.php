@php
    $tahun = current_tahun();
@endphp

<div class="sidebar">

    @include('layouts.partials.tahun-ajaran')

    <div class="sidebar-title">
        KEPALA SEKOLAH
    </div>

    {{-- DASHBOARD --}}
    <a
        class="menu-item {{ request()->is('superadmin/dashboard') ? 'active' : '' }}"
        href="{{ route('superadmin.dashboard') }}"
    >
        <div class="menu-left">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </div>
    </a>    

    {{-- Master Data --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuAkademik','arrowAkademik')"
    >
        <div class="menu-left">
            <i class="bi bi-journal-text"></i>
            <span>Master Data</span>
        </div>
        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowAkademik"
        ></i>
    </button>

    <div
        class="submenu"
        id="menuAkademik"
    >
        <a
            class="menu-item {{ request()->is('superadmin/akademik/mapel*') ? 'active' : '' }}"
            href="{{ route('superadmin.mapel.index') }}"
        >
            <div class="menu-left">
                <i class="bi bi-book"></i>
                <span>Mata Pelajaran</span>
            </div>
        </a>
        <a
            class="menu-item {{ request()->is('superadmin/guru*') ? 'active' : '' }}"
            href="{{ route('superadmin.guru.index') }}"
        >
            <div class="menu-left">
                <i class="bi bi-people"></i>
                <span>Guru & Wali Kelas</span>
            </div>
        </a>
        <a
            class="menu-item {{ request()->is('superadmin/siswa*') ? 'active' : '' }}"
            href="{{ route('superadmin.siswa.index') }}"
        >
            <div class="menu-left">
                <i class="bi bi-mortarboard"></i>
                <span>Siswa</span>
            </div>
        </a>
        <a
            class="menu-item {{ request()->is('superadmin/jadwal-final*') ? 'active' : '' }}"
            href="{{ route('superadmin.jadwal-final.index') }}"
        >
            <div class="menu-left">
                <i class="bi bi-calendar2-check"></i>
                <span>Jadwal Kelas</span>
            </div>
        </a>
        <a
            class="menu-item
            {{
                request()->is('superadmin/jadwal-final-ekskul*')
                    ? 'active'
                    : ''
            }}"
            href="{{ route('superadmin.jadwal-final-ekskul.index') }}"
        >
            <div class="menu-left">
                <i class="bi bi-calendar4-week"></i>
                <span>
                    Jadwal Ekskul
                </span>
            </div>
        </a>
    </div>
    

    {{-- MONITORING AKADEMIK --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuMonitoring','arrowMonitoring')"
    >

        <div class="menu-left">
            <i class="bi bi-bar-chart-line"></i>
            <span>Monitoring</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowMonitoring"
        ></i>

    </button>

    <div
        class="submenu"
        id="menuMonitoring"
    >

        <a
            class="menu-item {{ request()->is('superadmin/monitoring-jadwal*') ? 'active' : '' }}"
            href="{{ route('superadmin.monitoring-jadwal.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-calendar-week"></i>
                <span>Monitoring Jadwal</span>
            </div>

        </a>        

        <a
            class="menu-item {{ request()->is('superadmin/monitoring-kenaikan*') ? 'active' : '' }}"
            href="{{ route('superadmin.monitoring-kenaikan.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-mortarboard"></i>
                <span>Monitoring Kenaikan Kelas</span>
            </div>

        </a>
        <a
            class="menu-item
            {{
                request()->is('superadmin/monitoring-ekskul*')
                    ? 'active'
                    : ''
            }}"
            href="{{ route('superadmin.monitoring-ekskul.index') }}"
        >

            <div class="menu-left">

                <i class="bi bi-calendar-week"></i>

                <span>

                    Monitoring Jadwal Ekskul

                </span>

            </div>

        </a>
    </div>

    {{-- MANAJEMEN KEPEGAWAIAN --}}
    @php
        $manajemenKepegawaianActive =
            request()->is('superadmin/data-kepegawaian*') ||
            request()->is('superadmin/perubahan-data-kepegawaian*') ||
            request()->is('superadmin/datakepegawaian*');
    @endphp
    <a
        class="menu-item {{ $manajemenKepegawaianActive ? 'active' : '' }}"
        href="{{ route('superadmin.pegawai-changes.employees') }}"
    >
        <div class="menu-left">
            <i class="bi bi-person-gear"></i>
            <span>Data Kepegawaian</span>
        </div>
    </a>

    <a
        class="menu-item {{ request()->is('superadmin/manajemen-penugasan*') ? 'active' : '' }}"
        href="{{ route('superadmin.manajemen-penugasan.index') }}"
    >
        <div class="menu-left">
            <i class="bi bi-sliders"></i>
            <span>Manajemen Penugasan</span>
        </div>
    </a>


        {{-- DOKUMEN --}}
                <button
                class="menu-item border-0 bg-transparent text-start"
                onclick="toggleMenu('menuDokumen','arrowDokumen')"
            >

                <div class="menu-left">
                    <i class="bi bi-folder2-open"></i>
                    <span>Dokumen</span>
                </div>

                <i
                    class="bi bi-chevron-right dropdown-arrow"
                    id="arrowDokumen"
                ></i>

            </button>

            <div
                class="submenu
                    {{ request()->is('superadmin/dokumen*')
                    ? 'show'
                    : '' }}"
                id="menuDokumen"
            >

                {{-- SURAT --}}
                <a
                    class="menu-item
                        {{ request()->is('superadmin/dokumen*') ? 'active' : '' }}"
                    href="{{ route('superadmin.dokumen.surat') }}"
                >

                    <div class="menu-left">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Arsip Dokumen</span>
                    </div>
    
                </a>                

                <a class="menu-item {{ request()->is('superadmin/pengajuan-surat*') ? 'active' : '' }}"
                    href="{{ route('superadmin.pengajuan-surat.index') }}">
                    <div class="menu-left">

                        <i class="bi bi-send-check"></i>

                        <span>Ajuan Surat</span>

                    </div>

                </a>
                <a
                    class="menu-item
                    {{ request()->is('superadmin/nomor-surat*') ? 'active' : '' }}"
                    href="{{ route('superadmin.nomor-surat.index') }}">

                    <div class="menu-left">
                        <i class="bi bi-hash"></i>
                        <span>Nomor Surat</span>
                    </div>

                </a>

            </div>

</div>