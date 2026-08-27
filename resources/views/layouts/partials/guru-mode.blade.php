@php
    $guru = auth()->user()->guru;

    $isPembinaEkskul = $guru
        ? \App\Models\PembinaEkskul::where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->exists()
        : false;
@endphp

<div>

    <div class="sidebar-title">
        Mode Aktif
    </div>

    <div class="dropdown">

        <button
            class="btn btn-light border w-100 text-center dropdown-toggle mb-4"
            data-bs-toggle="dropdown">

            @if(session('guru_mode') == 'ekskul' && $isPembinaEkskul)
                Pembina Ekskul
            @else
                Akademik
            @endif

        </button>

        <ul class="dropdown-menu w-100">

            <li>
                <a
                    class="dropdown-item"
                    href="{{ route('guru.switch-mode', 'akademik') }}">
                    Akademik
                </a>
            </li>

            @if($isPembinaEkskul)
                <li>
                    <a
                        class="dropdown-item"
                        href="{{ route('guru.switch-mode', 'ekskul') }}">
                        Pembina Ekskul
                    </a>
                </li>
            @endif

        </ul>

    </div>

</div>