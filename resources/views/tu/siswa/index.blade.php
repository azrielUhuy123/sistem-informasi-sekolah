@include('components.crud.index', [
    'title' => 'Manajemen Data Siswa',
    'rows' => $siswa,
    'columns' => [
        ['key' => 'nama', 'label' => 'Nama Siswa', 'sortable' => true],
        ['key' => 'nis', 'label' => 'NIS', 'sortable' => true],
        ['key' => 'tanggal_lahir', 'label' => 'Tanggal Lahir', 'sortable' => true],
        ['key' => 'kelas_id', 'label' => 'Kelas', 'sortable' => true],
    ],
    'actions' => [
        'detail' => 'tu.siswa.detail',
        'edit' => 'tu.siswa.edit',
        'delete' => 'tu.siswa.destroy',
        'restore' => 'tu.siswa.restore',
        'forceDelete' => 'tu.siswa.forceDelete',
    ],
    'routeCreate' => route('tu.siswa.create'),
    'routeBulk' => 'tu.siswa.bulk',
])
