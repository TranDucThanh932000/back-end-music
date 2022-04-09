<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\GenreSong;

class Genre extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'description', 'image'];
    protected $softDelete = ['deleted_at'];

    public function genresongs(){
        return $this->belongsToMany(GenreSong::class,'genre_songs','song_id','genre_id');
    }
}
