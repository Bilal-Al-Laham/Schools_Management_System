<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function createUser(array $data);
    public function find($id);
    public function findByEmail(string $email);
    public function updateLastLogin(User $user);
}

class UserRepository implements UserRepositoryInterface
{
    public function createUser($data)
    {
        return User::create($data);
    }

    public function find($id)
    {
        return User::find($id);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function updateLastLogin(User $user)
    {
        // $user->last_login = now();
        $user->save();
    }
}
