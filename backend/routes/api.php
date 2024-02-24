<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, "login"]);
    Route::post('logout', [AuthController::class, "logout"]);
    Route::post('refresh', [AuthController::class, "refresh"]);
    Route::post('me', [AuthController::class, "me"]);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::get('/{userId}', [UserController::class, "find"]);
    Route::post('/', [UserController::class, "store"]);
    Route::delete('/{userId}', [UserController::class, "delete"]);
    Route::put('/', [UserController::class, "update"]);
    Route::patch('/', [UserController::class, "update"]);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'transactions'
], function ($router) {

    Route::post('/', [TransactionController::class, "transferBalance"]);
    Route::get("/", function (){
        return new TransactionResource(Transaction::paginate());
    });
});
