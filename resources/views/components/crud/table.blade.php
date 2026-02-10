<div class="table-responsive">
<table class="table table-hover">
    <thead>
    <tr>
        @if(($crudMode ?? null) !== 'internal_user')
        <th data-col="select">
            <input id="selectAll" type="checkbox">
        </th>
        @endif

        @foreach($columns as $col)
        <th data-col="{{ $col['key'] }}">
            <div class="th-sort">
                {{ $col['label'] }}

                <span class="sort-icon-btn" onclick="applySort('{{ $col['key'] }}')">
                    @include('components.crud.sort-icon', ['column' => $col['key']])
                </span>

            </div>
        </th>
        @endforeach

        @if(!empty($actions))
            <th data-col="aksi">Aksi</th>
        @endif

    </tr>
    </thead>

    <tbody>
    @forelse($rows as $row)
    <tr data-id="{{ $row->id }}" data-deleted="{{ $row->deleted_at ? 'yes' : 'no' }}">
        @if(($crudMode ?? null) !== 'internal_user')
        <td data-col="select">
            <input type="checkbox" class="row-checkbox" value="{{ $row->id }}">
        </td>
        @endif

       @foreach($columns as $col)
        <td data-col="{{ $col['key'] }}">
            @if(isset($col['format']))
                {!! $col['format']($row) !!}
            @else
                {{ data_get($row, $col['key']) }}
            @endif
        </td>
        @endforeach


        @if(!empty($actions))
        <td data-col="aksi">
            @include('components.crud.actions', ['row'=>$row, 'actions'=>$actions])
        </td>
        @endif

    </tr>
    @empty
        <tr><td colspan="99" class="text-center py-4">Tidak ada data</td></tr>
    @endforelse
    </tbody>

</table>
</div>