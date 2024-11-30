<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\WordHistory;
use App\Services\PaginationService;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $model;
    protected $paginationService;

    public function __construct(User $user, PaginationService $paginationService)
    {
        $this->model = $user;
        $this->paginationService = $paginationService;
    }

    public function create($request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->remember_token = Hash::make($request->remember_token);
        $user->save();
        return $user;;
    }

    // Método para obter as informações do perfil do usuário
    public function getUserProfile(int $userId)
    {
        return User::find($userId);
    }

    // Método para obter o histórico de palavras visitadas pelo usuário
    public function getUserWordHistory(int $userId, int $limit, ?string $cursor)
    {
        $query = WordHistory::where('user_id', $userId)->orderBy('accessed_at', 'desc');

        return $this->paginationService->paginateWithCursor($query, $limit, $cursor, 'accessed_at', null);
    }
}
