<?php

use App\Http\Controllers\Api\MobilController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — BTNG Backend (Showroom Mobil)
|--------------------------------------------------------------------------
| Seluruh endpoint tugas akhir berada di bawah prefix /api secara otomatis.
| Dokumentasi interaktif tersedia di /docs/api (Scramble).
*/

Route::apiResource('mobil', MobilController::class);
