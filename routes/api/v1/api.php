<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\LoginController;
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
    Route::post('/login', [LoginController::class,'login'])->name("login");
    Route::middleware('auth:api')->get('/current', [UserController::class,'currentUser'])->name("currentUser");
    Route::post('/createUser', [UserController::class,'createUser'])->name("createUser");
    Route::middleware('auth:api')->post('/update-user', [UserController::class,'updateUser'])->name("updateUser");
});