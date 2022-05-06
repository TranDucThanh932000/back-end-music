<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Events\CommentSent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CommentController extends Controller
{
    public function sendComment(Request $request){
        try{
            DB::beginTransaction();
            $userAuth = auth()->user();
            $comment = $userAuth->comments()->create([
                'content' => $request->comment,
                'post_id' => $request->postId
            ]);
            DB::commit();
            Artisan::call('cache:clear');
            broadcast(new CommentSent($userAuth, $comment->load('user'), $request->postId))->toOthers();
            Artisan::call('queue:work --stop-when-empty', []);
            return response([
                'message' => 'success'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response([
                'message' => $e
            ], 400);
        }
    }
}
