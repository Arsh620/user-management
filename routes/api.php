<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::controller(UserController::class)->group(function () {
    Route::get('get-all-users', 'index');
    Route::get('get-users-by-id/{id}', 'show');
    Route::post('users/create', 'store');
    Route::put('users-update/{id}', 'update');
});
