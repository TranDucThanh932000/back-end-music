<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Album;
use App\Models\Singer;

class SingerAlbum extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['singer_id', 'album_id'];
    protected $guarded =[]; 
    protected $softDelete = ['deleted_at'];

    public function album(){
        return $this->belongsTo(Album::class, 'album_id');
    }

    public function singer(){
        return $this->belongsTo(Singer::class, 'singer_id');
    }
}
