<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PassController;
use App\Http\Controllers\PassLogsController;
use App\Http\Controllers\TimingLogsController;
use App\Http\Controllers\ProfileController;

Route::group([
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
    /**
     * CRUD
     */
    Route::get('/list', [UserController::class, 'usersList'])
        ->middleware('IsSuperadmin')
        ->name('usersList');
    Route::post('/create', [UserController::class, 'createUserPost'])
        ->middleware('IsSuperadmin')
        ->name('createUserPost');
    Route::get('/create', [UserController::class, 'createUserGet'])
        ->middleware('IsSuperadmin')
        ->name('createUserGet');
    
    /**
     * PERSONAL
     */
    Route::prefix('/profile')->group(function(){
        Route::get('', [ProfileController::class, 'profile'])->name('getProfile');
        Route::post('/upload', [ProfileController::class, 'uploadPhoto'])->name('profileUploadPhoto');
    });

    /**
     * TIMINGS
     */
    Route::get('/timings-list/{user_id}', [TimingLogsController::class, 'timingsList'])
        ->middleware('IsSuperadmin')
        ->whereNumber('user_id')
        ->name('timingsList');
    Route::get('/timings-export/{user_id}', [TimingLogsController::class, 'exportExcel'])
        ->whereNumber('user_id')
        ->name('exportExcellTimings');
    
    /**
     * OTHERS
     */
    Route::get('/fio-and-id-list', [UserController::class, 'getUsersFioAndId'])
        ->name('usersList');
    
    /**
     * CRONES
     */
    Route::prefix('cron')->group(function () {
        Route::get('exit', [PassController::class, 'cronExitAll'])
            ->name('cronForceExit');
    });
});
