<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Genre;
use App\Models\Singer;
use App\Models\Composer;
use App\Models\Album;

class Song extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name','lyrics','timeDuration','image','src','releaseDate'];
    protected $softDelete = ['deleted_at'];

    public function songgenres(){
        return $this->belongsToMany(Genre::class,'genre_songs')->withTimestamps();
    }

    public function songsingers(){
        return $this->belongsToMany(Singer::class,'singer_songs')->withTimestamps();
    }

    public function songcomposers(){
        return $this->belongsToMany(Composer::class,'composer_songs')->withTimestamps();
    }

    public function songalbums(){
        return $this->belongsToMany(Album::class,'album_songs')->withTimestamps();
    }

}
