<?php

namespace Modules\RolePermission\Entities;

use App\InfixModuleManager;
use Illuminate\Database\Eloquent\Model;

class InfixModuleStudentParentInfo extends Model
{
    protected $guarded = ['id'];
    public static function studentMenu($id)
    {
        return InfixModuleStudentParentInfo::where('parent_id', $id)
            ->whereNotIn('parent_id', [1, 11, 56, 66])
            ->whereNotIn('name', ['edit', 'view', 'edit', 'add', 'add content'])
            ->where('active_status', 1)->get();
    }
    public static function studentMenuAll($parent_id, $child_id)
    {
        return $result = array_merge($parent_id, $child_id);
    }
    public function subModule()
    {

        return $this->hasMany('Modules\RolePermission\Entities\InfixModuleStudentParentInfo', 'parent_route', 'route')->whereNotNull('route')->where('route', '!=', '')
        ->when(session()->get('role_permission_user_type'), function ($q) {
          $q->whereNotInDeaActiveModulePermission(session()->get('role_permission_user_type'));
        })->where('active_status', 1);
    }
    public function scopeWhereNotInDeaActiveModulePermission($query, $user_type)
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
          $q->whereNotIn('module_name', $deActiveModules)->orWhereNull('module_name');           
        })->where('user_type', $user_type);
    }
}
