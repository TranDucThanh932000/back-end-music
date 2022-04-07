<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

//cổng này để nhận tin nhắn, bắt sự kiện nhập phím và lượng ra vào
Broadcast::channel('chat-1', function ($user) {
    if(auth()->check()){
        return $user;
    }else{
        return true;
    }
});

Broadcast::channel('chat-2', function ($user) {
    if(auth()->check()){
        return $user;
    }else{
        return true;
    }
});



