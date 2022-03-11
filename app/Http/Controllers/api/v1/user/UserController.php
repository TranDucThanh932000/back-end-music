<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function currentUser()
    {
        return Auth::user();
    }

    public function createUser(Request $request)
    {
        $validator = null;
        try {
            $validator = Validator::make($request->all(), [
                'fullname' => 'required|string',
                'username' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|password'
            ]);

            try {
                DB::beginTransaction();
                User::create([
                    'fullname' => $request->fullname,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
                DB::commit();
            } catch (Exception $exx) {
                DB::rollBack();
                return response(['message' => 'Tên đăng nhập này đã tồn tại'], 400);
            }
            if (!Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])) {
                return response(['message' => 'Invalid login credentials.'], 401);
            }

            $accessToken = Auth::user()->createToken('authToken')->accessToken;
            return response(['user' => Auth::user(), 'access_token' => $accessToken], 200);

        } catch (Exception $ex) {
            return response(['message' => $validator->fails()], 401);
        }
    }
}
