<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\SingerAlbum;
use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Album;
use App\Models\Justnow;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class PlaylistController extends Controller
{

    public function getJustNow(Request $request){
        $user = auth()->user();
        $justnow = $user->justnows()->orderBy('created_at', 'desc')->limit(5)->get();
        $data = [];
        for($i = 0; $i < count($justnow); $i++){
            if($justnow[$i]->type == 'album'){
                $album = Album::find($justnow[$i]->link);
                $album->link = "/album/" . $album->id;
                array_push($data, $album);
            }else{
                $playlist = Playlist::find($justnow[$i]->link);
                $playlist->link = "/playlist/" . $playlist->id;
                array_push($data, $playlist);
            }
        }
        return response(['playlist' => $data], 200);
    }

    public function getPlaylist(Request $request){
        $data = Playlist::find($request->playlistId)->playlistSongs()->get();
        return response(['playlist' => $data], 200);
    }

    public function getInforPlaylist(Request $request){
        $user = auth()->user();
        $playlistOfUser = $user->playlists()->get();
        for($i = 0; $i < count($playlistOfUser); $i++){
            if($user->id == $playlistOfUser[$i]->user_id){
                $playlist = Playlist::find($request->playlistId);
                $data['name'] = $playlist->name;
                $data['songs'] = $playlist->playlistSongs()->get();
                return response(['data' => $data], 200);
            }
        }
        return response(['data' => null], 401);
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
        $singers = [4, 8, 6, 3, 7];
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
                'image' => '1RIBMiKcguJobFN8uB5e2OvdH2GeP1TK9',
                'user_id' => auth()->user()->id
            ]);
            $playlist->playlistsongs()->attach($listSongId);
            DB::commit();
            return response(['message' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }

    public function getAllPlaylistUser(Request $request){
        $playlists = auth()->user()->playlists()->get();
        return response(['playlists' => $playlists], 200);
    }

    public function updatePlaylist(Request $request){
        try{
            DB::beginTransaction();
            $user = auth()->user();
            $playlist = Playlist::find($request->playlistId);
            if($user->id == $playlist->user_id){
                $playlist->update([
                    'name' => $request->name
                ]);
                $playlist->playlistsongs()->sync($request->listSongId);
                DB::commit();
                return response(['message' => 'success'], 200);
            }
            return response(['message' => null], 401);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => null], 401);
        }
    }

    public function getAllNewPlaylistInMonth(){
        $playlistIds = [2,1,3,1,2];
        $data = [];
        foreach($playlistIds as $id){
            array_push($data, Playlist::find($id));
        }
        
        return response(['playlists' => $data], 200);
    }

}
