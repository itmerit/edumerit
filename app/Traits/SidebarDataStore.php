<?php

namespace App\Traits;

use App\InfixModuleManager;
use Illuminate\Support\Facades\Cache;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;
use Modules\Saas\Entities\SaasSettings;

trait SidebarDataStore
{

    function defaultSidebarStore()
    {
        {
            $user = auth()->user();
            $exit = Sidebar::where('user_id', auth()->user()->id)->where('role_id', $user->role_id)->first();
            if ($exit) {
                return true;
            }
            $permissionInfos = $this->permissions();

            if (auth()->user()->role_id == 2 || auth()->user()->role_id == 3) {

                Sidebar::updateOrCreate([
                    'permission_id' => 1,
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,

                ], [
                    'position' => 1,
                    'level' => 1,
                    'parent' => null,
                ]);

            }
            $oldSidebar = collect();
            //  if(Schema::exists('sidebar_news')) {
            //     $oldSidebar = SidebarNew::where('user_id', auth()->user()->id)->get();
            //  }

            if (count($oldSidebar) > 0) {
                $oldPermissionIds = $oldSidebar->pluck('infix_module_id')->toArray();
                $permissionIds = Permission::whereIn('old_id', $oldPermissionIds)->get();

                foreach ($permissionInfos as $key => $sidebar) {
                    $parent_id = $this->parentId($sidebar);
                    if (in_array($sidebar->id, $permissionIds)) {
                        $this->storeSidebar($sidebar, $key, $parent_id);
                    }

                }
                Cache::forget('sidebars' . auth()->user()->id);
                return true;
            } elseif (count($oldSidebar) == 0 && (auth()->user()->role_id == 2 || auth()->user()->role_id == 3)) {
              
                foreach ($permissionInfos as $key => $sidebar) {
                    $parent_id = $this->parentId($sidebar);
                    $this->storeSidebar($sidebar, $key, $parent_id);

                }
              
                Cache::forget('sidebars' . auth()->user()->id);
                

            } else {
                $this->resetSidebarStore();
            }
            Cache::forget('sidebars' . auth()->user()->id);
        }
    }
    function resetSidebarStore()
    {
        if (auth()->user()->role_id == 2 || auth()->user()->role_id == 3) {
            $this->defaultSidebarStore();
            return true;
        }
        $user = auth()->user(); 

        $dashboardSections = ["dashboard", "menumanage.index"];
        $administration_sections = ["admin_section", "academics", "study_material", "lesson-plan", "bulk_print"];
        $student_sections = ["student_info", "fees", "fees_collection", "transport", "dormitory", "library", "homework", "behaviour_records"];
        $exam_sections = ["examination", "online_exam", "examplan"];
        $hr_sections = ["role_permission", "human_resource", "leave"];
        $account_sections = ["accounts", "inventory", "wallet"];
        $utilities_sections = ["chat", "style", "communicate"];
        $report_sections = ["students_report", "exam_report", "staff_report", "fees_report", "accounts_report"];
        $settings_sections = ["general_settings", "fees_settings", "exam_settings", "frontend_cms", "custom_field"];

        //permission section
        $permissionSections = include './resources/var/permission/permission_section_sidebar.php';
        $permissionSectionRoutes = [];
        foreach ($permissionSections as $item) {
            storePermissionData($item, auth()->user()->id);
            $permissionSectionRoutes[]=$item['route'];
        }
        // end
        $userPermissionSections = Permission::where('permission_section', 1)
            ->where('user_id', auth()->user()->id)
            ->where('is_saas', 0)->get(['id', 'name', 'type', 'route', 'parent_route', 'permission_section']);

        foreach ($userPermissionSections as $key => $userSection) {
            $parent = $userSection->parent_route != null
            ? Permission::where('route', $userSection->parent_route)
                ->when(auth()->user()->role_id == 2, function ($q) {
                    $q->where('is_student', 1);
                })->when(auth()->user()->role_id == 3, function ($q) {
                $q->where('is_parent', 1);
            })->where('is_menu', 1)
                ->value('id') : null;
            $this->storeSidebar($userSection, $key, $parent);
        }
        $permissionInfos = $this->permissions();

        foreach ($permissionInfos as $key => $sidebar) {
            $parent_id = $this->parentId($sidebar);
            
            if (in_array($sidebar->route, $dashboardSections)) {
                $parent_id = Permission::where('route', 'dashboard_section')
                ->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $administration_sections)) {
                $parent_id = Permission::where('route', 'administration_section')->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $student_sections)) {
                $parent_id = Permission::where('route', 'student_section')
                ->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $exam_sections)) {
                $parent_id = Permission::where('route', 'exam_section')->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $hr_sections)) {
                $parent_id = Permission::where('route', 'hr_section')->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $account_sections)) {
                $parent_id = Permission::where('route', 'accounts_section')->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $utilities_sections)) {
                $parent_id = Permission::where('route', 'utilities_section')->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $report_sections)) {
                $parent_id = Permission::where('route', 'report_section')
                ->where('user_id', $user->id)->value('id');
            }
            if (in_array($sidebar->route, $settings_sections)) {
                $parent_id = Permission::where('route', 'settings_section')->where('user_id', $user->id)->value('id');
            }
           
            if (!$sidebar->route && !$sidebar->parent_route) {
                continue;
            }
            $this->storeSidebar($sidebar, $key, $parent_id);
        }
        $ignorePermissionRoutes = ['reports', 'fees.fees-report', 'exam-setting'];
        $getIgnoreIds = Permission::whereIn('route', $ignorePermissionRoutes)->pluck('id')->toArray();
        Cache::forget('sidebars' . auth()->user()->id);
        Sidebar::whereIn('permission_id', $getIgnoreIds)->where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)->update(['active_status' => 0, 'ignore'=>1]);
        $this->deActiveForSaas();
       
    }
    function permissions()
    {
        $user = auth()->user();
        if ($user->role_id == 1) {
            $permissionInfos = Permission::where('is_admin', 1)->where('is_menu', 1)
                ->where('is_saas', 0)
                ->where(function($q) {
                    $q->where('user_id', auth()->user()->id)->orWhereNull('user_id');
                 })
                ->get(['id', 'name', 'type', 'route', 'parent_route', 'permission_section']);
        } else {
            $permissionInfos = Permission::where('is_menu', 1)
                ->orderBy('position', 'ASC')
                ->when(!in_array($user->role_id, [2,3]) , function($q){
                    $q->where('is_admin', 1);
                })->when($user->role_id == 4 , function($q){
                    $q->orWhere('is_teacher', 1);
                })->when($user->role_id == 2 , function($q){
                    $q->where('is_student', 1);
                })->when($user->role_id == 3 , function($q){
                    $q->where('is_parent', 1);
                })->where(function($q) {
                   $q->where('user_id', auth()->user()->id)->orWhereNull('user_id');
                })
                
                ->get(['id', 'name', 'type', 'route', 'parent_route', 'position', 'permission_section']);
        }
        return $permissionInfos;
    }
    function storeSidebar($sidebar, $key, $parent_id)
    {
        $user = auth()->user();

        Sidebar::updateOrCreate([
            'permission_id' => $sidebar->id,
            'user_id' => $user->id,
            'role_id'=>$user->role_id

        ], [
            'position' => $key + 1,
            'level' => $sidebar->type,
            'parent' => $parent_id,
        ]);
    }
    function modulePermissionSidebar()
    {
        $permissionIds = $this->permissions()->whereNotNull('route')->pluck('id')->toArray();
        $sidebarPermissionIds = Sidebar::where('user_id', auth()->user()->id)->where('role_id', auth()->user()->role_id)->pluck('permission_id')->toArray();
        $newPermissionIds = array_diff($permissionIds, $sidebarPermissionIds);
      
        if(empty($newPermissionIds)) return true;
        if (count($newPermissionIds) > 0) {          
            $permissionInfos = Permission::whereIn('id', $newPermissionIds)->get(['id', 'name', 'type', 'route', 'parent_route', 'position', 'permission_section']);
            foreach ($permissionInfos as $key => $sidebar) {
                $parent_id = $this->parentId($sidebar);               
                if (!$sidebar->route && !$sidebar->parent_route) {
                    continue;
                }
                $this->storeSidebar($sidebar, $key, $parent_id);
            }
            Cache::forget('sidebars' . auth()->user()->id);
        }
        

    }
    function parentId($sidebar)
    {
        $parent = $sidebar->parent_route != null
        ? Permission::where('route', $sidebar->parent_route)
            ->when(auth()->user()->role_id == 2, function ($q) {
                $q->where('is_student', 1);
            })->when(auth()->user()->role_id == 3, function ($q) {
            $q->where('is_parent', 1);
        })->where('is_menu', 1)
        // ->where('menu_status', 1)
        ->first(['id', 'permission_section']) : null;
        if ($parent && $parent->permission_section == 1 && $sidebar->permission_section) {
            $parent_id = null;
        } elseif($parent && $parent->permission_section == 1 && !$sidebar->permission_section){
            $parent_id = $parent->id;
        }elseif ($parent) {
            $parent_id = $parent->id;
        } else {
            $parent_id = 1;
        }
        if ($sidebar->permission_section) {
            $parent_id = null;
        }
        
        if (in_array($sidebar->route, $this->paidModuleRoutes())) {
            $parent_id = Permission::where('route', 'module_section')->where('user_id', auth()->user()->id)->value('id');
        }
       
        return $parent_id;
    }
    function allActivePaidModules()
    {
        $activeModules= [];
        $modules = InfixModuleManager::whereNotNull('purchase_code')->where('is_default', false)->where('name', '!=', 'OnlineExam')->pluck('name')->toArray();
        foreach($modules as $module) {
            if(moduleStatusCheck($module)) {
                $activeModules []= $module;
            }
        }
        return $activeModules;
    }
    function paidModuleRoutes()
    {
      return  Permission::whereIn('module', $this->allActivePaidModules())
      ->whereNotNull('route')
      ->whereNull('parent_route')
      ->when(auth()->user()->role_id == 1, function($q){
            $q->where('is_admin', 1);
        })->whereNotNull('module')->pluck('route')->toArray();
    }
    function deActiveForPgsql()
    {
        if(db_engine() !='mysql'){
            Permission::whereIn('route', ['backup-settings'])->update(['is_menu' => 0, 'menu_status'=>0, 'status'=>0]);
        }
    }
    function deActiveForSaas()
    {
        if(moduleStatusCheck('Saas')) {
            $list = ['update-system', 'utility', 'manage-adons', 'backup-settings', 'utility', 'language-list'];
            Permission::whereIn('route', $list)->update(['is_menu' => 0, 'menu_status'=>0, 'status'=>0, 'is_saas'=>1]);
            $saasSettingsRoutes = SaasSettings::where('saas_status', 1)->pluck('route')->toArray();
            if($saasSettingsRoutes) {
                Permission::whereIn('route', $saasSettingsRoutes)->update(['is_menu' => 1, 'menu_status'=>1, 'status'=>1, 'is_saas'=>0]);
            }
        }

    }
}
