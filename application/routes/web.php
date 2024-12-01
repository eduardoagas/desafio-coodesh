<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::get('/', [ApiController::class, "hello"]);

Route::get('/pleasesignin', [ApiController::class, "pleasesignin"])->name('login');