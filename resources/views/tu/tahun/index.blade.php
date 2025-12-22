@include('components.crud.index', [
    'title' => 'Manajemen Tahun Ajaran',
    'rows' => $tahun,
    'columns' => [
        ['key' => 'tahun_ajaran', 'label' => 'Tahun Ajaran', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'sortable' => true],
    ],
    'actions' => [
        'detail' => 'tu.tahun.detail',
        'edit' => 'tu.tahun.edit',
        'delete' => 'tu.tahun.destroy',
        'restore' => 'tu.tahun.restore',
        'forceDelete' => 'tu.tahun.forceDelete',
    ],
    'routeCreate' => route('tu.tahun.create'),
    'routeBulk' => 'tu.tahun.bulk',
])
