<?php

namespace Modules\MenuManage\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class SidebarNew extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return \Modules\MenuManage\Database\factories\SidebarNewFactory::new ();
    }

    public function scopeRoleUser($query)
    {
        return $query->where('school_id', auth()->user()->school_id)
            ->where('role_id', auth()->user()->role_id)
            ->where('user_id', auth()->user()->id);
    }

    public static function subMenuList($module_id)
    {
        $check = InfixPermissionAssign::where('role_id', auth()->user()->role_id)->get('module_id');
        $submenu = SidebarNew::query();
        if (moduleStatusCheck('Saas') == True) {
            $ids = [399, 400, 401, 402, 403, 404, 428, 429, 430, 431, 456, 457, 458, 459, 460, 461, 462, 463, 478, 482, 483, 484, 549];
            $submenu->whereNotIn('infix_module_id', $ids);
        }
        if (auth()->user()->role_id != 1) {
            $submenu->whereIn('infix_module_id', $check);
            $submenu->whereNotIn('infix_module_id', [833, 834]);
        }
        if (auth()->user()->role_id == 4) {
            $submenu->whereNotIn('infix_module_id', [833, 834]);
        }
        if (auth()->user()->role_id == 1) {
            $submenu->whereNotIn('infix_module_id', [193]);
        }


        $submenu = $submenu->where('module_id', $module_id)->where('type', 2)->roleUser()->orderby('id', 'ASC')->where('parent_id', '!=', 0)->get();
        return $submenu;
    }

    public static function subMenuListDefault($module_id)
    {
        $check = InfixPermissionAssign::where('role_id', auth()->user()->role_id)->get('module_id');
        $submenu = InfixModuleInfo::query();
        if (moduleStatusCheck('Saas') == True) {
            $ids = [399, 400, 401, 402, 403, 404, 428, 429, 430, 431, 456, 457, 458, 459, 460, 461, 462, 463, 478, 482, 483, 484, 549];
            $submenu->whereNotIn('id', $ids);
        }
        if (auth()->user()->role_id != 1) {
            $submenu->whereIn('id', $check);
        }
        if (auth()->user()->role_id == 4) {
            $submenu->whereNotIn('id', [833, 834]);
        }
        if (auth()->user()->role_id == 1) {
            $submenu->whereNotIn('id', [193]);
        }

        $submenu = $submenu->whereNotIn('id', [833, 834, 193]);
        $submenu = $submenu->where('parent_id', $module_id)->where('active_status', 1)->get();
        return $submenu;
    }

    public static function studentMenu($id)
    {

        $check = InfixPermissionAssign::where('role_id', auth()->user()->role_id)->get('module_id');
        return SidebarNew::where('module_id', $id)
            ->whereIn('infix_module_id', $check)
            ->where('parent_id', '!=', 0)->roleUser()
            ->get();
    }

    public static function studentMenuDefualt($id)
    {
        if (auth()->user()->role_id == 2) {
            $user_type = 1;
        } elseif (auth()->user()->role_id == 3) {
            $user_type = 2;
        }
        $check = InfixPermissionAssign::where('role_id', auth()->user()->role_id)->get('module_id');
        return InfixModuleStudentParentInfo::where('parent_id', $id)
            ->whereIn('id', $check)
            ->whereNotIn('parent_id', [1, 11, 56, 66])
            ->whereNotIn('name', ['edit', 'view', 'edit', 'add', 'add content'])
            ->where('active_status', 1)->where('user_type', $user_type)->get();
    }
}