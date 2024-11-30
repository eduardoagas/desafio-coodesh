<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getProfile(Request $request)
    {
        
        $user = $this->userService->getUserProfile(Auth::id());
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], Response::HTTP_OK);
    }

    public function getHistory(Request $request)
    {
        $limit = $request->query('limit', 10);
        $cursor = $request->query('cursor');

        $data = $this->userService->getUserWordHistory(Auth::id(), $limit, $cursor);

        return response()->json([$data], 200);
    }
}
