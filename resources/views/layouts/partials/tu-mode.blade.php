<div class="px-3 mb-3">

    <label class="small text-muted mb-2 d-block">

        Mode Aktif

    </label>

    <div class="dropdown w-100">

        <button
            class="btn btn-light border w-100 text-start dropdown-toggle"
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