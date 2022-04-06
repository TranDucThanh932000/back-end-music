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

//cổng này để nhận tin nhắn và bắt sự kiện nhập phím
Broadcast::channel('lchat', function ($user) {
    return true;
});

//kiểm soát lượng ra vào
Broadcast::channel('join-chat', function ($user) {
    if(auth()->check()){
        return $user;
    }else{
        return true;
    }
});
