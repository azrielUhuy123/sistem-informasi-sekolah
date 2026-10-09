@include('layouts.partials.modal-profile')
<div class="topbar">
    <div class="d-flex align-items-center gap-2">

        <button
            id="mobileSidebarToggle"
            class="btn btn-light d-md-none border-0 shadow-sm p-2"
            type="button"
            aria-label="Buka menu"
            aria-expanded="false"
        >
            <i class="bi bi-list fs-5"></i>
        </button>

        <img
            src="{{ asset('assets/images/logo-kolaan.png') }}"
            alt="Logo"
            width="40"
            height="40"
            class="rounded-circle logo-image"
        >

        <h3 class="text-primary school-name">

            SDN MEKAR JAYA

        </h3>

    </div>

    <div class="topbar-right">

        @include('layouts.partials.notifications')

        <div class="dropdown">
            <a
                class="btn border rounded-3 p-2 dropdown-toggle text-primary d-flex align-items-center gap-2"
                href="#"
                data-bs-toggle="dropdown"
            >
                <i class="bi bi-person-circle text-primary"></i>
                <small class="mb-0 text-primary user-name">{{ Auth::user()->name }}</small>
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

