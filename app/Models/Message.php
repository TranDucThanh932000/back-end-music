<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\RoomChat;

class Message extends Model
{
    // use HasFactory;

    protected $fillable = ['message','user_id','room_id'];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function roomchat(){
        return $this->belongsTo(RoomChat::class);
    }
}
