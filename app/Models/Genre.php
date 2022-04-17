<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'description', 'image'];
    protected $softDelete = ['deleted_at'];

    public function genresongs(){
        return $this->belongsToMany(Song::class,'genre_songs');
    }
}
