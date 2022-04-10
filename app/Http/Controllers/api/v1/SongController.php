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
}
