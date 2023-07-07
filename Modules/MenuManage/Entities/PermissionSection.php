<?php

namespace Modules\MenuManage\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionSection extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\MenuManage\Database\factories\PermissionSectionFactory::new();
    }
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'section_id', 'id');       
    }
    public function activeMenus()
    {
        // $sidebarPermissionIds = Sidebar::where('user_id', auth()->user()->id)->where('active_status', 1)->pluck('permission_id')->toArray();
        // ->whereIn('id', $sidebarPermissionIds)

        return $this->permissions()->where('type', 1)
        ->when(!in_array(auth()->user()->role_id, [2,3]), function($q){
            $q->where('is_admin', 1);
        })->when(auth()->user()->role_id == 2, function($q){
            $q->where('is_student', 1);
        })->when(auth()->user()->role_id == 3, function($q){
            $q->where('is_parent', 1);
        })->where('menu_status', 1);
    }

    public function activeSubmenus()
    {
        // $sidebarPermissionIds = Sidebar::where('user_id', auth()->user()->id)->where('active_status', 1)->pluck('permission_id')->toArray();
        // ->whereIn('id', $sidebarPermissionIds)

        return $this->permissions()->where('type', 2)
        ->where('menu_status', 1)
        ->when(!in_array(auth()->user()->role_id, [2,3]), function($q){
            $q->where('is_admin', 1);
        })->when(auth()->user()->role_id == 2, function($q){
            $q->where('is_student', 1);
        })->when(auth()->user()->role_id == 3, function($q){
            $q->where('is_parent', 1);
        });
    }

    public function activeActions()
    {
        return $this->permissions()->where('type', 3)->where('menu_status', 1)
        ->when(!in_array(auth()->user()->role_id, [2,3]), function($q){
            $q->where('is_admin', 1);
        })->when(auth()->user()->role_id == 2, function($q){
            $q->where('is_student', 1);
        })->when(auth()->user()->role_id == 3, function($q){
            $q->where('is_parent', 1);
        });
    }

    public function inActiveMenus()
    {
        return $this->permissions()->where('type', 1)->where('menu_status', 0);
    }

    public function inActiveSubmenus()
    {
        return $this->permissions()->where('type', 2)->where('menu_status', 0);
    }

    public function inActiveActions()
    {
        return $this->permissions()->where('type', 3)->where('menu_status', 0);
    }
    
    public function scopeRoleWise($query)
    {
        return $query->when(!in_array(auth()->user()->role_id, [2,3]), function($q){
            $q->where('is_admin', 0);
        })->when(auth()->user()->role_id == 2, function($q){
            $q->where('is_student', 1);
        })->when(auth()->user()->role_id == 3, function($q){
            $q->where('is_parent', 2);
        });

    }
    public function sidebars()
    {
        return $this->hasMany(Sidebar::class, 'section_id', 'id');
    }
}
