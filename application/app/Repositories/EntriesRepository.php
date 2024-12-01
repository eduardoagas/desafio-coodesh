<?php

namespace App\Repositories;

use App\Models\WordHistory;
use App\Models\DictionaryEntry;
use App\Services\PaginationService;
use Illuminate\Support\Facades\Auth;

class EntriesRepository
{
    protected $paginationService;

    public function __construct(PaginationService $paginationService)
    {
        $this->paginationService = $paginationService;
    }

    public function paginateWithCursor(?string $search, ?string $cursor, int $limit): array
    {
        $query = DictionaryEntry::query();

        if ($search) {
            $query->where('word', 'LIKE', "$search%");
        }

        $totalDocs = DictionaryEntry::count();

        return $this->paginationService->paginateWithCursor($query, $limit, $cursor, 'id', $totalDocs);
    }

    public function getWordInfoAndRegisterHistory(string $word)
    {
        // Encontrar a palavra no dicionário
        $entry = DictionaryEntry::where('word', $word)->first();

        if ($entry) {
            $this->registerHistory($entry->word);
        }

        return $entry;
    }

    public function registerHistory(string $word)
    {

        // Registrar o histórico de acesso
        WordHistory::create([
            'user_id' => Auth::id(),
            'word' => $word,
            'accessed_at' => now(),
        ]);
    }
}
