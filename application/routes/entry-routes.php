<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EntriesController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\DictionaryController;

//prefixos alterados em bootstrap/app
Route::get("en", [EntriesController::class, "search"]);
Route::get('en/{entry}', [DictionaryController::class, 'showWordInfo']);
Route::post('en/{entry}/favorite', [FavoriteController::class, 'addFavorite']);
Route::delete('en/{entry}/unfavorite', [FavoriteController::class, 'removeFavorite']);
