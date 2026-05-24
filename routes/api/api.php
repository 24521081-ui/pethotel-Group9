<?php

use App\Http\Controllers\Api\HealthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Laravel tu dong gan prefix /api cho file nay.
| Web routes hien thi trang, API routes tra ve JSON cho frontend.
*/

Route::get('/', [HealthController::class, 'index'])->name('api.index');

require __DIR__.'/public/index.php';
require __DIR__.'/authentication/index.php';
require __DIR__.'/customer/main.php';
require __DIR__.'/manager/main.php';
require __DIR__.'/ceo/api.php';
