<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlbumSong;
use App\Models\ComposerSong;
use App\Models\GenreSong;
use App\Models\SingerSong;
use App\Models\Song;
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
}
