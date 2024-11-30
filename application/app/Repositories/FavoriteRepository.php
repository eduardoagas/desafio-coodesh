<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Services\PaginationService;
use Illuminate\Support\Facades\Auth;

class FavoriteRepository
{

    protected $repository;
    protected $paginationService;

    public function __construct(FavoriteRepository $repository, PaginationService $paginationService)
    {
        $this->repository = $repository;
        $this->paginationService = $paginationService;
    }

    public function addFavorite(string $word): bool
    {
        return Favorite::create([
            'user_id' => Auth::id(),
            'word' => $word,
        ]) ? true : false;
    }

    public function removeFavorite(string $word): int
    {
        return Favorite::where('user_id', Auth::id())
            ->where('word', $word)
            ->delete();
    }

    public function getFavoritesQuery()
    {
        return Favorite::where('user_id', Auth::id());
    }

    public function getFavoritesWithPagination(int $limit, ?string $cursor): array
    {
        $query = $this->repository->getFavoritesQuery();

        return $this->paginationService->paginateWithCursor($query, $limit, $cursor, 'id', $query->count());
    }
}
