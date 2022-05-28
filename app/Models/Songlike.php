<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Songlike extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['song_id', 'user_id'];
    protected $softDelete = ['deleted_at'];
}
