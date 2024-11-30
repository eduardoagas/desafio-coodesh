<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Middleware\ForceJsonResponse;

//prefixos alterados em bootstrap/app
Route::post("signup", [AuthController::class, "signup"]);
Route::post("signin", [AuthController::class, "signin"]);
