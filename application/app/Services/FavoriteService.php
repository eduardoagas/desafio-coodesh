<?php

namespace App\Services;

use App\Models\User;
use App\Models\DictionaryEntry;
use App\Repositories\FavoriteRepository;

class FavoriteService
{
    protected $repository;

    public function __construct(FavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addFavorite(string $word): bool
    {
        return $this->repository->addFavorite($word);
    }

    public function removeFavorite(string $word): bool
    {
        return $this->repository->removeFavorite($word) > 0;
    }

    public function getFavoritesWithPagination(int $limit, ?string $cursor): array
    {
        return $this->repository->getFavoritesWithPagination($limit, $cursor);
    }
}
