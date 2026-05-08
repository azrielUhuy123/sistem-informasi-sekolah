<div class="topbar">
    <h4>SDN MEKAR JAYA</h4>

    <div class="topbar-right">
        <div class="search-box">
            <input
                type="text"
                id="globalSearch"
                class="form-control shadow-sm"
                placeholder="Cari siswa / kelas..."
            >
            <div
                id="searchResults"
                class="bg-white border rounded shadow-sm mt-1"
                style="display:none; position:absolute; width:100%; z-index:1000;"
            ></div>
        </div>

        <button id="darkToggle" class="btn">🌙</button>

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
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
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