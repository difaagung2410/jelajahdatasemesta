<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\NewsCommentController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\NewsLogController;
use App\Http\Controllers\Api\UserController;
use App\Models\NewsComment;
use App\Models\NewsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Route News
    Route::resource('news', NewsController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);

    // Route News Comment
    Route::resource('newsComment', NewsCommentController::class)->only(['store']);

    // Route User
    Route::resource('user', UserController::class)->only(['index']);

    // Route News Log
    Route::resource('newsLog', NewsLogController::class)->only(['index']);
});