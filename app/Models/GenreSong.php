<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenreSong extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['song_id', 'genre_id'];
    protected $softDelete = ['deleted_at'];
}
