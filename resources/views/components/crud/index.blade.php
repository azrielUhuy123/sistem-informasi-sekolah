<div class="card shadow-sm border-0">

    <div class="card-body">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-0">{{ $title }}</h5>
                <small class="text-muted">
                    {{ $rows->total() ?? count($rows) }} data
                </small>
            </div>

            @if(isset($routeCreate))
                <a href="{{ $routeCreate }}" class="btn btn-primary btn-sm">
                    + Tambah
                </a>
            @endif
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
            @include('components.crud.table', [
                'rows' => $rows,
                'columns' => $columns,
                'actions' => $actions ?? []
            ])
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($rows, 'links'))
            <div class="mt-3">
                {{ $rows->links() }}
            </div>
        @endif

    </div>
</div>