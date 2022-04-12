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
use App\Jobs\PutFile;
use App\Models\Composer;
use App\Models\Singer;
use Illuminate\Support\Facades\Artisan;

class UserController extends Controller
{

    public function getAllUser(){
        return response(['users' => User::all(), 200]);
    }

    public function getAlreadySingerComposer(Request $request){
        $singer = Singer::where('user_id', $request->userId)->get();
        $composer = Composer::where('user_id', $request->userId)->get();
        $alreadySinger = false;
        $alreadyComposer = false;
        if(count($singer)){
            $alreadySinger = true;
        }
        if(count($composer)){
            $alreadyComposer = true;
        }
        $already['singer'] = $alreadySinger;
        $already['composer'] = $alreadyComposer;
        return response(['already' => $already]);
    }

    public function setupAccount(Request $request){
        try{
            DB::beginTransaction();
            if($request->chbSinger){
                Singer::create([
                    'user_id' => $request->userId,
                    'nickname' => $request->nicknameSinger
                ]);
            }
            if($request->chbComposer){
                Composer::create([
                    'user_id' => $request->userId,
                    'nickname' => $request->nicknameComposer
                ]);
            }
            DB::commit();
            return response(['status' => 'success', 200]);
        }catch(Exception $e){
            DB::rollBack();
            return response(['status' => $e, 200]);
        }
    }

    public function currentUser()
    {
        return response(['user' => Auth::user()], 200);
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

    public function updateUser(Request $request)
    {
        $validator = null;
        try {
            if($request->has('avatar')){
                $validator = Validator::make($request->all(), [
                    'fullname' => 'required|string',
                    'username' => 'required|string',
                    'email' => 'required|string',
                    'avatar' => 'required|mimetypes:image/jpeg,image/png|max:6000'
                ]);
                try {
                    $nameFile = md5($request->avatar->getClientOriginalName());
    
                    if (Auth::user()->username == $request->username) {
                        $request->avatar->storeAs('/', $nameFile, 'public');
    
                        $temp = [];
                        foreach ($request->all() as $key => $item) {
                            $temp[$key] = $item;
                        }
                        $temp['avatar'] = $nameFile;
                        Artisan::call('cache:clear');
                        PutFile::dispatch($temp);
                        Artisan::call('queue:work --stop-when-empty', []);
                        // Nếu mình dùng queue:work ở terminal luôn thì nó sẽ cache những job mà mình đã từng thực hiện, nên nếu 
                        // cùng đẩy lên 1 cái ảnh với cùng tên và cùng file đó thì nó sẽ overwrite lại file trên Google Drive
                        // chứ không tạo mới 1 file như cách bên trên
                        return response(['avatar' => 'success'], 200);
                    }
                    return response(['message' => 'Permission denied'], 403);
                } catch (Exception $exx) {
                    return response(['message' => $exx], 400);
                }
            }else{
                $validator = Validator::make($request->all(), [
                    'fullname' => 'required|string',
                    'username' => 'required|string',
                    'email' => 'required|string',
                ]);
                try {
    
                    if (Auth::user()->username == $request->username) {
                        User::where('username', $request->username)->update([
                            'fullname' => $request->fullname,
                            'email' => $request->email,
                        ]);
                        return response(['avatar' => 'success'], 200);
                    }
                    return response(['message' => 'Permission denied'], 403);
                } catch (Exception $exx) {
                    return response(['message' => $exx], 400);
                }
            }

        } catch (Exception $exc) {
            return response(['message' => $validator->fails()]);
        }
    }
}
