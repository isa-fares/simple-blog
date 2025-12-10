<?php

namespace App\Repositories;

use App\Models\User;

class  EloquentUserRepository implements UserRepositoryInterface
{
    public function getAll()
    {
        return User::all();
    }

    public function getById($id): ?User
    {
        return User::find($id);
    }
    public function create(array $data): User
    {
        return User::create($data);
    }
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
