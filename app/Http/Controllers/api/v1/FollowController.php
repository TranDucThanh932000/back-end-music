<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function getAllFollowing(Request $request){
        return response(['followings' => User::find($request->id)->following()->get()], 200);
    }

    public function unfollowing(Request $request){
        try{
            DB::beginTransaction();
            Follow::where([
                'user_id' => $request->id,
                'follower' => $request->follower
            ])->delete();
            DB::commit();
            return response([ 'message' => 'success' ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response([ 'message' => $e ], 400);
        }
    }

    public function following(Request $request){
        try{
            DB::beginTransaction();
            $following = Follow::create([
                'user_id' => $request->user_id,
                'follower' => $request->follower
            ]);
            DB::commit();
            return response([ 
                'following' => $following,
                'message' => 'success' 
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response([ 'message' => $e ], 400);
        }
    }
}
