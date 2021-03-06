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
use App\Http\Controllers\api\v1\SlideController;
use App\Http\Controllers\api\v1\RoleController;
use App\Http\Controllers\api\v1\PermissionController;
use App\Http\Controllers\api\v1\MvController;
use App\Http\Controllers\api\v1\PostController;
use App\Http\Controllers\api\v1\FollowController;
use App\Http\Controllers\api\v1\CommentController;

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
    Route::middleware('auth:api')->get('/get-all-like-of-songs', [UserController::class,'getAllLikeOfSongs']);
    Route::middleware('auth:api')->post('/like-song', [UserController::class,'likeSong']);
    Route::middleware('auth:api')->post('/unlike-song', [UserController::class,'unlikeSong']);
    Route::middleware('auth:api')->get('/check-like-song', [UserController::class,'checkLikedSong']);
    Route::post('/login', [LoginController::class,'login'])->middleware("throttle:10,1");
    Route::middleware('auth:api')->get('/checkRole', [UserController::class,'checkRole']);
    Route::middleware('auth:api')->get('/current', [UserController::class,'currentUser']);
    Route::post('/createUser', [UserController::class,'createUser']);
    Route::middleware('auth:api')->post('/update-user', [UserController::class,'updateUser']);
    Route::middleware(['auth:api', 'can:list_user'])->get('/get-all-user', [UserController::class,'getAllUser']);
    Route::get('/already-singer-composer/{userId}', [UserController::class,'getAlreadySingerComposer']);
    Route::middleware(['auth:api', 'can:add_setup-account'])->post('/setup-account', [UserController::class,'setupAccount']);
    Route::prefix('/singer')->group(function(){
        Route::get('/get-image-hub', [SingerController::class,'getImageHub']);
        Route::get('/get-all-singer', [SingerController::class,'getAllSinger']);
        Route::get('/get-singer-by-song/{id}', [SingerController::class,'getSingerBySong']);
        Route::get('/get-info-singer/{id}', [SingerController::class,'getUserBySinger']);
        Route::get('/{singer_id}', [SingerController::class,'getSinger']);
        Route::get('/{singer_id}/songs', [SingerController::class,'getSingerSongs']);
        Route::get('/{singer_id}/albums', [SingerController::class,'getSingerAlbums']);    
    });
    Route::prefix('/composer')->group(function(){
        Route::get('/get-all-composer', [ComposerController::class,'getAllComposer']);
        Route::get('/{composer_id}', [ComposerController::class,'getComposer']);
        Route::get('/{composer_id}/songs', [ComposerController::class,'getComposerSongs']);
    });
    Route::prefix('/role')->group(function(){
        Route::middleware(['auth:api', 'can:list_role'])->get('/get-role-of-user/{id}', [RoleController::class,'getRoleOfUser']);
        Route::middleware(['auth:api', 'can:list_role'])->get('/get-all-user-has-role', [RoleController::class,'getAllUserHasRole']);
        Route::middleware(['auth:api', 'can:list_role'])->get('/get-all-role', [RoleController::class,'getAllRole']);
        Route::middleware(['auth:api', 'can:list_role'])->get('/get-full-infor-role/{id}', [RoleController::class,'getFullInforRole']);
        Route::middleware(['auth:api', 'can:list_role'])->post('/store-user-role', [RoleController::class,'createUserRole']);
        Route::middleware(['auth:api', 'can:add_role'])->post('/store', [RoleController::class,'createRole']);
        Route::middleware(['auth:api', 'can:edit_role'])->post('/store-edit', [RoleController::class,'updateRole']);
    });
});

Route::prefix('/song')->group(function(){
    Route::get('/get-full-infor-song/{song_id}', [SongController::class,'getFullInforSong']);
    Route::get('/get-all-song', [SongController::class,'getAllSong']);
    Route::get('/get-all-song-album/{albumId}', [SongController::class,'getAllSongInAlbum']);
    Route::get('/get-all-song-playlist/{playlistId}', [SongController::class,'getAllSongInPlaylist']);
    Route::get('/get-top3', [SongController::class,'getTopThree']);
    Route::get('/get-song-by-genre/{genre_id}/singer/{singer_id}', [SongController::class,'getSongByGenreAndSinger']);
    Route::get('/get-top100', [SongController::class,'getTop100']);
    Route::get('/get-top5-all-genre', [SongController::class,'getTop5AllGenre']);
    Route::get('/get-top-new-songs', [SongController::class,'getTopNewSongs']);
    Route::get('/search/{txtSearch}', [SongController::class,'getSongByTxtSearch']);
    Route::middleware('auth:api')->get('/get-songs-playlist-user', [SongController::class,'getSongPlaylistUser']);
    Route::get('/{song_id}', [SongController::class,'getSong']);
    Route::post('/store', [SongController::class,'createSong'])->middleware(['auth:api', 'can:add_song']);
    Route::post('/store-edit', [SongController::class,'editSong'])->middleware(['auth:api', 'can:edit_song']);
});

Route::prefix('/mv')->group(function(){
    Route::get('/get-full-infor-mv/{mv_id}', [MvController::class,'getFullInforMV']);
    Route::get('/get-all-mv', [MvController::class,'getAllMV']);
    Route::get('/get-list-mv/{id}', [MvController::class,'getListMvOfSinger']);
    Route::get('/get-by-genre/{id}', [MvController::class,'getByGenre']);
});

Route::prefix('/album')->group(function(){
    Route::get('/get-all-album', [AlbumController::class,'getAllAlbum']);
    Route::get('/get-full-info-album', [AlbumController::class,'getFullInforAllAlbum']);
    Route::get('/get-full-infor-album/{albumId}', [AlbumController::class,'getFullInforAlbum']);
    Route::get('/{album_id}', [AlbumController::class,'getAlbum']);
    Route::get('/{album_id}/songs', [AlbumController::class,'getAlbumSongs']);
    Route::get('/{album_id}/singers', [AlbumController::class,'getAlbumSingers']);
    Route::post('/store', [AlbumController::class,'createAlbum'])->middleware(['auth:api', 'can:add_album']);;
    Route::post('/store-edit', [AlbumController::class,'editAlbum'])->middleware(['auth:api', 'can:edit_album']);
});

Route::prefix('/genre')->group(function(){
    Route::get('/get-full-infor-genre/{genreId}', [GenreController::class,'getFullInforGenre']);
    Route::get('/get-all-genre', [GenreController::class,'getAllGenre']);
    Route::middleware(['auth:api', 'can:add_genre'])->post('/store', [GenreController::class,'createGenre']);
    Route::middleware(['auth:api', 'can:edit_genre'])->post('/store-edit', [GenreController::class,'editGenre']);
});

Route::prefix('/playlist')->group( function(){
    Route::get('/get-corner', [PlaylistController::class,'getCornerPlaylist']);
    Route::get('/get-playlist-hub', [PlaylistController::class, 'getPlayListHub']);
    Route::get('/get-all-new-playlist-in-month', [PlaylistController::class,'getAllNewPlaylistInMonth']);
    Route::middleware('auth:api')->get('/get-all-playlist-user', [PlaylistController::class,'getAllPlaylistUser']);
    Route::get('/get-top-five-selected-today', [PlaylistController::class,'getTopFivePlaylist']);
    Route::middleware('auth:api')->get('/get-infor-playlist/{playlistId}', [PlaylistController::class,'getInforPlaylist']);
    Route::middleware('auth:api')->get('/justnow', [PlaylistController::class,'getJustNow']);
    Route::get('/{playlistId}', [PlaylistController::class,'getPlaylist']);
    Route::middleware('auth:api')->post('/update', [PlaylistController::class,'updatePlaylist']);
    Route::middleware('auth:api')->post('/store', [PlaylistController::class,'createPlaylist']);
});
 
Route::prefix('/slide')->group(function(){
    Route::get('/get-slide', [SlideController::class,'getSlide']);
    Route::get('/get-full-infor-slide/{id} ', [SlideController::class,'getFullInforSlide']);
    Route::get('/get-all-slide', [SlideController::class,'getAllSlide']);
    Route::middleware(['auth:api', 'can:add_slide'])->post('/store', [SlideController::class,'createSlide']);
    Route::middleware(['auth:api', 'can:edit_slide'])->post('/store-edit', [SlideController::class,'updateSlide']);
});

Route::prefix('/public-chat')->group( function(){
    Route::get('/messages/{room_id}', [PublicChatController::class,'fetchMessages']);
    Route::middleware('auth:api')->post('/messages', [PublicChatController::class,'sendMessages']);
    Route::get('/room-chat/{room_id}', [PublicChatController::class,'getRoomChat']);
    Route::get('/get-rooms', [PublicChatController::class,'getRooms']);
});

Route::prefix('/permission')->group( function(){
    Route::middleware('auth:api')->get('/', [PermissionController::class,'getAllPermission']);
});

Route::prefix('/post')->group(function(){
    Route::get('/page/{numberPage}/number-of-post/{numberOfPost}', [PostController::class, 'getPostByPage']);
    Route::middleware('auth:api')->post('/store', [PostController::class, 'createPost']);
    Route::middleware('auth:api')->post('/edit-description', [PostController::class, 'editDescription']);
    Route::middleware('auth:api')->post('/delete', [PostController::class, 'delete']);
    Route::middleware('auth:api')->post('/like', [PostController::class, 'like']);
    Route::middleware('auth:api')->post('/unlike', [PostController::class, 'unlike']);
});

Route::prefix('/following')->group(function(){
    Route::get('/get-all-following/{id}', [FollowController::class, 'getAllFollowing']);
    Route::middleware('auth:api')->post('/unfollowing', [FollowController::class, 'unfollowing']);
    Route::middleware('auth:api')->post('/following', [FollowController::class, 'following']);
});

Route::prefix('/comment')->group(function(){
    Route::middleware('auth:api')->post('/', [CommentController::class, 'sendComment']);
});

