<?php

namespace App\Http\Controllers;

class GatewayController extends Controller
{
    public function index()
    {
        return view('gateway.index');
    }

    public function ppdb()
    {
        return view('ppdb.index');
    }

    public function informasi()
    {
        return view('informasi.index');
    }
}
