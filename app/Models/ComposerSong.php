<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComposerSong extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['composer_id', 'song_id'];
    protected $softDelete = ['deleted_at'];

}
