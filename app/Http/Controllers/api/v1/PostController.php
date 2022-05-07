<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Post;
use App\Models\Postlike;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public function getPostByPage(Request $request){
        $getPosts = Post::select('*')
        ->orderBy('created_at', 'desc')
        ->skip(($request->numberPage - 1) * $request->numberOfPost)
        ->limit($request->numberOfPost)
        ->get();
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

    public function editDescription(Request $request){
        try{
            DB::beginTransaction();
            $checkPost = $this->checkAuthor($request->postId);
            if($checkPost){
                $checkPost->description = $request->description;
                $checkPost->save();
                DB::commit();
                return response(['message' => 'success'], 200);
            }
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }

        
    }

    public function delete(Request $request){
        try{
            DB::beginTransaction();
            $checkPost = $this->checkAuthor($request->postId);
            if($checkPost){
                $checkPost->delete();
                DB::commit();
                return response(['message' => 'success'], 200);
            }
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        } 
    }

    public function checkAuthor($postId){
        try{
            $user = auth()->user();
            //Check xem user này có phải chủ bài đăng này không
            $checkPost = Post::select('*')->where([
                'id' => $postId,
                'user_id' => $user->id
            ])->first();
            return $checkPost;
        }catch(Exception $e){
            return null;
        }
    }

    public function createPost(Request $request){
        try{
            DB::beginTransaction();
            $user = auth()->user();
            $post = Post::create([
                'description' => $request->description,
                'user_id' => $user->id
            ]);
            Content::create([
                'link' => $request->linkImg,
                'type' => 'image',
                'post_id' => $post->id
            ]);
            DB::commit();
            return response([
                'message' => 'success',
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }
}
