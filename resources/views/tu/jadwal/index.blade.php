@include('components.crud.index', [
    'title' => 'Manajemen Data Jadwal',
    'rows' => $jadwal,
    'columns' => [
        ['key' => 'kelas_id', 'label' => 'Kelas', 'sortable' => true],
        ['key' => 'guru_id', 'label' => 'Guru', 'sortable' => true],
        ['key' => 'mapel_id', 'label' => 'Mata Pelajaran', 'sortable' => true],
        ['key' => 'hari', 'label' => 'Hari', 'sortable' => true],
        ['key' => 'jam_mulai', 'label' => 'Jam Mulai', 'sortable' => true],
        ['key' => 'jam_selesai', 'label' => 'Jam Selesai', 'sortable' => true],
    ],
    'actions' => [
        'detail' => 'tu.jadwal.detail',
        'edit' => 'tu.jadwal.edit',
        'delete' => 'tu.jadwal.destroy',
        'restore' => 'tu.jadwal.restore',
        'forceDelete' => 'tu.jadwal.forceDelete',
    ],
    'routeCreate' => route('tu.jadwal.create'),
    'routeBulk' => 'tu.jadwal.bulk',
])
