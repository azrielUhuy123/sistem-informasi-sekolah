<?php

return [
    'features' => [
        'tu.ppdb' => [
            'label' => 'Operasional PPDB',
            'roles' => [],
        ],
        'tu.academic' => [
            'label' => 'Administrasi Akademik TU',
            'roles' => [],
        ],
        'tu.documents' => [
            'label' => 'Dokumen dan Surat TU',
            'roles' => [],
        ],
        'guru.academic' => [
            'label' => 'Pembelajaran dan Akademik Guru',
            'roles' => [],
        ],
        'guru.extracurricular' => [
            'label' => 'Ekstrakurikuler Guru',
            'roles' => [],
        ],
        'siswa.elearning' => [
            'label' => 'E-learning Siswa',
            'roles' => ['siswa'],
        ],
        'siswa.academic' => [
            'label' => 'Jadwal, Nilai, dan Absensi Siswa',
            'roles' => ['siswa'],
        ],
        'siswa.extracurricular' => [
            'label' => 'Ekstrakurikuler Siswa',
            'roles' => ['siswa'],
        ],
        'siswa.documents' => [
            'label' => 'Dokumen Siswa',
            'roles' => ['siswa'],
        ],
        'siswa.letter' => [
            'label' => 'Nomor Surat Siswa',
            'roles' => ['siswa'],
        ],
        'superadmin.assignment' => [
            'label' => 'Penugasan Akademik',
            'roles' => ['superadmin'],
        ],
        'superadmin.approval' => [
            'label' => 'Approval dan Validasi',
            'roles' => ['superadmin'],
        ],
    ],
];
