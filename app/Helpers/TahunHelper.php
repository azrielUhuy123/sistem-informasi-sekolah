<?php

use App\Models\TahunAjaran;

if (!function_exists('current_tahun_id')) {
    function current_tahun_id()
    {
        return request('ta') ?? TahunAjaran::aktif()?->id;
    }
}

if (!function_exists('current_tahun')) {
    function current_tahun()
    {
        return TahunAjaran::find(current_tahun_id());
    }
}
if (!function_exists('max_jp_kelas')) {

    function max_jp_kelas($tingkat)
    {
        return match ((int) $tingkat) {

            1, 2 => 29,

            3, 4 => 35,

            5 => 42,

            6 => 41,

            default => 35,
        };
    }

}