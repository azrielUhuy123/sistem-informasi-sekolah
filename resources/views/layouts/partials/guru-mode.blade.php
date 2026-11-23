@php
    $hasAcademic = auth()->user()->hasAnyAssignmentMenu(array_merge(
        array_keys(config('assignment_menus.types.wali.menus', [])),
        array_keys(config('assignment_menus.types.guru.menus', []))
    ));
    $canEkskul = auth()->user()->hasAnyAssignmentMenu(
        array_keys(config('assignment_menus.types.ekskul.menus', []))
    );
@endphp

<div>

    <div class="sidebar-title">
        Mode Aktif
    </div>

    <div class="dropdown">

        <button
            class="btn btn-light border w-100 text-center dropdown-toggle mb-4"
            data-bs-toggle="dropdown">

            @if(session('guru_mode') == 'ekskul' && $canEkskul)
                Pembina Ekskul
            @else
                Akademik
            @endif

        </button>

        <ul class="dropdown-menu w-100">

            @if($hasAcademic)
                <li>
                    <a class="dropdown-item" href="{{ route('guru.switch-mode', 'akademik') }}">Akademik</a>
                </li>
            @endif

            @if($canEkskul)
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