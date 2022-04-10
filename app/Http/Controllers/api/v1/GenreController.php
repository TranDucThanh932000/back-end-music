<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genre;

class GenreController extends Controller
{
    public function getAllGenre(){
        return response([ 'genres' => Genre::all() ], 200);
    }
}
