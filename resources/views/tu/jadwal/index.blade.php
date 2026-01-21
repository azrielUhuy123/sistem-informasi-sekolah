@include('components.crud.index', [
    'title' => 'Manajemen Data Jadwal',
    'rows' => $jadwal,
    'columns' => [
        ['key' => 'kelas.nama_kelas', 'label' => 'Kelas'],
        ['key' => 'guru.nama', 'label' => 'Guru'],
        ['key' => 'mapel.nama_mapel', 'label' => 'Mata Pelajaran'],
        ['key' => 'hari', 'label' => 'Hari'],
        ['key' => 'jam_mulai', 'label' => 'Jam Mulai'],
        ['key' => 'jam_selesai', 'label' => 'Jam Selesai'],
    ],
    'actions' => [
        'detail' => 'tu.jadwal.detail',
        'edit' => 'tu.jadwal.edit',
        'delete' => 'tu.jadwal.destroy',
    ],
    'routeCreate' => route('tu.jadwal.create'),
])
