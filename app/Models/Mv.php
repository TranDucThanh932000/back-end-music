<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Song;

class Mv extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['src', 'image' ,'view', 'song_id'];
    protected $softDelete = ['deleted_at'];

    public function song(){
        return $this->hasOne(Song::class);
    }
}
