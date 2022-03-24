<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes sssss
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/pull/{key}', function ($key) {
    // $originalKey = env('GITHUB_PULL_SECRET', 'default');
    if($key == 'shellpullkeysupersecret12345') {
        shell_exec('git pull origin master');
        return response('Success pulled', 200)->header('Content-Type', 'text/plain');
    }
    return response('The secret key is invalid', 400)->header('Content-Type', 'text/plain');
});
