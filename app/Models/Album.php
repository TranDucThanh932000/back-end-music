<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SingerAlbum;
use App\Models\AlbumSong;

class Album extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'image', 'releaseDate'];
    protected $softDelete = ['deleted_at'];

    public function albumsingers(){
        return $this->belongsToMany(Singer::class,'singer_albums');
    }

    public function albumsongs(){
        return $this->belongsToMany(Song::class,'album_songs');
    }
}
