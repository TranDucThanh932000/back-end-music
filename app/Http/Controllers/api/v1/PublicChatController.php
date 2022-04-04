<?php

namespace App\Http\Controllers\api\v1;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class PublicChatController extends Controller
{
    public function fetchMessages(Request $request){
        $arrayMess = Message::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        return response(['message' => $arrayMess], 200);
    }

    public function sendMessages(Request $request){
        $message = auth()->user()->messages()->create(['message' => $request->message]);

        
        Artisan::call('cache:clear');
        broadcast(new MessageSent(auth()->user(), $message->load('user')))->toOthers();
        Artisan::call('queue:work --stop-when-empty', []);

        return response(['message' => $message], 200);
    }
}
