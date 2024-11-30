<?php

namespace App\Repositories;

use App\Models\DictionaryEntry;
use App\Services\PaginationService;

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
}
