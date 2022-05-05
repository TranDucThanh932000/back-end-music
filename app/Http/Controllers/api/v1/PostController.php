<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Postlike;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getAllPost(){
        $getPosts = Post::all();
        $posts = [];
        for($i = 0; $i < count($getPosts); $i++){
            $post = $getPosts[$i];
            $post->user = $getPosts[$i]->user()->first();
            $post->likes = $getPosts[$i]->postlikes()->get();
            $post->comments = $getPosts[$i]->comments()->get();
            for($j = 0; $j < count($post['comments']); $j++){
                $post['comments'][$j]->user = $post['comments'][$j]->user()->first();
            }
            $post->contents = $getPosts[$i]->contents()->get();
            array_push($posts, $post);
        }
        return response(['posts' => $posts], 200);
    }

    public function like(Request $request){
        try{
            DB::beginTransaction();
            $postlike = Postlike::create([
                'post_id' => $request->postId,
                'user_id' => $request->userId
            ]);
            DB::commit();
            return response([
                'message' => 'success',
                'postlike' => $postlike
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }

    public function unlike(Request $request){
        try{
            DB::beginTransaction();
            Postlike::where([
                'post_id' => $request->postId,
                'user_id' => $request->userId
            ])->delete();
            DB::commit();
            return response([
                'message' => 'success'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }
}
