<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\DictionaryService;
use App\Http\Controllers\Controller;
use App\Services\WordsApiService;
use Illuminate\Support\Facades\Cache;

class DictionaryController extends Controller
{
    protected $dictionaryService;
    protected $wordsApiService;

    public function __construct(DictionaryService $dictionaryService, WordsApiService $wordsApiService)
    {
        $this->dictionaryService = $dictionaryService;
        $this->wordsApiService = $wordsApiService;
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
            $this->dictionaryService->registerHistory($word);
            $entry = true;
        } else {
            // Se não estiver no cache, faz a requisição para a API externa
            // Buscar as informações da palavra e registrar o histórico
            $entry = $this->dictionaryService->getWordInfoAndRegisterHistory($word);
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
}
