<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *      path="/user/me",
     *      summary="Obter perfil.",
     *      description="Retorna as informações do usuário.",
     *      tags={"User"},
     *      @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1, description="O ID do usuário."),
     *             @OA\Property(property="name", type="string", example="Pedro Silva", description="Nome do usuário."),
     *             @OA\Property(property="email", type="string", example="pedrosilva@example.com", description="O endereço de email do usuário.")
     *         )
     *     )
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function getProfile(Request $request)
    {

        $user = $this->userService->getUserProfile(Auth::id());

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/user/me/history",
     *     summary="Obter histórico de palavras visitadas",
     *     description="Retorna a lista de palavras que o usuário já visualizou no dicionário.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número máximo de registros a serem retornados. Padrão é 10.",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="cursor",
     *         in="query",
     *         description="Código para paginação baseada em cursores.",
     *         required=false,
     *         @OA\Schema(type="string", example="eyJpZCI6MTIzfQ==")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de palavras do histórico.",
     *         @OA\JsonContent(
     *             @OA\Property(property="results", type="array", @OA\Items(
     *                 @OA\Property(property="word", type="string", example="fire"),
     *                 @OA\Property(property="viewed_at", type="string", format="date-time", example="2024-12-01T15:30:00Z")
     *             )),
     *             @OA\Property(property="totalDocs", type="integer", example=50),
     *             @OA\Property(property="next", type="string", example="eyJpZCI6MTIzfQ=="),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
     *         )
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function getHistory(Request $request)
    {
        $limit = $request->query('limit', 10);
        $cursor = $request->query('cursor');

        $data = $this->userService->getUserWordHistory(Auth::id(), $limit, $cursor);

        return response()->json([$data], 200);
    }
}
