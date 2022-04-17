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
use App\Http\Controllers\api\v1\PlaylistController;

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
    Route::get('/get-all-user', [UserController::class,'getAllUser']);
    Route::get('/already-singer-composer/{userId}', [UserController::class,'getAlreadySingerComposer']);
    Route::post('/setup-account', [UserController::class,'setupAccount']);
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
    Route::get('/get-full-infor-song/{song_id}', [SongController::class,'getFullInforSong']);
    Route::get('/get-all-song', [SongController::class,'getAllSong']);
    Route::get('/get-all-song-album/{albumId}', [SongController::class,'getAllSongInAlbum']);
    Route::get('/get-all-song-playlist/{playlistId}', [SongController::class,'getAllSongInPlaylist']);
    Route::get('/get-top3', [SongController::class,'getTopThree']);
    Route::get('/search/{txtSearch}', [SongController::class,'getSongByTxtSearch']);
    Route::get('/{song_id}', [SongController::class,'getSong']);
    Route::post('/store', [SongController::class,'createSong']);
    Route::post('/store-edit', [SongController::class,'editSong']);
});

Route::prefix('/album')->group(function(){
    Route::get('/get-all-album', [AlbumController::class,'getAllAlbum']);
    Route::get('/get-full-infor-album/{albumId}', [AlbumController::class,'getFullInforAlbum']);
    Route::get('/{album_id}', [AlbumController::class,'getAlbum']);
    Route::get('/{album_id}/songs', [AlbumController::class,'getAlbumSongs']);
    Route::get('/{album_id}/singers', [AlbumController::class,'getAlbumSingers']);
    Route::post('/store', [AlbumController::class,'createAlbum']);
    Route::post('/store-edit', [AlbumController::class,'editAlbum']);
});

Route::prefix('/genre')->group(function(){
    Route::get('/get-full-infor-genre/{genreId}', [GenreController::class,'getFullInforGenre']);
    Route::get('/get-all-genre', [GenreController::class,'getAllGenre']);
    Route::post('/store', [GenreController::class,'createGenre']);
    Route::post('/store-edit', [GenreController::class,'editGenre']);
});

Route::prefix('/playlist')->group( function(){
    Route::get('/get-corner', [PlaylistController::class,'getCornerPlaylist']);
    Route::middleware('auth:api')->get('/get-all-playlist-user', [PlaylistController::class,'getAllPlaylistUser']);
    Route::get('/get-top-five-selected-today', [PlaylistController::class,'getTopFivePlaylist']);
    Route::get('/get-infor-playlist/{playlistId}', [PlaylistController::class,'getInforPlaylist']);
    Route::get('/justnow', [PlaylistController::class,'getJustNow']);
    Route::get('/{playlistId}', [PlaylistController::class,'getPlaylist']);
    Route::middleware('auth:api')->post('/store', [PlaylistController::class,'createPlaylist']);
});


Route::prefix('/public-chat')->group( function(){
    Route::get('/messages/{room_id}', [PublicChatController::class,'fetchMessages']);
    Route::middleware('auth:api')->post('/messages', [PublicChatController::class,'sendMessages']);
    Route::get('/room-chat/{room_id}', [PublicChatController::class,'getRoomChat']);
    Route::get('/get-rooms', [PublicChatController::class,'getRooms']);
});

