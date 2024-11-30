<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Services\PaginationService;
use Illuminate\Support\Facades\Auth;

class FavoriteRepository
{

    protected PaginationService $paginationService;

    public function __construct(PaginationService $paginationService)
    {
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

    public function getFavorites(int $limit, ?string $cursor): array
    {
        $query = Favorite::where('user_id', Auth::id());

        return $this->paginationService->paginateWithCursor($query, $limit, $cursor, 'created_at', $query->count());
    }
}
