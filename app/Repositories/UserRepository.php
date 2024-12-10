<?php

namespace App\Repositories;

use App\DTOs\UserDTO;
use App\Models\User;

class UserRepository
{
    public function create(UserDTO $userDTO): User
    {
        return User::create($userDTO->toArray());
    }

    public function update(User $user, UserDTO $userDTO): void
    {
        $user->update($userDTO->toArray());
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
