<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function getAllRole(){
        return response(['roles' => Role::all()], 200);
    }

    public function getFullInforRole(Request $request){
        $role = Role::find($request->id);
        $permissions = $role->rolepermissions()->get();
        $role['permissions'] = $permissions;
        return response(['role' => $role], 200);
    }

    public function createRole(Request $request){
        try{
            DB::beginTransaction();
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name
            ]);
            $role->rolepermissions()->attach($request->permissionIds);
            DB::commit();
            return response(['message' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }

    public function updateRole(Request $request){
        try{
            DB::beginTransaction();
            $role = Role::find($request->roleId);
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name
            ]);
            $role->rolepermissions()->sync($request->permissionIds);
            DB::commit();
            return response(['message' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }

    public function getAllUserHasRole(){
        $userHasRole = UserRole::select('user_id')->distinct()->get();
        $users = [];
        foreach($userHasRole as $key){
            array_push($users, User::find($key['user_id']));
        }
        return response(['users' => $users], 200);
    }

    public function getRoleOfUser(Request $request){
        $userRoles = User::find($request->id)->roles()->get();
        $arrId = [];
        foreach($userRoles as $role){
            array_push($arrId, $role->id);
        }
        return response(['userRoles' => $arrId], 200);
    }

    public function createUserRole(Request $request){
        try{
            DB::beginTransaction();
            User::find($request->userId)->roles()->sync($request->roles);
            DB::commit();
            return response(['message' => 'success'], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => $e], 400);
        }
    }
}
