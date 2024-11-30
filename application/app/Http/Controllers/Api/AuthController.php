<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Http\Requests\SigninRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function signup(SignupRequest $request){

        $user = $this->userService->create($request);

        $token = $user->createToken('DictioToken')->accessToken;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'token' => 'Bearer ' . $token,
        ], 200);
    }

    public function signin(SigninRequest $request)
    {
        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('DictioToken')->accessToken;

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'token' => 'Bearer ' . $token,
            ], 200);
        }
    }

    
}
