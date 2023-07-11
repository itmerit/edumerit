<?php

namespace Modules\RolePermission\Entities;

use App\InfixModuleManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Modules\RolePermission\Entities\InfixRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];    


    public function roles()
    {
        return $this->belongsToMany(InfixRole::class, 'assign_permissions', 'permission_id', 'role_id');
    }

    public function assign()
    {
        return $this->hasMany(AssignPermission::class, 'role_id', 'id');
    }

    public function childs()
    {
        $studentParent = session()->get('role_permission_user_type');
        return $this->hasMany(Permission::class, 'parent_route', 'route')
        ->when(!in_array($studentParent, [2,3]) , function($q){
            $q->where('is_admin', 1);
        })->when($studentParent == 2, function($q){
            $q->where('is_student', 1);
        }, function($elseQ){
            $elseQ->where('is_admin', 1);
        })->when($studentParent == 3, function($q){
            $q->where('is_parent', 1);
        }, function($elseQ){
            $elseQ->where('is_admin', 1);
        })
        ->with('childs');
    }

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_route', 'route');
    }

    public function subModule()
    {        
        $studentParent = (int) session()->get('role_permission_user_type');
        
        return $this->hasMany('Modules\RolePermission\Entities\Permission','parent_route','route')
        ->whereNotNull('route')->where('route', '!=', '')       
        ->when($studentParent == 2, function($q){
            $q->where('is_student', 1);
        })->when($studentParent == 3, function($q){
            $q->where('is_parent', 1);
        })->when(!in_array($studentParent, [2,3]) , function($q){
            $q->where('is_admin', 1)->orWhere('is_teacher', 1);
        })
        // ->whereNotIn('id', deActivePermissions())
        ->where('menu_status', 1);
    }
    public function scopeWhereNotInDeaActiveModulePermission($query)
    {        
        $activeModuleList = InfixModuleManager::where('is_default', 0)
        ->whereNull('purchase_code')->pluck('name')->toArray();
          
        $deActiveModules = [];            
        foreach($activeModuleList as $module) {
            if(moduleStatusCheck($module)==false) {
                $deActiveModules[] = $module;
            }
        }
        return $query->where(function($q) use($deActiveModules) {
          $q->whereNotIn('module', $deActiveModules)->orWhereNull('module');           
        });
    }
    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            Cache::forget('PermissionList_' . SaasDomain());
            Cache::forget('RoleList_' . SaasDomain());
            Cache::forget('oldPermissionSync' . SaasDomain());
        });
        self::updated(function ($model) {
            Cache::forget('PermissionList_' . SaasDomain());
            Cache::forget('RoleList_' . SaasDomain());
            Cache::forget('PolicyPermissionList_' . SaasDomain());
            Cache::forget('PolicyRoleList_' . SaasDomain());
            Cache::forget('oldPermissionSync' . SaasDomain());
        });
    }
    public function scopeRoleWise($q)
    {
        $role_id = auth()->user()->role_id;
        return $q->whereNotNull('route')->where('route', '!=', '')       
        ->when($role_id == 2, function($q){
            $q->where('is_student', 1);
        })->when($role_id == 3, function($q){
            $q->where('is_parent', 1);
        })->when(!in_array($role_id, [2,3]) , function($q){
            $q->where('is_admin', 1);
        });
    }
}
