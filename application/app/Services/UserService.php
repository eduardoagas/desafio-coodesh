<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($request)
    {
        return $this->userRepository->create($request);
    }

    public function getUserProfile(int $userId)
    {
        return $this->userRepository->getUserProfile($userId);
    }

    public function getUserWordHistory(int $userId, int $limit, ?string $cursor)
    {
        return $this->userRepository->getUserWordHistory($userId, $limit, $cursor);
    }
}
