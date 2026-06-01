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
        {{-- AKADEMIK --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuAkademik','arrowAkademik')"
    >

        <div class="menu-left">
            <i class="bi bi-journal-text"></i>
            <span>Akademik</span>
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
            class="menu-item {{ request()->is('superadmin/pengampu-mapel*') ? 'active' : '' }}"
            href="{{ route('superadmin.pengampu-mapel.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-diagram-3"></i>
                <span>Pengampu Mapel</span>
            </div>

        </a>

        <a
            class="menu-item {{ request()->is('superadmin/wali-kelas*') ? 'active' : '' }}"
            href="{{ route('superadmin.wali-kelas.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-person-badge"></i>
                <span>Wali Kelas</span>
            </div>

        </a>

        <a
            class="menu-item
            {{ request()->is('superadmin/pembina-ekskul*') ? 'active' : '' }}"
            href="{{ route('superadmin.pembina-ekskul.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-trophy"></i>
                <span>Pembina Ekskul</span>
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
            <span>Monitoring Akademik</span>
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
            class="menu-item {{ request()->is('superadmin/jadwal-final*') ? 'active' : '' }}"
            href="{{ route('superadmin.jadwal-final.index') }}"
        >

            <div class="menu-left">
                <i class="bi bi-grid-3x3-gap"></i>
                <span>Jadwal Final</span>
            </div>

        </a>
    </div>

    {{-- MONITORING EKSKUL --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuMonitoringEkskul','arrowMonitoringEkskul')"
    >

        <div class="menu-left">

            <i class="bi bi-trophy"></i>

            <span>

                Monitoring Ekskul

            </span>

        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowMonitoringEkskul"
        ></i>

    </button>

    <div
        class="submenu
        {{
            request()->is('superadmin/monitoring-ekskul*')
            ||
            request()->is('superadmin/jadwal-final-ekskul*')
                ? 'show'
                : ''
        }}"
        id="menuMonitoringEkskul"
    >

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

                <i class="bi bi-grid-3x3-gap"></i>

                <span>

                    Jadwal Final Ekskul

                </span>

            </div>

        </a>

    </div>
    {{-- APPROVAL USER --}}
    <button
        class="menu-item border-0 bg-transparent text-start"
        onclick="toggleMenu('menuApproval','arrowApproval')"
    >

        <div class="menu-left">
            <i class="bi bi-person-check"></i>
            <span>Approval User</span>
        </div>

        <i
            class="bi bi-chevron-right dropdown-arrow"
            id="arrowApproval"
        ></i>

    </button>

    <div
        class="submenu"
        id="menuApproval"
    >

        <a
            class="menu-item {{ request()->is('superadmin/users/pending*') ? 'active' : '' }}"
            href="{{ route('superadmin.users.pending') }}"
        >

            <div class="menu-left">
                <i class="bi bi-hourglass-split"></i>
                <span>Pending</span>
            </div>

        </a>

        <a
            class="menu-item {{ request()->is('superadmin/users/approved*') ? 'active' : '' }}"
            href="{{ route('superadmin.users.approved') }}"
        >

            <div class="menu-left">
                <i class="bi bi-check-circle"></i>
                <span>Disetujui</span>
            </div>

        </a>

        <a
            class="menu-item {{ request()->is('superadmin/users/rejected*') ? 'active' : '' }}"
            href="{{ route('superadmin.users.rejected') }}"
        >

            <div class="menu-left">
                <i class="bi bi-x-circle"></i>
                <span>Ditolak</span>
            </div>

        </a>

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

                <i
                    class="bi bi-chevron-right dropdown-arrow"
                    id="arrowDokumen"
                ></i>

            </button>

            <div
                class="submenu
                    {{ request()->is('superadmin/dokumen*') ||
                    request()->is('superadmin/datakepegawaian/approval*')
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

                {{-- DATA KEPEGAWAIAN --}}
                <a
                    class="menu-item
                        {{ request()->is('superadmin/datakepegawaian/approval*') ? 'active' : '' }}"
                    href="{{ route('superadmin.datakepegawaian.approval') }}"
                >

                    <div class="menu-left">
                        <i class="bi bi-person-vcard"></i>
                        <span>Data Kepegawaian</span>
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