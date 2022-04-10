<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\LoginController;
use App\Http\Controllers\api\v1\PublicChatController;
use App\Http\Controllers\api\v1\user\UserController;
use App\Http\Controllers\api\v1\user\SingerController;
use App\Http\Controllers\api\v1\user\ComposerController;
use App\Http\Controllers\api\v1\SongController;
use App\Http\Controllers\api\v1\AlbumController;
use App\Http\Controllers\api\v1\GenreController;

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
    Route::prefix('/singer')->group(function(){
        Route::get('/get-all-singer', [SingerController::class,'getAllSinger']);
        Route::get('/{singer_id}', [SingerController::class,'getSinger']);
        Route::get('/{singer_id}/songs', [SingerController::class,'getSingerSongs']);
        Route::get('/{singer_id}/albums', [SingerController::class,'getSingerAlbums']);
    });
    Route::prefix('/composer')->group(function(){
        Route::get('/get-all-composer', [ComposerController::class,'getAllComposer']);
        Route::get('/{composer_id}', [ComposerController::class,'getComposer']);
        Route::get('/{composer_id}/songs', [ComposerController::class,'getComposerSongs']);
    });
});

Route::prefix('/song')->group(function(){
    Route::get('/get-all-song', [SongController::class,'getAllSong']);
    Route::get('/{song_id}', [SongController::class,'getSong']);
    Route::post('/store', [SongController::class,'createSong']);
});

Route::prefix('/album')->group(function(){
    Route::get('/get-all-album', [AlbumController::class,'getAllAlbum']);
    Route::get('/{album_id}', [AlbumController::class,'getAlbum']);
    Route::get('/{album_id}/songs', [AlbumController::class,'getAlbumSongs']);
    Route::get('/{album_id}/singers', [AlbumController::class,'getAlbumSingers']);
});

Route::prefix('/genre')->group(function(){
    Route::get('/get-all-genre', [GenreController::class,'getAllGenre']);
});

Route::prefix('/public-chat')->group( function(){
    Route::get('/messages/{room_id}', [PublicChatController::class,'fetchMessages']);
    Route::middleware('auth:api')->post('/messages', [PublicChatController::class,'sendMessages']);
    Route::get('/room-chat/{room_id}', [PublicChatController::class,'getRoomChat']);
});