<?php

namespace App\Http\Controllers\api\v1\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Singer;
use App\Models\Song;
use App\Models\User;

class SingerController extends Controller
{
    public function getAllSinger(){
        return response(['singers' => Singer::all()], 200);
    }

    public function getSinger(Request $request){
        return response(['singer' => Singer::find($request->singer_id)], 200);
    }

    public function getSingerSongs(Request $request){
        return response(['singerSongs' => Singer::find($request->singer_id)->singersongs()->get()], 200);
    }

    public function getSingerAlbums(Request $request){
        return response(['singerAlbums' => Singer::find($request->singer_id)->singeralbums()->get()], 200);
    }

    public function getUserBySinger(Request $request){
        $user = User::find($request->id);
        $singer = $user->singers()->first();
        $songs = $singer->singersongs()->orderBy('releaseDate','desc')->limit(3)->get();
        $singer->user = $user;
        $singer->songs = $songs;
        return response(['singer' => $singer], 200);
    }

    public function getSingerBySong(Request $request){
        $singers = Song::find($request->id)->songsingers()->get();
        return response(['singers' => $singers], 200);
    }
}
