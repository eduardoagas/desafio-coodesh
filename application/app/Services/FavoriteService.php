<?php

namespace App\Services;

use App\Repositories\FavoriteRepository;

class FavoriteService
{
    protected FavoriteRepository $repository;

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

    public function getFavorites(array $params): array
    {
        $cursor = $params['cursor'] ?? null;
        $limit = $params['limit'] ?? 10;
        return $this->repository->getFavorites($limit, $cursor);
    }
}
