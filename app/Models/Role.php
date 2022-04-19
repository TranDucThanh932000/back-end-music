<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'display_name'];
    protected $softDelete = ['deleted_at'];

    public function rolepermissions(){
        return $this->belongsToMany(Permission::class,'role_permissions');
    }
}
