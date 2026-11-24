<?php

return [
    'fields' => [
        'nama' => [
            'label' => 'Nama lengkap',
            'category' => 'Identitas pribadi',
            'type' => 'text',
            'max' => 255,
            'evidence' => false,
        ],
        'nip' => [
            'label' => 'NIP',
            'category' => 'Identitas kepegawaian',
            'type' => 'text',
            'max' => 50,
            'evidence' => true,
        ],
        'nuptk' => [
            'label' => 'NUPTK',
            'category' => 'Identitas kepegawaian',
            'type' => 'text',
            'max' => 50,
            'evidence' => true,
        ],
        'tempat_lahir' => [
            'label' => 'Tempat lahir',
            'category' => 'Data pribadi',
            'type' => 'text',
            'max' => 100,
            'evidence' => false,
        ],
        'tanggal_lahir' => [
            'label' => 'Tanggal lahir',
            'category' => 'Data pribadi',
            'type' => 'date',
            'evidence' => true,
        ],
        'agama' => [
            'label' => 'Agama',
            'category' => 'Data pribadi',
            'type' => 'select',
            'options' => ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'],
            'evidence' => false,
        ],
        'jenis_kelamin' => [
            'label' => 'Jenis kelamin',
            'category' => 'Data pribadi',
            'type' => 'select',
            'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan'],
            'evidence' => false,
        ],
        'status_kawin' => [
            'label' => 'Status perkawinan',
            'category' => 'Data keluarga',
            'type' => 'select',
            'options' => ['K' => 'Menikah', 'TK' => 'Belum menikah'],
            'evidence' => true,
        ],
        'ijazah_terakhir' => [
            'label' => 'Pendidikan terakhir',
            'category' => 'Pendidikan',
            'type' => 'text',
            'max' => 100,
            'evidence' => true,
        ],
        'pengalaman_tahun' => [
            'label' => 'Pengalaman mengajar (tahun)',
            'category' => 'Pengalaman',
            'type' => 'number',
            'evidence' => false,
        ],
        'nomor_sertifikasi' => [
            'label' => 'Nomor sertifikasi',
            'category' => 'Pendidikan dan sertifikasi',
            'type' => 'text',
            'max' => 100,
            'evidence' => true,
        ],
        'tahun_sertifikasi' => [
            'label' => 'Tahun sertifikasi',
            'category' => 'Pendidikan dan sertifikasi',
            'type' => 'number',
            'evidence' => true,
        ],
    ],
];
