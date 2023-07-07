<?php

use App\InfixModuleManager;
use App\SmSchool;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Modules\Saas\Entities\SmSubscriptionPayment;
use Nwidart\Modules\Facades\Module;


if (!function_exists('getVar')) {
    function getVar($list)
    {
        $file = resource_path('var/' . $list . '.json');

        return (File::exists($file)) ? json_decode(file_get_contents($file), true) : [];
    }
}

if (!function_exists('getModuleVar')) {
    function getModuleVar($module, $list)
    {
        if(!Module::find($module)){
            return [];
        }
        $file = module_path($module, 'Resources/var/' . $list . '.json');
        return (File::exists($file)) ? json_decode(file_get_contents($file), true) : [];
    }
}

function getPlanPermissionMenuModuleId(){
    return collect(planPermissions('menus', true))->values()->filter(function($v){
        return !is_null($v);
    })->toArray();
}

function planPermissions($hook = null, $module_id = false)
{

    $modules = Cache::rememberForever('paid_modules', function(){
        return InfixModuleManager::where('name', '!=', 'Saas')->where('is_default', '!=', 1)->pluck('name')->toArray();
    });
    $default_modules = Cache::rememberForever('default_modules', function(){
        return InfixModuleManager::where('name', '!=', 'Saas')->where('is_default', 1)->pluck('name')->toArray();
    });

    $menus = collect(getVar('limits'));

    foreach ($default_modules as $d) {
        $menus = $menus->merge(getModuleVar($d, 'limits'));
    }
    $menus = $menus->map(function($v) use($module_id){
        return gv($v, $module_id ? 'module_id' : 'lang');
    }) ->toArray();


    $final_modules = [];
    foreach ($modules as $key => $module) {
        $m = Module::find($module);
        if ($m && $m->isEnabled($module)) {
            $is_verify = Cache::rememberForever('module_'.$module, function() use($module){
                return InfixModuleManager::where('name', $module)->first();
            });
            if ($is_verify && $is_verify->purchase_code) {
                $final_modules[$module] = $module;
            }
        }
    }


    $data = ['modules' => $final_modules, 'menus' => $menus];

    if (!$hook) {
        return $data;
    }

    return gv($data, $hook);
}

function saasSettings($key): bool
{
    if (!moduleStatusCheck('Saas')) {
        return false;
    }

    $settings = Cache::rememberForever('saas_settings', function () {
        return \Modules\Saas\Entities\SaasSettings::all();
    });

    if ($s = $settings->where('lang_name', $key)->first()) {
        return (bool)$s->saas_status;
    }
    return false;
}

function isSubscriptionEnabled(): bool
{
    return saasSettings('manage_subscription');
}

function isMenuAllowToShow($menu) : bool
{
    if (!isSubscriptionEnabled()) {
        return true;
    }
    $school = getSchool();


    if (!$school || $school->id == 1) {
        return true;
    }



    if($school_module = getSchoolModule($school)){
        return in_array($menu, $school_module->menus);
    }

    if ($active = activePackage()) {
        return in_array($menu, $active->menus);
    }

    return false;
}

function getSchool(){

    if (app()->bound('school')) {
        $school = app('school');
    } elseif (auth()->check()) {
        $school = auth()->user()->school;
    } else{
        $school = SmSchool::first();
    }

    return $school;
}

function isModuleForSchool($module) : bool
{
    $module = strtolower($module);
    $school = getSchool();

    if ($school->id == 1) {
        return true;
    }

    if(!isSubscriptionEnabled()){
        return true;
    }

    if($school_module = getSchoolModule($school)){
        return in_array($module, strToLowerArray($school_module->modules));
    }

    if ($active = activePackage()) {
        return in_array($module, strToLowerArray($active->modules));
    }

    return false;
}

function strToLowerArray($array): array
{
    return  array_map(function($m){
        return strtolower($m);
    }, $array);
}

function getSchoolModule($school = null){
    if(!$school){
        $school = getSchool();
    }

    return Cache::rememberForever('school_modules' . $school->id,  function () use ($school) {
        return \App\Models\SchoolModule::where('school_id', $school->id)->first();
    });
}

function activePackage($school = null)
{
    if(!$school){
        $school = getSchool();
    }

    return Cache::remember('active_package' . $school->id, Carbon::now()->endOfDay()->addSecond(), function () use ($school) {

        $last_record = SmSubscriptionPayment::with('package')->orderBy('id', 'desc')->where(function ($q) {
            $q->where('approve_status', 'approved')->orWhere('payment_type', 'trial');
        })->where('school_id', $school->id)->first();


        if (!$last_record) {
            return false;
        }
        $now_time = date('Y-m-d');
        $purchase_packages =  SmSubscriptionPayment::with('package')->where('school_id', $school->id)->get();

        $last_active = SmSubscriptionPayment::with('package')->orderBy('id', 'desc')->where('approve_status', 'approved')
            ->where('start_date', '<=', $now_time)->where('end_date', '>=', $now_time)->where('school_id', $school->id)->first();

        if (!$purchase_packages->count()) {
            return false;
        }
        $package = null;

        foreach ($purchase_packages as $purchase_package) {
            if ($last_record && $last_record->buy_type == 'instantly' && $last_record->approve_status == 'approved') {
                if ($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $purchase_package->id == $last_record->id && $purchase_package->approve_status == 'approved') {
                    $package = $purchase_package;
                }

            } elseif ($last_record && $last_record->buy_type == 'buy_now' && $last_record->approve_status == 'approved') {
                if ($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $purchase_package->approve_status == 'approved' && $last_active->id == $purchase_package->id) {
                    $package = $purchase_package;
                }

            } elseif ($last_record && $last_record->payment_type == 'trial') {
                if ($now_time >= $purchase_package->start_date && $now_time <= $purchase_package->end_date && $purchase_package->payment_type == 'trial') {
                    $package = $purchase_package;
                }
            }
        }
        if ($package) {
            return $package->package;
        }
        return false;
    });
}

function getModuleAdminSection($module_id){
    $school_permissions = planPermissions('menus', true);
    $key = false;
    foreach($school_permissions as $permission => $id){
        if($id == $module_id){
            $key = $permission;
            break;
        }
    }

    return $key;
}