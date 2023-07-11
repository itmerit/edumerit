<?php

namespace Modules\MenuManage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\RolePermission\Entities\Permission;

class Sidebar extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected static function newFactory()
    {
        return \Modules\MenuManage\Database\factories\SidebarFactory::new();
    }
    public function permissionInfo()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id')->withDefault();
    }
    public function parentMenu()
    {
        return $this->belongsTo(Permission::class, 'parent', 'id')->withDefault();
    }
    public function deActiveChild()
    {
        return $this->hasMany('Modules\MenuManage\Entities\Sidebar','parent','permission_id')
        ->orderBy('position', 'ASC')
        ->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)->where('active_status', 0);
    }
    public function userChildMenu()
    {
        return $this->hasMany('Modules\MenuManage\Entities\Sidebar','parent','permission_id')->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id);
    }
    public function subModule()
    { 
        $user = auth()->user();
        return $this->hasMany('Modules\MenuManage\Entities\Sidebar','parent','permission_id')
        // ->whereNotIn('permission_id', deActivePermissions())
        ->with('permissionInfo', 'subModule')
        ->whereHas('permissionInfo', function($q) use ($user){
            $q->where('menu_status', 1)->when($user->role_id == 2, function($q){
                $q->where('is_student', 1);
            })->when($user->role_id == 3, function($q){
                $q->where('is_parent', 1);
            })->when(!in_array($user->role_id, [2,3]) , function($q){
                $q->where('is_admin', 1)->orWhere('is_teacher', 1);
            });
        })
        ->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)->where('active_status', 1)->orderBy('position', 'ASC');
    }
    public function deActiveSubMenu()
    { 
        $user = auth()->user();
        return $this->hasMany('Modules\MenuManage\Entities\Sidebar','parent','permission_id')
        // ->whereNotIn('permission_id', deActivePermissions())
        ->with('permissionInfo', 'deActiveSubMenu')
        ->whereHas('permissionInfo', function($q) use ($user){
            $q->where('menu_status', 1)->when($user->role_id == 2, function($q){
                $q->where('is_student', 1);
            })->when($user->role_id == 3, function($q){
                $q->where('is_parent', 1);
            })->when(!in_array($user->role_id, [2,3]) , function($q){
                $q->where('is_admin', 1);
            });
        })
        ->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)->where('active_status', 0)->orderBy('position', 'ASC');
    }
    public function permissionSection()
    {
        return $this->belongsTo(PermissionSection::class, 'permission_id', 'id')->whereNotNull('parent_section')->withDefault();
    }
    public function scopeDeActiveMenuUser($q)
    {
        return $q->where('ignore', 0)       
        ->where('active_status', 0)
        ->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id);
    }
    public function scopeActiveMenuUser($q)
    {
        return $q->where('ignore', 0)       
        ->where('active_status', 1)
        ->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id);
    }
   
}
