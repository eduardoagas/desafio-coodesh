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

    /**
     * @OA\Get(
     *     path="/",
     *     summary="Endpoint de Boas-vindas",
     *     description="Retorna uma mensagem de boas-vindas para verificar se a API está funcionando corretamente.",
     *     tags={"Miscellaneous"},
     *     @OA\Response(
     *         response=200,
     *         description="Mensagem de boas-vindas retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Fullstack Challenge 🏅 - Dictionary"
     *             )
     *         )
     *     )
     * )
     */
    public function hello()
    {
        return response()->json([
            'message' => 'Fullstack Challenge 🏅 - Dictionary'
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/pleasesignin",
     *     summary="Mensagem de solicitação de login.",
     *     description="Retorna uma mensagem indicando que o usuário precisa realizar o login antes de continuar.",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=400,
     *         description="Usuário não autenticado, solicita login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Please, sign in first!"
     *             )
     *         )
     *     )
     * )
     */
    public function pleasesignin()
    {
        return response()->json([
            'message' => 'Please, sign in first!'
        ], 400);
    }
}
