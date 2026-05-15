<div class="card border-0 shadow-sm rounded-4">

    @if(isset($header))

        <div class="card-header bg-white border-0">

            {{ $header }}

        </div>

    @endif

    <div class="card-body">

        {{ $slot }}

    </div>

</div>