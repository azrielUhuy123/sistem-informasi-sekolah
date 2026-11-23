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

    UserRole::GURU => [
        [
            'label' => 'Dashboard',
            'route' => 'guru.dashboard-gurubidang',
        ],
    ],

    UserRole::MURID => [
        [
            'label' => 'Dashboard',
            'route' => 'siswa.dashboard.index',
        ],
    ],

];
