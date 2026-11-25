<div class="sidebar">
    @include('layouts.partials.tahun-ajaran')
    @include('layouts.partials.assignment-mode', ['user' => auth()->user()])

    <a class="menu-item" href="{{ route('guru.profile') }}">
        <div class="menu-left"><i class="bi bi-person-circle"></i><span>My Profile</span></div>
    </a>
    <a class="menu-item {{ request()->is('guru/pegawai/perubahan*') ? 'active' : '' }}"
       href="{{ route('guru.pegawai-changes.index') }}">
        <div class="menu-left"><i class="bi bi-person-vcard"></i><span>Perubahan Data Pegawai</span></div>
    </a>

    @php
        $modes = auth()->user()->getAssignmentModes();
        $assignmentMode = session('assignment_mode');
        if (! isset($modes[$assignmentMode])) {
            $assignmentMode = array_key_first($modes);
        }
    @endphp

    @if(in_array($assignmentMode, ['guru', 'wali'], true))
        @include('layouts.sidebar.guru-akademik', ['sidebarEmbedded' => true, 'assignmentMode' => $assignmentMode])
    @elseif($assignmentMode === 'ekskul')
        @include('layouts.sidebar.guru-ekskul', ['sidebarEmbedded' => true])
    @elseif($assignmentMode === 'operator')
        @include('layouts.sidebar.tu-operasional', ['sidebarEmbedded' => true])
    @elseif($assignmentMode === 'ppdb')
        @include('layouts.sidebar.tu-ppdb', ['sidebarEmbedded' => true])
    @endif
</div>
