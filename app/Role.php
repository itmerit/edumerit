<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class Role extends Model
{
    //
    public function permissions()
    {
        return $this->hasMany(InfixPermissionAssign::class, 'role_id', 'id');
    }
}
