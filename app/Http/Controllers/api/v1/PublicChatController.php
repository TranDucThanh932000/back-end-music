<?php

namespace App\Http\Controllers\api\v1;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
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
        broadcast(new MessageSent($userAuth, $message->load('user')))->toOthers();
        Artisan::call('queue:work --stop-when-empty', []);
        return response(['message' => $message], 200);
    }
}
