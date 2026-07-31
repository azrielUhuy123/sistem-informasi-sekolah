<div class="card border-0 shadow-sm rounded-4">

    <div class="card-header bg-white border-0 py-4">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div class="ml-3">

                <div class="d-flex align-items-center gap-2 mb-1">

                    <h5 class="fw-bold mb-0">
                        {{ $title }}
                    </h5>

                    @isset($badge)
                        {{ $badge }}
                    @endisset

                </div>

                @isset($subtitle)
                    <small class="text-muted">
                        {{ $subtitle }}
                    </small>
                @endisset

            </div>

            @isset($actions)
                <div class="d-flex align-items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset

        </div>

    </div>

    <div class="card-body overflow-visible">
        {{ $slot }}
    </div>

</div>