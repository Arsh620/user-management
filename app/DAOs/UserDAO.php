<?php

namespace App\DAOs;

use App\Models\User;

class UserDAO
{
    public function all()
    {
        return User::all();
    }
    public function find($id)
    {
        return User::find($id);
    }
    public function store(array $data)
    {
        return User::create($data);
    }
    public function update(User $user, array $data)
    {
        $user->update($data);
        return $user;
    }
}
