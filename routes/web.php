<?php
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// });

Route::post('/pull', function () {
    shell_exec('git pull');
    return 'Git pull executed';
});

Route::get('/{any}', function(){
    return view('home');
})->where('any', '(?!api$).*$');


require __DIR__.'/auth.php';
