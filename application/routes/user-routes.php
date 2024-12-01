<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FavoriteController;

//prefixos alterados em bootstrap/app
Route::get("me", [UserController::class, "getProfile"]);
Route::get('me/history', [UserController::class, 'getHistory']);
Route::get('me/favorites', [UserController::class, "getFavorites"]);
