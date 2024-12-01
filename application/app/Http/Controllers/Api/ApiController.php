<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Dictiocod API",
 *     version="1.0",
 *     description="API de dicionário!"
 * )
 */
class ApiController extends Controller
{
    public function hello()
    {
        return response()->json([
            'message' => 'Fullstack Challenge 🏅 - Dictionary'
        ], 200);
    }

    public function pleasesignin()
    {
        return response()->json([
            'message' => 'Please, sign in first!'
        ], 400);
    }
}
