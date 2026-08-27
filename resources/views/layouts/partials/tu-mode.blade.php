<div>

    <div class="sidebar-title">

        Mode Aktif

    </div>

    <div class="dropdown">

        <button
            class="btn border w-100 text-center dropdown-toggle mb-4"
            data-bs-toggle="dropdown"
        >

            @if(session('tu_mode') == 'ppdb')

                PPDB

            @else

                Operasional

            @endif

        </button>

        <ul class="dropdown-menu w-100">

            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('tu.switch-mode', 'operasional') }}"
                >

                    Operasional

                </a>

            </li>

            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('tu.switch-mode', 'ppdb') }}"
                >

                    PPDB

                </a>

            </li>

        </ul>

    </div>

</div>