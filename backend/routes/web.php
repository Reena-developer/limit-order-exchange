<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-broadcast', function () {
    event(new \App\Events\DebugEvent());
    return 'event fired';
});