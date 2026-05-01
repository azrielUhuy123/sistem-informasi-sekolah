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