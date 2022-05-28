<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlbumSong;
use App\Models\ComposerSong;
use App\Models\GenreSong;
use App\Models\SingerSong;
use App\Models\Song;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Playlist;
use App\Models\Singer;
use Illuminate\Support\Facades\DB;
use Exception;

class SongController extends Controller
{

    public function getAllSong(){
        return response([ 'songs' => Song::all() ], 200);
    }

    public function getSong(Request $request){
        return response(['song' => Song::find($request->song_id)], 200);
    }

    public function getFullInforSong(Request $request){
        $songFinded = Song::find($request->song_id);
        $singers = $songFinded->songsingers()->get();
        $composers = $songFinded->songcomposers()->get();
        $genres = $songFinded->songgenres()->get();
        $albums = $songFinded->songalbums()->get();
        $song = [];
        $song['singers'] = $singers;
        $song['composers'] = $composers;
        $song['genres'] = $genres;
        $song['albums'] = $albums;
        $song['songFinded'] = $songFinded;
        return response(['song' => $song], 200);
    }

    public function createSong(Request $request){
        try{
            DB::beginTransaction();
            $song = Song::create([
                'name' => $request->name,
                'lyrics' => $request->lyrics,
                'timeDuration' => $request->timeDuration,
                'image' => $request->image,
                'src' => $request->src,
                'releaseDate' => $request->releaseDate
            ]);
            $arrComposer = $request->composers;
            $arrSinger = $request->singers;
            $arrGenre = $request->genres;
            for($i = 0; $i < count($arrComposer); $i++){
                ComposerSong::create([
                    'composer_id' => $arrComposer[$i],
                    'song_id' => $song->id
                ]);
            }
            for($i = 0; $i < count($arrSinger); $i++){
                SingerSong::create([
                    'singer_id' => $arrSinger[$i],
                    'song_id' => $song->id
                ]);
            }
            for($i = 0; $i < count($arrGenre); $i++){
                GenreSong::create([
                    'genre_id' => $arrGenre[$i],
                    'song_id' => $song->id
                ]);
            }
            if(count($request->albums) != 0){
                $arrAlbum = $request->albums;
                for($i = 0; $i < count($arrAlbum); $i++){
                    AlbumSong::create([
                        'album_id' => $arrAlbum[$i],
                        'song_id' => $song->id
                    ]);
                }
            }
            DB::commit();
            return response(['status' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status' => $e], 400);
        }
    }

    public function editSong(Request $request){
        try{
            DB::beginTransaction();
            $song = Song::find($request->songId);
            $song->update([
                'name' => $request->name,
                'lyrics' => $request->lyrics,
                'timeDuration' => $request->timeDuration,
                'image' => $request->image,
                'src' => $request->src,
                'releaseDate' => $request->releaseDate
            ]);
            $arrComposer = $request->composers;
            $arrSinger = $request->singers;
            $arrGenre = $request->genres;
            $song->songcomposers()->sync($arrComposer);
            $song->songsingers()->sync($arrSinger);
            $song->songgenres()->sync($arrGenre);
            if(count($request->albums) != 0){
                $song->songalbums()->sync($request->albums);
            }else{
                $song->songalbums()->detach();
            }
            DB::commit();
            return response(['status' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status' => $e], 400);
        }
    }

    public function getTopThree(){
        $top3 = Song::select('*')
        ->orderBy('view', 'desc')
        ->limit(3)
        ->get();
        for($i = 0; $i < 3; $i++){
            $top3[$i]['singer'] = $top3[$i]->songsingers()->get(); 
        }
        return response([ 'top3' =>  $top3]);
    }

    public function getTop100(){
        $top100 = Song::select('*')
        ->orderBy('view', 'desc')
        ->limit(100)
        ->get();
        for($i = 0; $i < count($top100); $i++){
            $top100[$i]['singer'] = $top100[$i]->songsingers()->get(); 
        }
        return response([ 'songs' =>  $top100]);
    }

    public function getTop5AllGenre(){
        $songsVN = Genre::find(2)->genresongs()->orderBy('view', 'desc')->limit(5)->get();
        for($i = 0; $i < count($songsVN); $i++){
            $songsVN[$i]['singer'] = $songsVN[$i]->songsingers()->get(); 
        }

        $songsUsuk = Genre::find(4)->genresongs()->orderBy('view', 'desc')->limit(5)->get();
        for($i = 0; $i < count($songsUsuk); $i++){
            $songsUsuk[$i]['singer'] = $songsUsuk[$i]->songsingers()->get(); 
        }

        $songsKpop = Genre::find(3)->genresongs()->orderBy('view', 'desc')->limit(5)->get();
        for($i = 0; $i < count($songsKpop); $i++){
            $songsKpop[$i]['singer'] = $songsKpop[$i]->songsingers()->get(); 
        }
        $songs['vietnam'] = $songsVN;
        $songs['usuk'] = $songsUsuk;
        $songs['kpop'] = $songsKpop;
        return response(['songs' => $songs], 200);
    }

    public function getAllSongInAlbum(Request $request){
        $songs = Album::find($request->albumId)->albumsongs()->get();
        return response(['songs' => $songs], 200);
    }

    public function getAllSongInPlaylist(Request $request){
        $songs = Playlist::find($request->playlistId)->playlistSongs()->get();
        return response(['songs' => $songs], 200);
    }

    public function getSongByTxtSearch(Request $request){
        $songs = Song::where('name', 'Like', "%{$request->txtSearch}%")
        ->orderBy('view','desc')
        ->limit(3)
        ->get();
        for($i = 0; $i < count($songs); $i++){
            $songs[$i]['singer'] = $songs[$i]->songsingers()->get();
        }
        return response(['songs' => $songs], 200);
    }

    public function getTopNewSongs(){
        $top100 = Song::select('*')
        ->orderBy('releaseDate', 'desc')
        ->limit(100)
        ->get();
        for($i = 0; $i < count($top100); $i++){
            $top100[$i]['singer'] = $top100[$i]->songsingers()->get(); 
        }
        return response([ 'songs' =>  $top100]);
    }

    public function getSongPlaylistUser(){
        $user = auth()->user();
        $playlist = $user->playlists()->first();
        if($playlist){
            $songs = $playlist->playlistsongs()->get();
            for($i = 0; $i < count($songs); $i++){
                $songs[$i]['singer'] = $songs[$i]->songsingers()->get(); 
            }
            return response(['songs' => $songs], 200);
        }else{
            $songs = Song::where('name','Nếu ngày ấy')->get();
            for($i = 0; $i < count($songs); $i++){
                $songs[$i]['singer'] = $songs[$i]->songsingers()->get(); 
            }
            return response(['songs' => $songs], 200);
        }
    }

    public function getSongByGenreAndSinger(Request $request){
        try{
            $data = [];
            $songsOfSinger = Singer::where('id', $request->singer_id)->first()->singersongs()->get();
            foreach($songsOfSinger as $song){
                $genres =  $song->songgenres()->get();
                foreach($genres as $genre){
                    if($genre->id == $request->genre_id){
                        array_push($data, $song);
                    }
                }
            }

            return response([
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ], 200);
        }catch(Exception $e){
            return response([
                'status' => 500,
                'message' => $e
            ], 200);
        }
    }


}
