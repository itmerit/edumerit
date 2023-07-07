<?php

namespace Modules\RolePermission\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\RolePermission\Entities\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignPermission extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'permission_id', 'status', 'menu_status', 'saas_schools', 'role_id', 'school_id'];
    protected $table = 'assign_permissions';
    protected static function newFactory()
    {
        return \Modules\RolePermission\Database\factories\AssignPermissionFactory::new();
    }
    public function permissionInfo()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
