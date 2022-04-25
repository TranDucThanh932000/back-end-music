<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function getAllPermission(){
        $data = Permission::where('parent_id', 0)->get();
        
        for($i = 0; $i < count($data); $i++){
            $child = Permission::where('parent_id', $data[$i]->id)->get();
            $data[$i]['child'] = $child;
        }
        return response(['data' => $data], 200);
    }


}
