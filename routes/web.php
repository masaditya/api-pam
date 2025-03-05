<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tai-banget', function () {
    return view('welcome');
});

Route::get('/ulo', function () {
    return view('welcome');
});
