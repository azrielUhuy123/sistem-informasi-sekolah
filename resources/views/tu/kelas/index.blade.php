@include('components.crud.index', [
    'title' => 'Manajemen Data Kelas',
    'rows' => $kelas,
    'columns' => [
        ['key' => 'nama_kelas', 'label' => 'Nama Kelas', 'sortable' => true],
        ['key' => 'tingkat', 'label' => 'Tingkat', 'sortable' => true],
        ['key' => 'tahun_ajaran', 'label' => 'Tahun Ajaran', 'sortable' => true],
    ],
    'actions' => [
        'detail' => 'tu.kelas.detail',
        'edit' => 'tu.kelas.edit',
        'delete' => 'tu.kelas.destroy',
        'restore' => 'tu.kelas.restore',
        'forceDelete' => 'tu.kelas.forceDelete',
    ],
    'routeCreate' => route('tu.kelas.create'),
    'routeBulk' => 'tu.kelas.bulk',
])
