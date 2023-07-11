<?php

use App\SmClass;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use App\SmCurrency;
use App\Models\Theme;
use App\SmClassSection;
use App\SmAssignSubject;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\Lms\Entities\ModuleStatus;
use Modules\MenuManage\Entities\Sidebar;
use Modules\RolePermission\Entities\Permission;
use Modules\MenuManage\Entities\AlternativeModule;
use App\Traits\SidebarDataStore;
if (!function_exists('color_theme')) {
    function color_theme()
    {
        if (!auth()->check()) {
           return userColorThemeActive();
        } else if(auth()->user()) {  
            return userColorThemeActive(auth()->user()->id);
        }
        
    }

}

if (!function_exists('userColorThemeActive')) 
{
    function userColorThemeActive(int $user_id = null)  {
        $school_id = auth()->user()->school_id ?? 1;
        $cache_key = $user_id? ('active_theme_user_'.$user_id) : 'active_theme_school_'.$school_id;
        $active_theme =   Cache::rememberForever( $cache_key , function () use ($user_id) {
                    $theme = Theme::with('colors')->where('is_default', 1)
                            ->when($user_id, function ($q) use ($user_id) {
                                $q->where('created_by', $user_id);
                            })->first();
                            if ($user_id && !$theme) {
                                $theme = Theme::with('colors')->where('is_default', 1)->first();
                            }
                            if(!$theme) {
                                $theme = Theme::with('colors')->first();
                            }
                return $theme;
            });
            return $active_theme;
    }
}

if (!function_exists('userColorThemes')) 
{
    function userColorThemes(int $user_id = null)  {

        $themes = Theme::with('colors')
        ->when($user_id, function ($q) use ($user_id) {
            $q->where('created_by', $user_id);
        })->get();
        if ($user_id && !$themes) {
            $themes = Theme::with('colors')->where('is_system', 1)->get();
        }
        return $themes;
    }
}

if (!function_exists('activeStyle')) {
    function activeStyle()
    {
        if (session()->has('active_style')) {
            $active_style = session()->get('active_style');
            return $active_style;
        } else {
            $active_style = auth()->check() ? Theme::where('id', auth()->user()->style_id)->first() :
                Theme::where('school_id', 1)->where('is_default', 1)->first();
            if ($active_style == null) {
                $active_style = Theme::where('school_id', 1)->where('is_default', 1)->first();
            }
            
            session()->put('active_style', $active_style);
            return session()->get('active_style');
        }
    }
}

if(!function_exists('currency_format_list')) {
    function currency_format_list()
    {
        $symbol = generalSetting()->currency_symbol ?? '$';
        
        $code = generalSetting()->currency ?? 'USD';
        $formats = [
            [ 'name'=>'symbol_amount','format'=>'symbol(amount) =  '.$symbol.' 1'],
            ['name'=>'amount_symbol', 'format'=>'amount(symbol) = 1'.$symbol],
            ['name'=>'code_amount', 'format'=>'code(amount) = '.$code.' 1'],
            ['name'=>'amount_code', 'format'=>'amount(code) = 1 ' .$code],
        ];

        return $formats;
    }
}
if(!function_exists('currency_format')) {
    function currency_format($amount = null, string $format = null)
    {
        if(!$amount) return false; 
        $format = generalSetting()->currencyDetail;
        if(!$format) return $amount;

        $decimal = $format->decimal_digit ?? 0;
        $decimal_separator = $format->decimal_separator ?? "";
        $thousands_separator = $format->thousand_separator ?? "";
        $amount = number_format($amount, $decimal, $decimal_separator, $thousands_separator);
        $symbolCode = $format->currency_type == 'C' ? $format->code : $format->symbol;
       
        $symbolCodeSpace = $format->space ? 
                            ($format->currency_position == 'S' ? $symbolCode.' ' : ' '. $symbolCode) : $symbolCode;
        
        if ($format->currency_position == 'S') {
            return $symbolCodeSpace . $amount;
        } elseif($format->currency_position == 'P') {
            return $amount . $symbolCodeSpace;
        }
    }
}
if(!function_exists('classes')) {
    function classes(int $academic_year = null)
    {
        return  SmClass::withOutGlobalScopes()
        ->when($academic_year, function($q) use($academic_year){
            $q->where('academic_id', $academic_year);
        }, function($q){
            $q->where('academic_id', getAcademicId());
        })->where('school_id', auth()->user()->school_id)
        ->where('active_status', 1)->get();
    }
}
if(!function_exists('sections')) {
    function sections($class_id = null, $academic_year = null)
    {
        if(!$class_id) return null;
        return  SmClassSection::withOutGlobalScopes()->where('class_id', $class_id)
                            ->where('school_id', auth()->user()->school_id)
                            ->when($academic_year, function($q) use($academic_year){
                                $q->where('academic_id', $academic_year);
                            }, function($q){
                                $q->where('academic_id', getAcademicId());
                            })->get();

    }
}
if(!function_exists('subjects')) {
    function subjects(int $class_id, int $section_id, int $academic_year = null)
    {
         $subjects = SmAssignSubject::withOutGlobalScopes()
         ->where('class_id', $class_id)
         ->where('section_id', $section_id)
         ->where('school_id', auth()->user()->school_id)
         ->when($academic_year, function($q) use($academic_year){
            $q->where('academic_id', $academic_year);
        }, function($q){
            $q->where('academic_id', getAcademicId());
        })->select('class_id', 'section_id', 'subject_id')->distinct(['class_id', 'section_id', 'subject_id'])->get(); 
        
        return $subjects;

    }
}
if(!function_exists('students')) {
    function students(int $class_id, int $section_id = null, int $academic_year = null)
    {
         $student_ids = StudentRecord::where('class_id', $class_id)
         ->when($section_id, function($q) use($section_id){
            $q->where('section_id', $section_id);
         })->when('academic_year', function($q) use($academic_year) {
            $q->where('academic_id', $academic_year);
         })->where('school_id', auth()->user()->school_id)->pluck('student_id')->unique()->toArray();

         $students = SmStudent::withOutGlobalScopes()->whereIn('id', $student_ids)->get();
        
        return $students;

    }
}
if(!function_exists('classSubjects')) {
    function classSubjects($class_id = null) {
        $subjects = SmAssignSubject::query();
        if (teacherAccess()) {
            $subjects->where('teacher_id', auth()->user()->staff->id) ;
        }
        if ($class_id !="all_class") {
            $subjects->where('class_id', '=', $class_id);
        } else {
            $subjects->distinct('class_id');
        }
        $subjectIds = $subjects->distinct('subject_id')->get()->pluck(['subject_id'])->toArray();        

        return SmSubject::whereIn('id', $subjectIds)->get(['id','subject_name']);
    }
}
if(!function_exists('subjectSections')) {
    function subjectSections($class_id = null, $subject_id =null) {
        
        if(!$class_id || !$subject_id) return null;

        $sectionIds = SmAssignSubject::where('class_id', $class_id)
        ->where('subject_id', '=', $subject_id)                         
        ->where('school_id', auth()->user()->school_id)
        ->when(teacherAccess(), function($q) {
            $q->where('teacher_id',auth()->user()->staff->id);
        })
        ->distinct(['class_id','section_id'])
        ->pluck('section_id')
        ->toArray();
        return SmSection::whereIn('id',$sectionIds)->get(['id','section_name']);

    }
}

if (!function_exists('routeIsExist')) {
    function routeIsExist($route, $children_id = null)
    {
        if($children_id) {
            if (Route::has($route)) {
                return true;
            }
        }
        if (Route::has($route)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('validRouteUrl')) {
    function validRouteUrl($route, $children_id = null)
    {
        $url = null;
        try {
            if (routeIsExist($route, $children_id)) {
                if($children_id){
                    $url = \route($route, $children_id);
                }else{
                    $url = \route($route);
                }
                
            }
        } catch (\Exception $e) {
        }
        return $url;
    }
}

if (!function_exists('routeIs')) {
    function routeIs($route)
    {
        if (Route::currentRouteName() == $route) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('subModuleRoute')) {
    function subModuleRoute($menu, $routes = [])
    {
        if(@$menu->permissionInfo->route){
            $routes[] = $menu->permissionInfo->route;
        }
        if($menu->subModule->count()){
            foreach($menu->subModule as $child){
                $routes = subModuleRoute($child, $routes);
            }
            return $routes;
        }
        return $routes;
    }
}
if(!function_exists('deActivePermissions')) {
    function deActivePermissions()
    {
        $alternativeDeActiveModuleInfo = AlternativeModule::where('status', 0)->pluck('module_name')->toArray();
        return Permission::whereIn('module', $alternativeDeActiveModuleInfo)->pluck('id')->toArray();
       
    }
}
if(!function_exists('sidebar_menus')) {
    function sidebar_menus()
    {
        $user = auth()->user();
          return $sidebars = Cache::rememberForever('sidebars'.$user->id, function() use($user){
            return Sidebar::with(['subModule', 'permissionInfo'])
             ->whereNull('parent')
             ->whereHas('permissionInfo', function($q) use ($user){
                 $q->where('menu_status', 1)
                 ->when($user->role_id == 2, function($q){
                     $q->where('is_student', 1);
                 })->when($user->role_id == 3, function($q){
                     $q->where('is_parent', 1);
                 })->when(!in_array($user->role_id, [2,3]) , function($q){
                     $q->where('is_admin', 1);
                 });
             })           
             ->where('user_id', $user->id)->where('role_id', $user->role_id)->where('active_status', 1)
             ->orderBy('position', 'ASC')->get();
            }); 
       
    }
}

if(!function_exists('storePermissionData')) {
    function storePermissionData($permission, $user_id = null, $school_id = null)
    {
        Permission::updateOrCreate([
            'module' => $permission['module'],
            'sidebar_menu'=>$permission['sidebar_menu'],
            'lang_name' => $permission['lang_name'],
            'icon' => $permission['icon'],
            'svg' => $permission['svg'], 
            'route' => $permission['route'],
            'parent_route' => $permission['parent_route'],              
            'is_admin'=>$permission['is_admin'],
            'is_teacher'=>$permission['is_teacher'], 
            'is_student'=>$permission['is_student'],
            'is_parent'=>$permission['is_parent'],   
                        
            'is_saas'=>$permission['is_saas'],             
            'is_menu'=>$permission['is_menu'],
            'status'=>$permission['status'], 
            'menu_status'=>$permission['menu_status'],   
            'relate_to_child'=>$permission['relate_to_child'],   
            'alternate_module'=>$permission['alternate_module'],   
            'permission_section'=>$permission['permission_section'],
            'type'=>$permission['type'],   
            'user_id'=> $permission['permission_section']==1 && $user_id ? $user_id: null,   
            'old_id'=>$permission['old_id'],     
            'school_id'=>$school_id ?? 1,     
          ],
        [
            'name' => $permission['name'],
            'position'=>$permission['position'],
        
        ]); 
        if(isset($permission['child'])) {
            foreach($permission['child'] as $child) {              
                storePermissionData($child);
            }
        }
    }
}

if(!function_exists('sidebarPermission')) {
    function sidebarPermission($permission)
    {
        if(!$permission) return false;
        $user = auth()->user();
        if($permission->permission_section == 1) return true;
       
        if($permission->module  && $permission->module !='fees_collection') {
            if(moduleStatusCheck($permission->module)) {
                $access = true;
                if($permission->module == 'Saas') {
                    $saasNotAdministrator = ['administrator-notice','school/ticket-view', 'subscription/history'];
                    $subscriptions = ['subscription/package-list', 'subscription/history'];
                    $access = true;
                    if($permission->route == 'saas.custom-domain') {
                        $access = config('app.allow_custom_domain') ? true : false;
                    }
                    if($permission->route == 'school-general-settings') {
                        $access = isSubscriptionEnabled() && $user->is_administrator == 'yes' ? true : false;
                    }
                    if(in_array($permission->route, $saasNotAdministrator)) {
                        $access = $user->is_administrator != 'yes' ? true : false;
                    }
                    if(in_array($permission->route, $subscriptions)) {
                        $access = isSubscriptionEnabled() && $user->is_administrator != 'yes' ? true : false;
                    }
                }
            }else {
                $access = false;
            }
           
        }elseif(!$permission->module) {
            $access = true;
        }
        
        if($permission->module =='fees_collection') {
            $routeList = ['fees_group', 'fees_type', 'search_fees_due','fees_forward'];
            if((int)generalSetting()->fees_status != 1 && directFees()) {               
                $access = true;              
                if(in_array($permission->route,$routeList)) {
                    $access = false;
                } 
            }elseif((int)generalSetting()->fees_status != 1 && directFees()==false) {
                $access = true;             
                if(in_array($permission->route,$routeList)) {
                    $access = true;
                }                
            }
            else{
                $access = false;
            } 
        
            
        }
        if($permission->module =='Fees'){
            if((int)generalSetting()->fees_status == 1) {
                $access = true;
                if(moduleStatusCheck('Saas') && $permission->sidebar_menu ) {
                      $access = isMenuAllowToShow($permission->sidebar_menu);
                }
            }else{
                $access = false;
            }
           
        }      
        if($permission->alternate_module == 'OnlineExam') {
            if(moduleStatusCheck('OnlineExam')) {              
                if($permission->route !='online_exam' && $permission->alternate_module == 'OnlineExam') {
                    $access = false;                  
                } 
            }            
        }
        if(moduleStatusCheck('Saas') && $permission->sidebar_menu ) {
            if(!$permission->module || $permission->alternate_module == 'OnlineExam') {
                $access = isMenuAllowToShow($permission->sidebar_menu);
            }
           
        }
      
        if(userPermission($permission->route)==true && $access==true) {
            return true;
        }
        return false;
    }
}

if(!function_exists('ignorePermissionRoutes')) {
    function ignorePermissionRoutes()
    {
        return ['reports', 'system_settings', 'front_settings', 'fees.fees-report', 'exam-setting'];
    }
}
if(!function_exists('ignorePermissionIds')) {
    function ignorePermissionIds()
    {
       return Permission::whereIn('route', ignorePermissionRoutes())->pluck('id')->toArray();
    }
}

function hasDueFees($children_id){
    $student_ids = Cache::get('have_due_fees_'.auth()->user()->id);
  
    return $student_ids && in_array($children_id, $student_ids) ;
        
    
}