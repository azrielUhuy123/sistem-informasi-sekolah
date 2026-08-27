<?php

use App\Http\Controllers\Api\GatewayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/gateway/statistik', [GatewayController::class, 'statistik']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

