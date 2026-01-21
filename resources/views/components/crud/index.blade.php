@extends($layout ?? 'layouts.tu')

@section('title', $title)

@section('content')

{{-- =============================
      GLOBAL STYLING
============================= --}}
<style>
/* Wrapper styling */
.highlight {
    background: #fffd8a;
    padding: 2px 4px;
    border-radius: 4px;
    font-weight: 700;
}

.search-wrapper { position: relative; }

.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.6;
}

.clear-icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    opacity: 0.5;
    display: none;
    transition: 0.2s;
}
.clear-icon:hover { opacity: 1; }


.table-responsive {
    border-radius: 10px;
    overflow: hidden;
}

/* Header */
.table thead th {
    background: #f5f7ff !important;
    font-weight: 700;
    padding: 14px;
    color: #2f3240;
    border-bottom: 2px solid #e0e4f4 !important;
}

/* Body */
.table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-color: #eef1f9 !important;
}

/* Hover row */
.table-hover tbody tr:hover {
    background: #f7faff !important;
}

/* Checkbox column */
.table th[data-col="select"],
.table td[data-col="select"] {
    width: 48px;
    text-align: center;
}

/* Icon button universal */
.icon-btn {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: transparent;
    cursor: pointer;
    border: none;
    transition: 0.15s ease;
    margin-right: 4px;
}

.icon-btn svg {
    width: 20px;
    height: 20px;
    stroke-width: 2;
    stroke: #000 !important;
}

.icon-btn:hover {
    transform: translateY(-2px);
    opacity: 0.85;
}

/* Disabled */
.icon-btn.disabled svg {
    stroke: #999 !important;
    opacity: 0.5;
}

/* Sort */
.th-sort {
    display: flex;
    align-items: center;
    gap: 6px;
}

.sort-icon-btn {
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 26px;     /* tambah ruang */
    height: 26px;    /* tambah ruang */

    padding: 2px;    /* supaya icon tidak menempel ke border */
    margin-top: -2px; /* naikkan sedikit agar sejajar */
}
.sort-icon-btn svg {
    width: 20px;
    height: 20px;
    stroke: #6c757d;
}

/* Bulk Action Bar */
#bulkActionBar {
    display: none;
    padding: 10px 16px;
    background: #eef1ff;
    border: 1px solid #d8dcf7;
    border-radius: 10px;
    margin-bottom: 12px;
    justify-content: space-between;
}

.bulk-btn {
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    color: #fff;
}

.bulk-delete { background: #e74c3c; }
.bulk-delete:hover { background: #c0392b; }

.bulk-restore { background: #2ecc71; }
.bulk-restore:hover { background: #27ae60; }

.bulk-force { background: #2d3436; }
.bulk-force:hover { background: #1e272e; }

/* Pagination */
.simple-pagination {
    display: flex;
    gap: 6px;
}

.simple-pagination .page-link,
.simple-pagination .active-page,
.simple-pagination .disabled-page {
    border: 1px solid #ddd;
    padding: 6px 12px;
    border-radius: 6px;
}

.active-page {
    background: #4d5bd8;
    color: #fff;
}

.disabled-page {
    opacity: 0.4;
}

</style>

{{-- ============================
        HEADER
============================ --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $title }}</h1>

    @if(isset($routeCreate))
        <a href="{{ $routeCreate }}" class="btn btn-primary">+ Tambah</a>
    @endif
</div>

{{-- ============================
        SEARCH & FILTER
============================ --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex gap-2 flex-wrap justify-content-between">
            <div class="search-wrapper" style="max-width:600px; position:relative;">

            <!-- ICON SEARCH -->
                <span class="search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7" stroke="#777" stroke-width="2" fill="none"/>
                        <line x1="16.5" y1="16.5" x2="22" y2="22" stroke="#777" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>

                <!-- INPUT -->
                <input
                    id="tableSearch"
                    class="form-control ps-5 pe-5"
                    placeholder="Cari..."
                    value="{{ request('q') }}"
                    autocomplete="off"
                >

                <!-- ICON CLEAR -->
                <span class="clear-icon" id="clearSearch">
                    <svg width="16" height="16" viewBox="0 0 24 24">
                        <line x1="4" y1="4" x2="20" y2="20" stroke="#666" stroke-width="2"/>
                        <line x1="20" y1="4" x2="4" y2="20" stroke="#666" stroke-width="2"/>
                    </svg>
                </span>

            </div>
            <div class="d-flex gap-2">
                <select id="perPage" class="form-select">
                    @foreach([10,15,25,50] as $n)
                    <option value="{{ $n }}" {{ request('per_page')==$n ? 'selected':'' }}>{{ $n }}</option>
                    @endforeach
                </select>



                {{-- Deleted Filter --}}
                <select id="deletedFilter" class="form-select">
                    <option value="active" {{ request('deleted')=='active' ? 'selected':'' }}>Tidak Terhapus</option>
                    <option value="only" {{ request('deleted')=='only' ? 'selected':'' }}>Hanya Terhapus</option>
                    <option value="all" {{ request('deleted')=='all' ? 'selected':'' }}>Semua</option>
                </select>
            </div>

        </div>
    </div>
</div>

{{-- ============================
        BULK ACTION BAR
============================ --}}
<div id="bulkActionBar">
    <div><span id="selectedCount">0</span> item dipilih</div>

    <div class="d-flex gap-2">
        @if(!request()->has('deleted') || request('deleted') === 'active')
        <button id="bulkDelete" class="btn btn-danger">Hapus</button>
        <button id="bulkForce" class="btn btn-dark">Hapus Permanen</button>
        @endif


        @if(request('deleted') === 'only')
        <button id="bulkRestore" class="btn btn-success">Pulihkan</button>
        <button id="bulkForce" class="btn btn-dark">Hapus Permanen</button>
        @endif

        @if(request('deleted') === 'all')
        <button id="bulkDelete" class="btn btn-danger">Hapus</button>
        <button id="bulkRestore" class="btn btn-success">Pulihkan</button>
        <button id="bulkForce" class="btn btn-dark">Hapus Permanen</button>
        @endif

    </div>
</div>

{{-- ============================
        TABLE
============================ --}}
<div class="table-responsive">
<table class="table table-hover">
    <thead>
    <tr>
        <th data-col="select">
            <input id="selectAll" type="checkbox">
        </th>

        @foreach($columns as $col)
        <th data-col="{{ $col['key'] }}">
            <div class="th-sort">
                {{ $col['label'] }}

                @if(isset($col['sortable']) && $col['sortable'])
                <span class="sort-icon-btn" onclick="applySort('{{ $col['key'] }}')">
                    @include('components.crud.sort-icon', ['column' => $col['key']])
                </span>
                @endif
            </div>
        </th>
        @endforeach

        <th data-col="aksi">Aksi</th>
    </tr>
    </thead>

    <tbody>
    @forelse($rows as $row)
    <tr data-id="{{ $row->id }}" data-deleted="{{ $row->deleted_at ? 'yes' : 'no' }}">

        <td data-col="select">
            <input type="checkbox" class="row-checkbox" value="{{ $row->id }}">
        </td>

       @foreach($columns as $col)
        <td data-col="{{ $col['key'] }}">
            @if(isset($col['format']))
                {!! $col['format']($row) !!}
            @else
                {{ data_get($row, $col['key']) }}
            @endif
        </td>
        @endforeach


        <td data-col="aksi">
            @include('components.crud.actions', ['row'=>$row, 'actions'=>$actions])
        </td>
    </tr>
    @empty
        <tr><td colspan="99" class="text-center py-4">Tidak ada data</td></tr>
    @endforelse
    </tbody>

</table>
</div>

{{-- ============================
        PAGINATION
============================ --}}
<div class="d-flex justify-content-between mt-3">
    <div>
        Menampilkan {{ $rows->firstItem() }} - {{ $rows->lastItem() }} dari {{ $rows->total() }} data
    </div>

    <div class="simple-pagination">
        @if($rows->onFirstPage())
        <span class="disabled-page">«</span>
        @else
        <a class="page-link" href="{{ $rows->previousPageUrl() }}">«</a>
        @endif

        @foreach($rows->getUrlRange(1, $rows->lastPage()) as $page => $url)
            @if($page == $rows->currentPage())
                <span class="active-page">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="page-link">{{ $page }}</a>
            @endif
        @endforeach

        @if($rows->hasMorePages())
        <a class="page-link" href="{{ $rows->nextPageUrl() }}">»</a>
        @else
        <span class="disabled-page">»</span>
        @endif
    </div>
</div>

@endsection

@section('scripts')
@include('components.crud.scripts')
@endsection
