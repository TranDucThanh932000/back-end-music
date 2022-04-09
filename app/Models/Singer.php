<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Album;
use App\Models\Song;

class Singer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'nickname'];
    protected $softDelete = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function singeralbums(){
        return $this->belongsToMany(Album::class,'singer_albums');
    }

    public function singersongs(){
        return $this->belongsToMany(Song::class,'singer_songs');
    }

}
