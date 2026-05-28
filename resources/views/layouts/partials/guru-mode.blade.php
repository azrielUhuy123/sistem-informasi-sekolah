<div class="px-3 mb-3">

    <label class="small text-muted mb-2 d-block">

        Mode Aktif

    </label>

    <div class="dropdown w-100">

        <button
            class="btn btn-light border w-100 text-start dropdown-toggle"
            data-bs-toggle="dropdown"
        >

            @if(session('guru_mode') == 'ekskul')

                Pembina Ekskul

            @else

                Akademik

            @endif

        </button>

        <ul class="dropdown-menu w-100">

            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('guru.switch-mode', 'akademik') }}"
                >

                    Akademik

                </a>

            </li>

            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('guru.switch-mode', 'ekskul') }}"
                >

                    Pembina Ekskul

                </a>

            </li>

        </ul>

    </div>

</div>