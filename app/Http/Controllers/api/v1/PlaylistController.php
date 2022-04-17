<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\SingerAlbum;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Album;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class PlaylistController extends Controller
{

    public function getJustNow(Request $request){
        $listPlaylist = [1,2,3,4,5];
        $data = [];
        for($i = 0; $i < count($listPlaylist); $i++){
            array_push($data, Playlist::find($listPlaylist[$i]));
        }
        return response(['playlist' => $data], 200);
    }

    public function getPlaylist(Request $request){
        $data = Playlist::find($request->playlistId)->playlistSongs()->get();
        return response(['playlist' => $data], 200);
    }

    public function getInforPlaylist(Request $request){
        $playlist = Playlist::find($request->playlistId);
        $data['name'] = $playlist->name;
        $data['songs'] = $playlist->playlistSongs()->get();
        return response(['data' => $data], 200);
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
        $singers = [2, 8, 6, 3, 7];
        $cornerPlaylist = [];
        for($i = 0; $i < count($singers); $i++){
            $album = SingerAlbum::where('singer_id', $singers[$i])->first();
            array_push($cornerPlaylist, Album::find($album->album_id));
        }
        return response(['playlistCorner' => $cornerPlaylist], 200);
    }
    
    public function createPlaylist(Request $request){
        $listSongId = $request->listSongId;
        try{
            DB::beginTransaction();
            $playlist = Playlist::create([
                'name' => $request->name,
                'image' => '1RIBMiKcguJobFN8uB5e2OvdH2GeP1TK9'
            ]);
            auth()->user()->userplaylists()->attach($playlist->id);
            $playlist->playlistsongs()->attach($listSongId);
            DB::commit();
            return response(['message' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }

    public function getAllPlaylistUser(Request $request){
        $playlists = auth()->user()->userplaylists()->get();
        return response(['playlists' => $playlists], 200);
    }
}
