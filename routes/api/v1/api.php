<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\LoginController;
use App\Http\Controllers\api\v1\PublicChatController;
use App\Http\Controllers\api\v1\user\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('/user')->group( function(){
    Route::post('/login', [LoginController::class,'login']);
    Route::middleware('auth:api')->get('/current', [UserController::class,'currentUser']);
    Route::post('/createUser', [UserController::class,'createUser']);
    Route::middleware('auth:api')->post('/update-user', [UserController::class,'updateUser']);
});
Route::prefix('/public-chat')->group( function(){
    Route::get('/messages/{room_id}', [PublicChatController::class,'fetchMessages']);
    Route::middleware('auth:api')->post('/messages', [PublicChatController::class,'sendMessages']);
    Route::get('/room-chat/{room_id}', [PublicChatController::class,'getRoomChat']);
});