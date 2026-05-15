<a
    class="menu-item {{ $active ? 'active' : '' }}"
    href="{{ $href }}"
>

    <div class="menu-left">

        <i class="bi {{ $icon }}"></i>

        <span>
            {{ $slot }}
        </span>

    </div>

</a>