<?php

use App\Enums\UserRole;

return [

    UserRole::SUPERADMIN => [
        [
            'label' => 'Approval User',
            'route' => 'approval.users.index',
        ],
    ],

    UserRole::ADMIN => [
        [
            'label' => 'Data User',
            'route' => 'admin.users.index',
        ],
        [
            'label' => 'Tahun Ajaran',
            'route' => 'admin.tahun-ajaran.index',
        ],
    ],

    UserRole::TU => [
        [
            'label' => 'Data Siswa',
            'route' => 'tu.siswa.index',
        ],
    ],

    UserRole::GURU => [
        [
            'label' => 'Dashboard',
            'route' => 'guru.dashboard',
        ],
    ],

    UserRole::MURID => [
        [
            'label' => 'Dashboard',
            'route' => 'murid.dashboard',
        ],
    ],

];
