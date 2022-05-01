<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Mv;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Genre;
use App\Models\Song;
use App\Models\User;
use Exception;

class MvController extends Controller
{
    public function getFullInforMV(Request $request){
        $mv = Mv::find($request->mv_id);
        $song = $mv->song()->first();
        $singers = $song->songsingers()->get();
        $mv['songName'] = $song->name;
        $mv['singers'] = $singers;
        return response([ 'mv' => $mv], 200);
    }

    public function getAllMV(){
        try{
            $mvs = Mv::all();
            for($i = 0; $i < count($mvs); $i++){
                $song = Song::find($mvs[$i]->song_id);
                $mvs[$i]['songName'] = $song->name;
                $mvs[$i]['singers'] = $song->songsingers()->get();
                $mvs[$i]['singerImg'] = User::find( ($mvs[$i]['singers'])[0]->user_id )->avatar;
            }
            return response(['mvs' => $mvs], 200);
        }catch(Exception $e){
            return response(['message' => $e], 400);
        }

    }

    public function getByGenre(Request $request){
        $genre = Genre::find($request->id);
        $songs = $genre->genresongs()->get();
        $mvs = [];
        for($i = 0; $i < count($songs); $i++){
            if($songs[$i]->mv_id != null){
                $mv = $songs[$i]->mv()->first();
                $mv['songName'] = $songs[$i]->name;
                $mv['singers'] = $songs[$i]->songsingers()->get();
                $mv['singerImg'] = User::find(($mv['singers'])[0]->user_id)->avatar;
                array_push($mvs, $mv);
            }
        }
        // if($request->status == 0){
        //     $c = collect($mvs);
        //     $sorted = $c->sortBy('view');
        //     $mvs = $c->toArray();
        // }else if($request->status == 1){
        //     $c = collect($mvs);
        //     $sorted = $c->sortBy('created_at');
        //     $mvs = $c->toArray();
        // }
        return response(['mvs' => $mvs], 200);
    }

    //api get tất cả các mv của những ca sỹ thuộc mv có id trong request
    public function getListMvOfSinger(Request $request){
        $mv = Mv::find($request->id);
        $song = $mv->song()->first();
        $singers = $song->songsingers()->get();
        $mvs = [];
        for($i = 0; $i < count($singers); $i++){
            $sg = $singers[$i]->singersongs()->where('mv_id', '!=', null)->get();
            for($j = 0; $j < count($sg); $j++){
                $sgers = $sg[$j]->songsingers()->get();
                $mvTemp = Mv::find($sg[$j]->mv_id);
                $mvTemp['singers'] = $sgers;
                $mvTemp['songName'] = $sg[$j]->name;
                array_push($mvs, $mvTemp);
            }
        }
        return response(['mvs' => $mvs], 200);
    }
}
