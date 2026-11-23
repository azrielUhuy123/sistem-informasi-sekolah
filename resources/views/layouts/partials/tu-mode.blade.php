@php
    $user = auth()->user();
    $hasPpdb = $user->hasAnyAssignmentMenu(array_keys(config('assignment_menus.types.ppdb.menus', [])));
    $hasOperator = $user->hasAnyAssignmentMenu(array_keys(config('assignment_menus.types.operator.menus', [])));
@endphp

<div>

    <div class="sidebar-title">

        Mode Aktif

    </div>

    <div class="dropdown">

        <button
            class="btn border w-100 text-center dropdown-toggle mb-4"
            data-bs-toggle="dropdown"
        >

            @if(session('tu_mode') == 'ppdb' && $hasPpdb)

                PPDB

            @else

                Operasional

            @endif

        </button>

        <ul class="dropdown-menu w-100">

            @if($hasOperator)
            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('tu.switch-mode', 'operasional') }}"
                >

                    Operasional

                </a>

            </li>
            @endif

            @if($hasPpdb)
            <li>
                <a class="dropdown-item" href="{{ route('tu.switch-mode', 'ppdb') }}">PPDB</a>
            </li>
            @endif

        </ul>

    </div>

</div>