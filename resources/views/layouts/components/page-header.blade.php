<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            {{ $title }}
        </h3>

        @if(isset($subtitle))

            <div class="text-muted small">
                {{ $subtitle }}
            </div>

        @endif

    </div>

    <div>

        {{ $actions ?? '' }}

    </div>

</div>