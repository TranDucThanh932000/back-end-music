<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Singer;
use App\Models\Song;

class AlbumController extends Controller
{
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
}
