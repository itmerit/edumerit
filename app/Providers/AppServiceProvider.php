<?php 
namespace App\Providers;

use App\SmParent;
use App\SmNotification;
use App\SmGeneralSettings;
use App\Models\CustomMixin;
use Spatie\Valuestore\Valuestore;
use App\Models\MaintenanceSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Modules\MenuManage\Entities\Sidebar;
use Modules\MenuManage\Entities\SidebarNew;
use Modules\RolePermission\Entities\InfixRole;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\AssignPermission;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        
        try{
            Builder::defaultStringLength(191);
    
            view()->composer('backEnd.partials.parents_sidebar', function ($view) {
                $data =[
                    'childrens' => SmParent::myChildrens(),
                ];
                $view->with($data);
       

            });


            view()->composer('backEnd.partials.menu', function ($view) {
                $notifications = DB::table('notifications')->where('notifiable_id', auth()->id())
                    ->where('read_at', null)
                    ->get();

                foreach ($notifications as $notification){
                    $notification->data = json_decode($notification->data);
                }

                $view->with(['notifications_for_chat' => $notifications]);
            });

            view()->composer(['backEnd.master', 'backEnd.partials.menu'], function ($view) {
                    $data =[
                        'notifications' => SmNotification::notifications(),
                    ];
                    $view->with($data);
            });

            if(Storage::exists('.app_installed') && Storage::get('.app_installed')){
                config(['broadcasting.default' => saasEnv('chatting_method')]);
                config(['broadcasting.connections.pusher.key' => saasEnv('pusher_app_key')]);
                config(['broadcasting.connections.pusher.secret' => saasEnv('pusher_app_secret')]);
                config(['broadcasting.connections.pusher.app_id' => saasEnv('pusher_app_id')]);
                config(['broadcasting.connections.pusher.options.cluster' => saasEnv('pusher_app_cluster')]);
            }

        } catch(\Exception $e){
            return false;
        }
    }

    public function register()
    {
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->singleton('dashboard_bg', function () {
            $dashboard_background = DB::table('sm_background_settings')->where('school_id', app('school')->id)->where([['is_default', 1], ['title', 'Dashboard Background']])->first();
            return $dashboard_background;
        });

        $this->app->singleton('school_info', function () {
            return SmGeneralSettings::where('school_id', app('school')->id)->first();
        });

        $this->app->singleton('school_menu_permissions', function () {
            $module_ids = getPlanPermissionMenuModuleId();
            return InfixModuleInfo::where('parent_id', 0)->with(['children' ])->whereIn('id', $module_ids)->get();
        });

        $this->app->singleton('permission', function () {
            
            $infixRole = InfixRole::find(Auth::user()->role_id);
            $permissionIds = AssignPermission::where('role_id', Auth::user()->role_id)
            ->when($infixRole->is_saas == 0, function($q) {
                $q->where('school_id', Auth::user()->school_id);
            })->pluck('permission_id')->toArray();
            
            $permissions = Permission::whereIn('id', $permissionIds)->pluck('route')->toArray();  

        });

        $this->app->singleton('saasSettings', function () {
            return \Modules\Saas\Entities\SaasSettings::where('saas_status', 0)->pluck('infix_module_id')->toArray();
        });


        $this->app->singleton('sidebar_news', function () {
            return  Sidebar::get();
        });




    }
}