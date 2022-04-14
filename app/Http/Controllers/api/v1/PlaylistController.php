<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\SingerAlbum;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Album;

class PlaylistController extends Controller
{
    public function getPlaylist(Request $request){
        $data = Song::find($request->playlistId);
        return response(['playlist' => $data], 200);
    }

    //selected today
    public function getTopFivePlaylist(){
        $singers = [3, 4, 5, 6, 8];
        $topFivePlaylist = [];
        for($i = 0; $i < count($singers); $i++){
            $album = SingerAlbum::where('singer_id',$singers[$i])->first();
            array_push($topFivePlaylist, Album::find($album->album_id));
        }
        return response(['playlist' => $topFivePlaylist], 200);
    }

    //corner
    public function getCornerPlaylist(){
        $singers = [9, 8, 6, 3, 7];
        $cornerPlaylist = [];
        for($i = 0; $i < count($singers); $i++){
            $album = SingerAlbum::where('singer_id', $singers[$i])->first();
            array_push($cornerPlaylist, Album::find($album->album_id));
        }
        return response(['playlistCorner' => $cornerPlaylist], 200);
    }
}
