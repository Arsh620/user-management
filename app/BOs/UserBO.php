<?php

namespace App\BOs;

use App\DAOs\UserDAO;
use Illuminate\Support\Facades\Hash;

class UserBO
{
    protected $dao;
    public function __construct(UserDAO $dao)
    {
        $this->dao = $dao;
    }

    public function createUser($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $this->dao->store($data);
    }
    public function updateUser($id, $data)
    {
        $user = $this->dao->find($id);
        if (!$user) return null;
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        return $this->dao->update($user, $data);
    }
    public function getUser($id)
    {
        return $this->dao->find($id);
    }
    public function getAllUsers()
    {
        return $this->dao->all();
    }
}
