<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Song;

class Composer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'nickname'];
    protected $softDelete = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id');
    }

    public function composersongs(){
        return $this->belongsToMany(Song::class,'composer_songs');
    }
}
