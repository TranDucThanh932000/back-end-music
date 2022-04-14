<?php

namespace App\Http\Controllers\api\v1;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\RoomChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PublicChatController extends Controller
{
    public function fetchMessages(Request $request){
        // $arrayMess = Message::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $arrayMess = Message::select('user_id','message','messages.created_at','users.fullname','users.avatar')
        ->join('users','users.id','=','messages.user_id')
        ->where('room_id', $request->room_id)
        ->orderBy('messages.created_at', 'desc')
        ->take(10)->get();
        return response(['messages' => $arrayMess], 200);
    }

    public function sendMessages(Request $request){
        $userAuth = auth()->user();
        $message = $userAuth->messages()->create(
            [
                'message' => $request->message,
                'room_id' => $request->room_id
            ]
        );

        Artisan::call('cache:clear');
        broadcast(new MessageSent($userAuth, $message->load('user'), $request->room_id))->toOthers();
        Artisan::call('queue:work --stop-when-empty', []);
        return response(['message' => $message], 200);
    }

    public function getRoomChat(Request $request){
        return response(['room' => RoomChat::find($request->room_id)], 200);
    }

    public function getRooms(Request $request){
        $room = [1, 2, 2, 1, 2, 1, 2, 1 , 1, 2, 1 ,1];
        $rooms = [];
        for($i = 0; $i < count($room); $i++){
            $chat = RoomChat::find($room[$i]);
            array_push($rooms, $chat);
        }
        return response(['rooms' => $rooms], 200);
    }
}
