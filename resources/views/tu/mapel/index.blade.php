@include('components.crud.index', [
    'title' => 'Manajemen Mata Pelajaran',
    'rows' => $mata_pelajaran,
    'columns' => [
        ['key' => 'nama_mapel', 'label' => 'Nama Mata Pelajaran', 'sortable' => true],
    ],
    'actions' => [
        'detail' => 'tu.mapel.detail',
        'edit' => 'tu.mapel.edit',
        'delete' => 'tu.mapel.destroy',
        'restore' => 'tu.mapel.restore',
        'forceDelete' => 'tu.mapel.forceDelete',
    ],
    'routeCreate' => route('tu.mapel.create'),
    'routeBulk' => 'tu.mapel.bulk',
])
