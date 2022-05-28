<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Singer;
use App\Models\Song;
use Exception;

class AlbumController extends Controller
{

    public function createAlbum(Request $request){
        try{
            DB::beginTransaction();
            $album = Album::create([
                'name' => $request->name,
                'image' => $request->image,
                'releaseDate' => $request->releaseDate,
            ]);
            $album->albumsingers()->attach($request->singers);
            DB::commit();
            return response([ 'status' =>  'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response([ 'status' => $e ], 400);
        }
    }

    public function editAlbum(Request $request){
        try{
            DB::beginTransaction();
            $album = Album::find($request->albumId);
            $album->update([
                'name' => $request->name,
                'image' => $request->image,
                'releaseDate' => $request->releaseDate
            ]);
            $arrSingers = $request->singers;
            $album->albumsingers()->sync($arrSingers);
            DB::commit();
            return response(['status' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status' => $e], 400);
        }
    }

    public function getFullInforAlbum(Request $request){
        $album = Album::find($request->albumId);
        $singers = $album->albumsingers()->get();
        $data['album'] = $album;
        $data['singers'] = $singers;
        return response([ 'album' => $data], 200);
    }

    public function getAllAlbum(){
        return response([ 'albums' => Album::all() ], 200);
    }

    public function getAlbum(Request $request){
        return response(['album' => Album::find($request->album_id)], 200);
    }

    public function getAlbumSongs(Request $request){
        return response(['albumSongs' => Album::find($request->album_id)->albumsongs()->get()], 200);
    }

    //Lấy tất cả những ca sỹ có hát trong album này
    public function getAlbumSingers(Request $request){
        $songsOfAlbum = Album::find($request->album_id)->albumsongs()->get();
        $arrId = [];
        $inc = 0;
        foreach( $songsOfAlbum as $song )
        {
            $songTemp = Song::find($song->id)->songsingers()->get();
            foreach($songTemp as $item){
                $arrId[$inc] = $item->pivot->singer_id;
                $inc++;
            }
        }
        $arrId = array_unique($arrId);
        $singers = [];
        foreach($arrId as $id){
            $singers[$id] = Singer::find($id);
        }
        return response(['albumSingers' => $singers], 200);
    }

    public function getFullInforAllAlbum(){
        try{
            $genres = Genre::all();
            $data = [];
            for($i = 0; $i < count($genres); $i++){
                $temp = $genres[$i]->genresongs()->get(); 
                for($j = 0; $j < count($temp); $j++){
                    $singers = $temp[$j]->songsingers()->get();
                    for($k = 0; $k < count($singers); $k++){
                        $temp2['img'] = $singers[$k]->user()->first()->avatar;
                        $temp2['nickname'] = $singers[$k]->nickname;
                        $temp2['singer_id'] = $singers[$k]->id;
                        $temp2['genre_id'] = $genres[$i]->id;
                        $data[$genres[$i]->name][$singers[$k]->id] = $temp2;
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
            ], 500);
        }
    }
}
