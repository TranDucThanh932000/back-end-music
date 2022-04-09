<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ComposerSong;
use App\Models\SingerSong;
use App\Models\Song;
use Illuminate\Support\Facades\DB;
use Exception;

class SongController extends Controller
{
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
            DB::commit();
            return response(['status' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status' => $e], 400);
        }
    }
}
