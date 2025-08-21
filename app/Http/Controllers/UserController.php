<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    private function apiResponse($status, $message, $data = [], $code = 200, $action = "GET")
    {
        return response()->json([
            "status" => $status,
            "message" => $message,
            "meta-data" => [
                "apiId"       => "120104",
                "version"     => "01",
                "responsetime" => round(microtime(true) - LARAVEL_START, 2), // request time diff
                "epoch"       => now()->format("Y-m-d H:i:s"),
                "action"      => $action,
                "deviceId"    => request()->header("deviceId", null)
            ],
            "data" => $data
        ], $code);
    }

    public function index(Request $request)
    {
        try {
            $users = $this->service->getAllUsers();
            return $this->apiResponse(true, "All users fetched successfully", $users, 200, $request->method());
        } catch (Exception $e) {
            Log::error("UserController@index Error: " . $e->getMessage());
            return $this->apiResponse(false, "Failed to fetch users", [], 500, $request->method());
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $this->service->getUser($id);
            if (!$user) {
                return $this->apiResponse(false, "User not found", [], 404, $request->method());
            }
            return $this->apiResponse(true, "User fetched successfully", $user, 200, $request->method());
        } catch (Exception $e) {
            Log::error("UserController@show Error: " . $e->getMessage());
            return $this->apiResponse(false, "Failed to fetch user", [], 500, $request->method());
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->service->createUser($request->validated());
            return $this->apiResponse(true, "User created successfully", $user, 201, $request->method());
        } catch (Exception $e) {
            Log::error("UserController@store Error: " . $e->getMessage());
            return $this->apiResponse(false, "Failed to create user", [], 500, $request->method());
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = $this->service->updateUser($id, $request->validated());
            if (!$user) {
                return $this->apiResponse(false, "User not found", [], 404, $request->method());
            }
            return $this->apiResponse(true, "User updated successfully", $user, 200, $request->method());
        } catch (Exception $e) {
            Log::error("UserController@update Error: " . $e->getMessage());
            return $this->apiResponse(false, "Failed to update user", [], 500, $request->method());
        }
    }
}
