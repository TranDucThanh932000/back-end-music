<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Genre;
use App\Models\Singer;

class Song extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name','lyrics','timeDuration','image','src','releaseDate'];
    protected $softDelete = ['deleted_at'];

    public function genresongs(){
        return $this->belongsToMany(Genre::class,'genre_songs');
    }

    public function songsingers(){
        return $this->belongsToMany(Singer::class,'singer_songs');
    }

}
