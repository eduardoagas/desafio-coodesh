<?php

namespace App\Services;

use App\Repositories\EntriesRepository;

class EntriesService
{
    protected EntriesRepository $entriesRepository;

    public function __construct(EntriesRepository $entriesRepository)
    {
        $this->entriesRepository = $entriesRepository;
    }

    public function getWords(array $params): array
    {
        $search = $params['search'] ?? null;
        $cursor = $params['cursor'] ?? null;
        $limit = $params['limit'] ?? 10;

        return $this->entriesRepository->paginateWithCursor($search, $cursor, $limit, 'id', $limit);
    }

    public function getWordInfoAndRegisterHistory(string $word)
    {
        return $this->entriesRepository->getWordInfoAndRegisterHistory($word);
    }

    public function registerHistory(string $word)
    {
        return $this->entriesRepository->registerHistory($word);
    }
}
