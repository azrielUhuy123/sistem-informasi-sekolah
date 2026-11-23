<?php

namespace App\Enums;

class UserRole
{
    public const SUPERADMIN = 'superadmin';
    public const KEPSEK = 'superadmin';
    public const ADMIN      = 'admin';
    public const TU         = 'tu'; // Legacy value; migrated to Guru and never grants feature access.
    public const GURU       = 'guru';
    public const MURID      = 'siswa';
}
