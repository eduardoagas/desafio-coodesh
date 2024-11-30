<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\DictionaryService;
use App\Http\Controllers\Controller;

class DictionaryController extends Controller
{
    protected $dictionaryService;

    public function __construct(DictionaryService $dictionaryService)
    {
        $this->dictionaryService = $dictionaryService;
    }

    public function showWordInfo(Request $request, string $word)
    {
        // Buscar as informações da palavra e registrar o histórico
        $entry = $this->dictionaryService->getWordInfoAndRegisterHistory($word);

        if ($entry) {
            return response()->json([
                'word' => $entry->word,
                'description' => 'A beautiful word',
                //'description' => $entry->description, 
            ], 200);
        }

        return response()->json(['message' => 'Word not found'], 400);
    }
}
