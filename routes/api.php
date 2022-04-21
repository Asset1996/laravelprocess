<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PassLogsController;
use App\Http\Controllers\TimingLogsController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->name('me');
});

Route::group([
    'prefix' => 'history'
], function() {
    Route::get('/list', [PassLogsController::class, 'list'])->middleware('IsSuperadmin')->name('logsList');
});

Route::group([
    'prefix' => 'user'
], function() {
    Route::get('/list', [AuthController::class, 'usersList'])
        ->middleware('IsSuperadmin')
        ->name('usersList');
    Route::get('/fio-and-id-list', [AuthController::class, 'getUsersFioAndId'])
        ->name('usersList');
    Route::get('/timings-list/{user_id}', [TimingLogsController::class, 'timingsList'])
        ->middleware('IsSuperadmin')
        ->whereNumber('user_id')
        ->name('timingsList');
    Route::get('/timings-export/{user_id}', [TimingLogsController::class, 'exportExcel'])
        ->whereNumber('user_id')
        ->name('exportExcellTimings');
});
