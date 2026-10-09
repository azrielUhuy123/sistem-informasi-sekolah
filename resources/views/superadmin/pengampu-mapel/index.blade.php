@extends('layouts.app')

@section('title', 'Pengampu Mapel')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-4 p-lg-5 text-white" style="background: linear-gradient(135deg, #0f4c81 0%, #2563eb 100%);">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <h3 class="fw-bold mb-2">Pengampu Mapel</h3>
                    <p class="mb-0 opacity-75">Kelola guru pengampu mata pelajaran.</p>
                </div>
                <div class="badge bg-white text-primary px-3 py-2 fs-6">
                    <i class="bi bi-shield-check me-2"></i> Sinergi-MJ
                </div>
            </div>
        </div>
    </div>

    <x-alert.flash key="success" type="success" />
    <x-alert.flash key="error" type="danger" />

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-4">
            <h5 class="fw-bold mb-1">Penempatan Pengampu</h5>
            <small class="text-muted">Pilih satu guru untuk mengelola seluruh mata pelajaran dan kelasnya.</small>
        </div>

        <div class="card-body pt-3">
            <form method="GET" action="{{ route('superadmin.pengampu-mapel.index') }}">
                <div class="row align-items-end">
                    <div class="col-lg-12 mb-5">
                        <label for="guru_id" class="form-label fw-semibold">Pilih Guru</label>
                        <select id="guru_id" name="guru_id" class="form-control" onchange="this.form.submit()" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach ($guru as $g)
                                <option value="{{ $g->id }}" {{ $guruTerpilih?->id == $g->id ? 'selected' : '' }}>
                                    {{ $g->pegawai?->nama ?? $g->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            @if ($guruTerpilih)
                @php
                    $assignedMapelIds = $pengampuGuru->pluck('mata_pelajaran_id')->unique();
                    $assignedKelasIds = $pengampuGuru->pluck('kelas_id')->unique();
                @endphp

                <form action="{{ route('superadmin.pengampu-mapel.store') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="guru_id" value="{{ $guruTerpilih->id }}">                    

                    <hr>
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-3">
                        <div>
                            <h6 class="fw-bold mb-1">Penugasan Mata Pelajaran &amp; Kelas</h6>
                            <div class="text-muted" id="pengampu-global-summary">
                                {{ $assignedMapelIds->count() }} Mapel ditugaskan &middot; {{ $assignedKelasIds->count() }} Kelas ditugaskan
                            </div>
                        </div>
                        
                    </div>

                    <div class="input-group mb-3">                        
                        <input type="search" class="form-control" id="pengampu-mapel-search" placeholder="Cari mata pelajaran..." autocomplete="off">
                    </div>

                    <div id="pengampu-mapel-list">
                        @foreach ($mapel as $m)
                            @php
                                $selectedCount = $pengampuGuru->where('mata_pelajaran_id', $m->id)->count();
                                $collapseId = 'pengampu-mapel-' . $m->id;
                            @endphp
                            <div class="border rounded-3 mb-3 pengampu-mapel-section" data-mapel-name="{{ strtolower($m->nama_mapel) }}">
                                <button type="button"
                                    class="btn btn-light w-100 text-start rounded-3 d-flex align-items-center justify-content-between gap-3 p-3"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}"
                                    aria-controls="{{ $collapseId }}"
                                    aria-expanded="{{ $selectedCount > 0 ? 'true' : 'false' }}">
                                    <span class="d-flex align-items-center gap-3 min-w-0">
                                        <span class="badge bg-primary rounded-pill px-2 py-1">
                                            {{ $m->kode_mapel ?? '-' }}
                                        </span>
                                        <span class="fw-semibold text-truncate">{{ $m->nama_mapel }}</span>
                                    </span>
                                    <span class="d-flex align-items-center gap-2 flex-shrink-0">
                                        <small class="text-muted pengampu-count" data-mapel-count="{{ $m->id }}">{{ $selectedCount }} kelas</small>
                                        <i class="bi {{ $selectedCount > 0 ? 'bi-chevron-up' : 'bi-chevron-down' }} collapse-chevron"></i>
                                    </span>
                                </button>

                                <div id="{{ $collapseId }}" class="collapse {{ $selectedCount > 0 ? 'show' : '' }}">
                                    <div class="border-top p-3">
                                        <div class="row g-2">
                                            @foreach ($kelas as $k)
                                                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                                                    <label class="d-flex align-items-center gap-2 border rounded-3 p-2 h-100">
                                                        <input type="checkbox"
                                                            class="form-check-input pengampu-kelas-checkbox"
                                                            data-mapel-id="{{ $m->id }}"
                                                            name="pengampu[{{ $m->id }}][kelas_id][]"
                                                            value="{{ $k->id }}"
                                                            @checked($pengampuGuru->contains(fn ($p) => $p->mata_pelajaran_id == $m->id && $p->kelas_id == $k->id))>
                                                        <span>{{ $k->nama_kelas_label }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div id="pengampu-mapel-empty-search" class="text-center text-muted py-3 d-none">
                        Mata pelajaran tidak ditemukan.
                    </div>
                    <div class="text-end">
                        <a href="{{ route('superadmin.pengampu-mapel.index') }}" class="btn btn-outline-secondary rounded-3 me-2">
                            <i class="bi bi-chevron-left me-1"></i> <span class="desc-add">Kembali</span>
                        </a>  
                        <button type="submit" class="btn btn-primary rounded-3">                        
                            {{ $pengampuGuru->isEmpty() ? 'Simpan Penugasan' : 'Simpan Perubahan' }} <i class="bi bi-save ms-1"></i>
                        </button>
                    </div>                
                </form>
            @endif
        </div>
    </div>

    <x-table.card
        title="Data Pengampu Mata Pelajaran"
        subtitle="Ringkasan guru beserta jumlah mata pelajaran dan kelas yang diampu"
    >
        <x-slot:badge>
            <span class="badge bg-primary rounded-pill px-2 py-1">
                {{ $pengampu->count() }} <span class="text-badge">Guru</span>
            </span>
        </x-slot:badge>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Total Kelas</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengampu as $assignments)
                        @php
                            $first = $assignments->first();
                            $uniqueMapel = $assignments->pluck('mapel')->filter()->unique('id');
                            $totalKelas = $assignments->pluck('kelas_id')->unique()->count();
                        @endphp
                        <tr>
                            <td><span class="badge bg-light text-dark rounded-circle">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $first->guru->pegawai?->nama ?? $first->guru->nama ?? '-' }}</div>
                                        <small class="text-muted">NIP : {{ $first->guru->pegawai?->nip ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>                            
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-book-half text-primary me-2"></i>
                                    @foreach ($uniqueMapel as $mapelItem)
                                        <span class="text-muted">{{ $mapelItem->nama_mapel }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>                               
                                @if($totalKelas  == 0)
                                    <span class="badge-status selesai">
                                        0 kelas
                                    </span>
                                @elseif($totalKelas  >= 15)
                                    <span class="badge-status nonaktif">
                                        {{ $jumlahKelas }} Kelas
                                    </span>
                                @elseif($totalKelas  >= 8)
                                    <span class="badge-status pending">
                                        {{ $totalKelas  }} Kelas
                                    </span>
                                @else
                                    <span class="badge-status aktif">
                                        {{ $totalKelas  }} Kelas
                                    </span>
                                @endif
                            </td>                            
                            <td>
                                <form action="{{ route('superadmin.pengampu-mapel.destroy', $first->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 py-0 btn-confirm"
                                        data-title="Hapus Pengampu Mapel"
                                        data-message="Seluruh penugasan guru ini pada tahun ajaran aktif akan dihapus."
                                        data-button="btn-danger">
                                        Hapus <i class="bi bi-trash ms-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-journal-x display-5 text-secondary"></i>
                                <p class="mt-3 mb-0 text-muted">Belum ada data pengampu mata pelajaran.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </x-table.card>    
</div>

@if ($guruTerpilih)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const assignmentCheckboxes = document.querySelectorAll('.pengampu-kelas-checkbox');
            const mapelSections = document.querySelectorAll('.pengampu-mapel-section');
            const globalSummary = document.getElementById('pengampu-global-summary');
            const mapelSearch = document.getElementById('pengampu-mapel-search');
            const emptySearch = document.getElementById('pengampu-mapel-empty-search');

            function updateMapelCount(mapelId) {
                const counter = document.querySelector('[data-mapel-count="' + mapelId + '"]');
                const total = document.querySelectorAll('.pengampu-kelas-checkbox[data-mapel-id="' + mapelId + '"]:checked').length;

                if (counter) {
                    counter.textContent = total + ' kelas';
                }
            }

            function updateGlobalSummary() {
                const assignedMapel = new Set();
                const assignedKelas = new Set();

                assignmentCheckboxes.forEach(function (checkbox) {
                    if (checkbox.checked) {
                        assignedMapel.add(checkbox.dataset.mapelId);
                        assignedKelas.add(checkbox.value);
                    }
                });

                if (globalSummary) {
                    globalSummary.textContent = assignedMapel.size + ' Mapel ditugaskan \u00b7 ' + assignedKelas.size + ' Kelas ditugaskan';
                }
            }

            function updateAllCounts() {
                document.querySelectorAll('.pengampu-count').forEach(function (counter) {
                    updateMapelCount(counter.dataset.mapelCount);
                });
                updateGlobalSummary();
            }

            assignmentCheckboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    updateMapelCount(checkbox.dataset.mapelId);
                    updateGlobalSummary();
                });
            });

            const checkAllButton = document.getElementById('checkAllPengampu');
            if (checkAllButton) {
                checkAllButton.addEventListener('click', function () {
                    assignmentCheckboxes.forEach(function (checkbox) { checkbox.checked = true; });
                    updateAllCounts();
                });
            }

            const uncheckAllButton = document.getElementById('uncheckAllPengampu');
            if (uncheckAllButton) {
                uncheckAllButton.addEventListener('click', function () {
                    assignmentCheckboxes.forEach(function (checkbox) { checkbox.checked = false; });
                    updateAllCounts();
                });
            }

            if (mapelSearch) {
                mapelSearch.addEventListener('input', function () {
                    const query = mapelSearch.value.trim().toLowerCase();
                    let visibleSections = 0;

                    mapelSections.forEach(function (section) {
                        const isVisible = section.dataset.mapelName.includes(query);
                        section.classList.toggle('d-none', !isVisible);
                        visibleSections += isVisible ? 1 : 0;
                    });

                    if (emptySearch) {
                        emptySearch.classList.toggle('d-none', visibleSections > 0);
                    }
                });
            }

            mapelSections.forEach(function (section) {
                const collapse = section.querySelector('.collapse');
                const trigger = section.querySelector('[data-bs-toggle="collapse"]');
                const chevron = section.querySelector('.collapse-chevron');

                if (collapse && trigger && chevron) {
                    collapse.addEventListener('shown.bs.collapse', function () {
                        trigger.setAttribute('aria-expanded', 'true');
                        chevron.classList.replace('bi-chevron-down', 'bi-chevron-up');
                    });
                    collapse.addEventListener('hidden.bs.collapse', function () {
                        trigger.setAttribute('aria-expanded', 'false');
                        chevron.classList.replace('bi-chevron-up', 'bi-chevron-down');
                    });
                }
            });

            updateAllCounts();
        });
    </script>
@endif
@endsection
