<?php

use App\Http\Controllers\Auth\PassController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('pass', [PassController::class, 'pass'])
                ->name('pass');
    
    Route::post('take-picture', [PassController::class, 'takePicture'])
                ->name('take-picture');
    
    
    // Route::prefix('auth')->group(function () {
    //     Route::get('users/{id}', function ($slug) {
    //         print_r($slug);exit();
    //     });
    //     Route::get('login', [AuthController::class, 'getLogin'])
    //             ->name('getLogin');
    // });            
});
