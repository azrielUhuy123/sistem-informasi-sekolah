@php
    $guru = auth()->user()->guru;

    $isPembinaEkskul = $guru
        ? \App\Models\PembinaEkskul::where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', current_tahun_id())
            ->exists()
        : false;
@endphp

<div class="px-3 mb-3">

    <label class="small text-muted mb-2 d-block">
        Mode Aktif
    </label>

    <div class="dropdown w-100">

        <button
            class="btn btn-light border w-100 text-start dropdown-toggle"
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