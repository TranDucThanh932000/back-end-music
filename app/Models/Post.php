<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Postlike;
use App\Models\Comment;
use App\Models\Content;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['description', 'user_id'];
    protected $softDelete = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postlikes(){
        return $this->hasMany(Postlike::class, 'post_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function contents(){
        return $this->hasMany(Content::class, 'post_id');
    }
}
