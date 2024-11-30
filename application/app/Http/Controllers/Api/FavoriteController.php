<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\FavoriteService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\FavoritesRequest;

class FavoriteController extends Controller
{
    protected FavoriteService $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
    }

    public function addFavorite(Request $request, string $word)
    {
        if ($this->service->addFavorite($word)) {
            return response()->json(null, 204);
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

    public function getFavorites(FavoritesRequest $request)
    {
        $data = $this->service->getFavorites($request->validated());

        return response()->json([$data], 200);
    }
}