<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['content', 'user_id','post_id'];
    protected $softDelete = ['deleted_at'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
