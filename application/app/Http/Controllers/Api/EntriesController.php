<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\EntriesService;
use App\Services\FavoriteService;
use App\Services\WordsApiService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\EntriesSearchRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntriesController extends Controller
{
    protected EntriesService $entriesService;
    protected FavoriteService $favoriteService;
    protected $wordsApiService;

    public function __construct(WordsApiService $wordsApiService, EntriesService $entriesService, FavoriteService $favoriteService)
    {
        $this->entriesService = $entriesService;
        $this->favoriteService = $favoriteService;
        $this->wordsApiService = $wordsApiService;
    }

    /**
     * @OA\Get(
     *     path="/entries/en",
     *     summary="Buscar palavras no dicionário",
     *     description="Retorna uma lista de palavras do dicionário com base em um critério de busca, utilizando paginação com cursores.",
     *     tags={"Entries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Termo de busca para filtrar palavras do dicionário.",
     *         required=false,
     *         @OA\Schema(type="string", example="fire")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número máximo de palavras a serem retornadas. Padrão é 10.",
     *         required=false,
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Parameter(
     *         name="cursor",
     *         in="query",
     *         description="Cursor para paginação. Usado para navegar entre as páginas de resultados.",
     *         required=false,
     *         @OA\Schema(type="string", example="")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de palavras encontradas.",
     *         @OA\JsonContent(
     *             @OA\Property(property="results", type="array", @OA\Items(
     *                 @OA\Property(property="word", type="string", example="fire")
     *             )),
     *             @OA\Property(property="totalDocs", type="integer", example=20),
     *             @OA\Property(property="previous", type="string", example="eyIkb2lkIjoiNTgwZmQxNmjJkOGI5In0"),
     *             @OA\Property(property="next", type="string", example="eyIkb2lkIjoiNTgwZmQxNm1NjJkOGI4In0"),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação dos parâmetros de busca.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Bad Request")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Erro de autenticação, usuário não está logado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Please, sign in first!")
     *         )
     *     )
     * )
     */
    public function search(EntriesSearchRequest $request)
    {
        $data = $this->entriesService->getWords($request->validated());

        return response()->json([$data], 200);
    }

    /**
     * @OA\Get(
     *     path="/entries/en/{entry}",
     *     summary="Obter informações sobre uma palavra",
     *     description="Busca informações sobre a palavra fornecida.",
     *     tags={"Entries"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="entry",
     *         in="path",
     *         required=true,
     *         description="A palavra a ser pesquisada",
     *         @OA\Schema(type="string", example="example")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informações da palavra retornadas com sucesso, incluindo status de cache e tempo de resposta",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="word", type="string", example="fogo"),
     *             @OA\Property(property="description", type="string", example="Diversas informações sobre a palavra 'fogo'.")
     *         ),
     *         @OA\Header(
     *             header="x-cache",
     *             description="Indica se a resposta foi do cache (HIT) ou precisa ser buscada (MISS)",
     *             @OA\Schema(type="string", enum={"HIT", "MISS"})
     *         ),
     *         @OA\Header(
     *             header="x-response-time",
     *             description="O tempo de resposta da requisição em milissegundos",
     *             @OA\Schema(type="integer", example=150)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao processar a palavra, como palavra não encontrada ou erro na requisição externa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Word not found")
     *         )
     *     )
     * )
     */
    public function showWordInfo(Request $request, string $word)
    {
        $startTime = microtime(true); // Inicia a contagem do tempo

        // Verifica se o resultado está no cache
        $cacheKey = "word_info_{$word}"; // Chave única para a palavra
        $cachedResponse = Cache::get($cacheKey);

        if ($cachedResponse) {
            // Se estiver no cache, retorna os dados em cache
            $response = $cachedResponse;
            $cacheStatus = 'HIT';
            $this->entriesService->registerHistory($word);
            $entry = true;
        } else {
            // Se não estiver no cache, faz a requisição para a API externa
            // Buscar as informações da palavra e registrar o histórico
            $entry = $this->entriesService->getWordInfoAndRegisterHistory($word);
            $response = $this->wordsApiService->getWordDetails($word);

            if (isset($response['error'])) {
                return response()->json([
                    'message' => 'Something went wrong.',
                ], 400);
            }

            // Cache a resposta da API por 60 minutos
            Cache::put($cacheKey, $response, 60); // 60 minutos

            $cacheStatus = 'MISS';
        }

        // Registra o tempo de resposta
        $responseTime = round((microtime(true) - $startTime) * 1000); // Milissegundos

        if (isset($entry)) {
            return response()->json([
                'word' => $word,
                'description' => $response['results'] ?? 'A beautiful word',
            ], 200)->header('x-cache', $cacheStatus)
                ->header('x-response-time', $responseTime);;
        }

        return response()->json(['message' => 'Word not found'], 400);
    }

    /**
     * @OA\Post(
     *     path="/entries/en/{entry}/favorite",
     *     summary="Adicionar palavra aos favoritos",
     *     description="Adiciona uma palavra aos favoritos do usuário.",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="entry",
     *         in="path",
     *         description="A palavra que será adicionada aos favoritos.",
     *         required=true,
     *         @OA\Schema(type="string", example="fire")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Palavra adicionada aos favoritos com sucesso.",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao adicionar palavra aos favoritos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro ao adicionar favorito.")
     *         )
     *     ),
     * )
     */
    public function addFavorite(Request $request, string $word)
    {
        if ($this->favoriteService->addFavorite($word)) {
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Erro ao adicionar favorito.'], 400);
    }

    /**
     * @OA\Delete(
     *     path="/entries/en/{entry}/unfavorite",
     *     summary="Remover palavra dos favoritos",
     *     description="Remove uma palavra dos favoritos do usuário.",
     *     tags={"User"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="entry",
     *         in="path",
     *         description="A palavra que será removida dos favoritos.",
     *         required=true,
     *         @OA\Schema(type="string", example="fire")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Palavra removida dos favoritos com sucesso.",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao remover palavra dos favoritos.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro ao remover favorito.")
     *         )
     *     ),
     * )
     */
    public function removeFavorite(Request $request, string $word)
    {
        if ($this->favoriteService->removeFavorite($word)) {
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Erro ao remover favorito.'], 400);
    }
}
