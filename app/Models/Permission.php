<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'display_name','parent_id', 'key_code'];
    protected $softDelete = ['deleted_at'];

    public function childPermission($child){
        return Permission::where('parent_id', $child)->get();
    }
}
