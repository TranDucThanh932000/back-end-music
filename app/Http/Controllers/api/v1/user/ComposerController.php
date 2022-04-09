<?php

namespace App\Http\Controllers\api\v1\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Composer;

class ComposerController extends Controller
{
    public function getAllComposer(){
        return response(['composers' => Composer::all()], 200);
    }

    public function getComposer(Request $request){
        return response(['composer' => Composer::find($request->composer_id)], 200);
    }

    public function getComposerSongs(Request $request){
        return response(['composerSongs' => Composer::find($request->composer_id)->composersongs()->get()], 200);
    }
}
