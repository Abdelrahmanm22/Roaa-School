<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::group(
    ['middleware' => 'api', 'prefix' => 'auth'],
    function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
});
