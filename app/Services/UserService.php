<?php

namespace App\Services;

use App\BOs\UserBO;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $bo;
    protected $cacheKey = 'users';

    public function __construct(UserBO $bo)
    {
        $this->bo = $bo;
    }

    public function getAllUsers()
    {
        return Cache::remember($this->cacheKey, now()->addMinutes(10), function () {
            return $this->bo->getAllUsers();
        });
    }

    public function getUser($id)
    {
        return Cache::remember('user_' . $id, now()->addMinutes(10), function () use ($id) {
            return $this->bo->getUser($id);
        });
    }

    public function createUser($data)
    {
        DB::beginTransaction();
        try {
            $user = $this->bo->createUser($data);
            $token = $user->createToken('auth_token')->plainTextToken;
            Cache::forget($this->cacheKey);
            DB::commit();
            return ['user' => $user, 'token' => $token];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUser($id, $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->bo->updateUser($id, $data);
            Cache::forget($this->cacheKey);
            Cache::forget('user_' . $id);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
