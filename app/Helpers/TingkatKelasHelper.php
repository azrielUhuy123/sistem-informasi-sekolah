<?php

namespace App\Helpers;

final class TingkatKelasHelper
{
    private const BERIKUTNYA = [
        'I' => 'II',
        'II' => 'III',
        'III' => 'IV',
        'IV' => 'V',
        'V' => 'VI',
        'VI' => 'LULUS',
    ];

    public static function berikutnya(?string $tingkat): ?string
    {
        return self::BERIKUTNYA[$tingkat] ?? null;
    }
}