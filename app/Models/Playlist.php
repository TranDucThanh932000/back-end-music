<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'image'];
    protected $softDelete = ['deleted_at'];

    public function playlistSongs(){
        return $this->belongsToMany(Song::class, 'playlist_songs');
    }
}
