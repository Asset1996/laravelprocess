<?php

use App\Http\Controllers\Auth\PassController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('pass', [PassController::class, 'pass'])
        ->name('pass');
    
    Route::post('take-picture', [PassController::class, 'takePicture'])
        ->name('take-picture');    
});
