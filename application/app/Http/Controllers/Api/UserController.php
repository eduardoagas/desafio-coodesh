<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FavoritesRequest;
use App\Services\FavoriteService;

class UserController extends Controller
{
    protected $userService;
    protected  $favoriteService;

    public function __construct(UserService $userService, FavoriteService $favoriteService)
    {
        $this->userService = $userService;
        $this->favoriteService = $favoriteService;
    }

    /**
     * @OA\Get(
     *      path="/user/me",
     *      summary="Obter perfil.",
     *      description="Retorna as informações do usuário.",
     *      tags={"User"},
     *      security={{"bearerAuth": {}}},
     *      @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1, description="O ID do usuário."),
     *             @OA\Property(property="name", type="string", example="Pedro Silva", description="Nome do usuário."),
     *             @OA\Property(property="email", type="string", example="pedrosilva@example.com", description="O endereço de email do usuário.")
     *         )
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Erro de autorização.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Please, sign in first!")
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
     *     security={{"bearerAuth": {}}},
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
     *         @OA\Schema(type="string", example="")
     *     ),
     *     @OA\Parameter(
     *         name="cursorField",
     *         in="query",
     *         description="Campo para determinar o cursor. Use 'accessed_at' para retornar palavras com datas de visualização.",
     *         required=false,
     *         @OA\Schema(type="string", example="accessed_at")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de palavras do histórico.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="results", type="array", @OA\Items(
     *                     @OA\Property(property="word", type="string", example="fire"),
     *                     @OA\Property(property="added", type="string", format="date-time", example="2024-12-01T15:30:00Z")
     *                 )),
     *                 @OA\Property(property="totalDocs", type="integer", example=50),
     *                 @OA\Property(property="next", type="string", example="eyJpZCI6MTIzfQ=="),
     *                 @OA\Property(property="hasNext", type="boolean", example=true),
     *                 @OA\Property(property="hasPrev", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de autorização.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Please, sign in first!")
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

    /**
     * @OA\Get(
     *     path="/user/me/favorites",
     *     summary="Obter lista de palavras favoritas",
     *     description="Retorna a lista de palavras que o usuário marcou como favoritas.",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
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
     *         @OA\Schema(type="string", example="")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de palavras favoritas.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="results", type="array", @OA\Items(
     *                     @OA\Property(property="word", type="string", example="fire"),
     *                     @OA\Property(property="added", type="string", format="date-time", example="2024-12-01T15:30:00Z")
     *                 )),
     *                 @OA\Property(property="totalDocs", type="integer", example=25),
     *                 @OA\Property(property="next", type="string", example="eyJpZCI6MTIzfQ=="),
     *                 @OA\Property(property="hasNext", type="boolean", example=true),
     *                 @OA\Property(property="hasPrev", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de autorização ou entrada inválida.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Please, sign in first!")
     *         )
     *     )
     * )
     */
    public function getFavorites(FavoritesRequest $request)
    {
        $data = $this->favoriteService->getFavorites($request->validated());

        return response()->json([$data], 200);
    }
}
