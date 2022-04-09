<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SingerSong extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['singer_id', 'song_id'];
    protected $softDelete = ['deleted_at'];
}
