@include('components.crud.index', [
    'title' => 'Manajemen Data Guru',
    'rows' => $guru,
    'columns' => [
        ['key' => 'nama', 'label' => 'Nama Guru', 'sortable' => true],
        ['key' => 'nip', 'label' => 'NIP', 'sortable' => true],
        ['key' => 'mapel.nama_mapel', 'label' => 'Mata Pelajaran'],
    ],
    'actions' => [
        'detail' => 'tu.guru.detail',
        'edit' => 'tu.guru.edit',
        'delete' => 'tu.guru.destroy',
        'restore' => 'tu.guru.restore',
        'forceDelete' => 'tu.guru.forceDelete',
    ],
    'routeCreate' => route('tu.guru.create'),
    'routeBulk' => 'tu.guru.bulk',
])
