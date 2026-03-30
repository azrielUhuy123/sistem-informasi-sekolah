<style>
.table tbody {
    display: table-row-group !important;
}

.table tr {
    height: auto !important;
}

.table td, .table th {
    padding: 10px 12px !important;
    vertical-align: middle !important;
}
</style>

<div class="table-responsive">
<table class="table table-hover align-middle table-sm mb-0">

    <thead class="table-light">
    <tr>
        @if(($crudMode ?? null) !== 'internal_user')
        <th style="width:40px;">
            <input id="selectAll" type="checkbox">
        </th>
        @endif

        @foreach($columns as $col)
        <th>
            <div class="d-flex align-items-center gap-1">
                <span>{{ $col['label'] }}</span>

                <span class="sort-icon-btn" onclick="applySort('{{ $col['key'] }}')" style="cursor:pointer;">
                    @include('components.crud.sort-icon', ['column' => $col['key']])
                </span>
            </div>
        </th>
        @endforeach

        @if(!empty($actions))
            <th class="text-center" style="width:150px;">Aksi</th>
        @endif

    </tr>
    </thead>

    <tbody>
    @forelse($rows as $row)
    <tr data-id="{{ $row->id }}" data-deleted="{{ $row->deleted_at ? 'yes' : 'no' }}">

        @if(($crudMode ?? null) !== 'internal_user')
        <td>
            <input type="checkbox" class="row-checkbox" value="{{ $row->id }}">
        </td>
        @endif

        @foreach($columns as $col)
        <td class="text-start">

            @if(isset($col['format']))
                {!! $col['format']($row) !!}
            @else
                {{ data_get($row, $col['key']) ?? '-' }}
            @endif

        </td>
        @endforeach

        @if(!empty($actions))
<td class="text-center">
    {{-- @include('components.crud.actions', ['row'=>$row, 'actions'=>$actions]) --}}
</td>
@endif

    </tr>
    @empty
        <tr>
            <td colspan="100%" class="text-center text-muted py-4">
                Tidak ada data
            </td>
        </tr>
    @endforelse
    </tbody>

</table>
</div>