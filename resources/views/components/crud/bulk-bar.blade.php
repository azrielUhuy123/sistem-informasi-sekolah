@if(($crudMode ?? null) !== 'internal_user')
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
@endif