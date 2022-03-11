<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request){
        $login = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if( !Auth::attempt( $login )){
            return response(['message' => 'Sai tài khoản hoặc mật khẩu'], 401);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['user' => Auth::user(), 'access_token' => $accessToken], 200);
    }

}
