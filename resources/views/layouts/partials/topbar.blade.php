@include('layouts.partials.modal-profile')
<div class="topbar">
    <div class="d-flex align-items-center gap-2">

        <img
            src="{{ asset('assets/images/logo-kolaan.png') }}"
            alt="Logo"
            width="40"
            height="50"
            class="rounded-circle"
        >

        <h3 class="mt-2">

            SDN MEKAR JAYA

        </h3>

    </div>

    <div class="topbar-right">

        

        <div class="dropdown">
            <a
                class="btn btn-light shadow-sm dropdown-toggle"
                href="#"
                data-bs-toggle="dropdown"
            >
                {{ Auth::user()->name }}
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a
                        class="dropdown-item"
                        href="#"
                        data-bs-toggle="modal"
                        data-bs-target="#profileModal"
                    >
                        Profil
                    </a>
                </li>

                <li><hr class="dropdown-divider"></li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

