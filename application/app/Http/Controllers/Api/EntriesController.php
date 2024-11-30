<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\EntriesService;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntriesSearchRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntriesController extends Controller
{
    protected EntriesService $entriesService;

    public function __construct(EntriesService $entriesService)
    {
        $this->entriesService = $entriesService;
    }

    public function search(EntriesSearchRequest $request)
    {
        $data = $this->entriesService->getWords($request->validated());

        return response()->json($data);
    }
}
