<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\DictionaryEntry;
use App\Services\FavoriteService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    protected $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    public function addFavorite(Request $request, string $word)
    {
        if ($this->service->addFavorite($word)) {
            return response()->json(null, 200);
        }

        return response()->json(['message' => 'Unable to add favorite'], 400);
    }

    public function removeFavorite(Request $request, string $word)
    {
        if ($this->service->removeFavorite($word)) {
            return response()->json(null, 204);
        }

        return response()->json(['message' => 'Unable to remove favorite'], 400);
    }

    public function getFavorites(Request $request)
    {
        $limit = $request->query('limit', 10);
        $cursor = $request->query('cursor');

        $favorites = $this->service->getFavoritesWithPagination($limit, $cursor);

        return response()->json($favorites);
    }
}