<div class="card border-0 shadow-sm rounded-4">

    <div class="card-header bg-white border-0 py-4 table-card-header">  

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

            <div class="table-card-title">

                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">

                    <h4 class="fw-bold mb-0">
                        {{ $title }}
                    </h4>

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
                <div class="table-card-actions">
                    {{ $actions }}
                </div>
            @endisset

        </div>

    </div>

    <div class="card-body overflow-visible">
        {{ $slot }}
    </div>

</div>