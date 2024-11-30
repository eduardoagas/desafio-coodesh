<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Fullstack Challenge 🏅 - Dictionary'
    ], 200);
});

Route::get('/pleasesignin', function () {
    return response()->json([
        'message' => 'Please, sign in first!'
    ], 200);
})->name('login');