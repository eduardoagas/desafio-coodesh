<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EntriesController;
use App\Http\Controllers\Api\FavoriteController;

//prefixos alterados em bootstrap/app
Route::get("en", [EntriesController::class, "search"]);
Route::get('en/{entry}', [EntriesController::class, 'showWordInfo']);
Route::post('en/{entry}/favorite', [EntriesController::class, 'addFavorite']);
Route::delete('en/{entry}/unfavorite', [EntriesController::class, 'removeFavorite']);
