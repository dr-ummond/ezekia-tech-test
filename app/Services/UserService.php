<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $user_repo) {}

    public function createUser(UserDTO $userDTO): User
    {
        return $this->user_repo->create($userDTO);
    }

    public function updateUser(User $user, UserDTO $userDTO): void
    {
        $this->user_repo->update($user, $userDTO);
    }

    public function deleteUser(User $user): void
    {
        $this->user_repo->delete($user);
    }
}
