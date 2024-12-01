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

    /**
     * @OA\Post(
     *     path="/auth/signup",
     *     summary="Registrar um novo usuário",
     *     description="Cria um novo usuário e retorna os dados do usuário junto com o token de autenticação.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados para criar um novo usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="senha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="token", type="string", example="Bearer xxxxxxx.token.here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na validação dos dados ou falha ao criar o usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro de validação.")
     *         )
     *     )
     * )
     */
    public function signup(SignupRequest $request)
    {

        $user = $this->userService->create($request);

        $token = $user->createToken('DictioToken')->accessToken;

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'token' => 'Bearer ' . $token,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/auth/signin",
     *     summary="Autenticar um usuário existente",
     *     description="Realiza o login de um usuário, validando suas credenciais e retornando o token de autenticação.",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados para realizar o login do usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="senha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido, retorna os dados do usuário e o token de autenticação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="token", type="string", example="Bearer xxxxxxx.token.here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas, login falhou",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Erro de validação.")
     *         )
     *     )
     * )
     */
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

        return response()->json(['message' => 'Erro de validacao.'], 400);
    }
}
