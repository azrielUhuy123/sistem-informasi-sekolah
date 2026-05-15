<button
    class="menu-item border-0 bg-transparent text-start"
    onclick="toggleMenu('{{ $menu }}','{{ $arrow }}')"
>

    <div class="menu-left">

        <i class="bi {{ $icon }}"></i>

        <span>
            {{ $title }}
        </span>

    </div>

    <i
        class="bi bi-chevron-right dropdown-arrow"
        id="{{ $arrow }}"
    ></i>

</button>