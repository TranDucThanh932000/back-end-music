<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;
use Exception;

class GenreController extends Controller
{
    public function getAllGenre(){
        return response([ 'genres' => Genre::all() ], 200);
    }

    public function createGenre(Request $request){
        try{
            DB::beginTransaction();
            Genre::create([
                'name' => $request->name,
                'image' => $request->image,
                'description' => $request->description
            ]);
            DB::commit();
            return response([ 'status' => 'success' ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response([ 'status' => $e ], 200);
        }
    }

    public function getFullInforGenre(Request $request){
        $genre = Genre::find($request->genreId);
        return response([ 'genre' => $genre ], 200);
    }

    public function editGenre(Request $request){
        try{
            DB::beginTransaction();
            Genre::find($request->genreId)->update([
                'name' => $request->name,
                'image' => $request->image,
                'description' => $request->description
            ]);
            DB::commit();
            return response([ 'status' => 'success' ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response([ 'status' => $e ], 200);
        }
    }
}
