<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Message;
use App\Models\Singer;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, CascadeSoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'fullname',
        'username',
        'avatar',
        'email',
        'password',
    ];
    protected $softDelete = ['deleted_at'];
    protected $cascadeDeletes = ['singers', 'composers'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function messages(){
        return $this->hasMany(Message::class,'user_id');
    }

    public function singers(){
        return $this->hasMany(Singer::class, 'user_id');
    }

    public function composers(){
        return $this->hasMany(Composer::class, 'user_id');
    }

    public function playlists(){
        return $this->hasMany(Playlist::class, 'user_id');
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
