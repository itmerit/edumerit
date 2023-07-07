<?php

use App\InfixModuleManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $feesCarryForward = array(
            'fees-carry-forward-view' => array(
                'module' => "Fees",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward',
                'lang_name' => 'fees.fees_carry_forward',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-view',
                'parent_route' => 'fees',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-search',
                        'parent_route' => 'fees-carry-forward-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                    'fees-carry-forward-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-store',
                        'parent_route' => 'fees-carry-forward-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-settings-view' => array(
                'module' => "Fees",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Settings',
                'lang_name' => 'fees.fees_carry_forward_settings',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-settings-view',
                'parent_route' => 'fees',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-settings-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-settings-store',
                        'parent_route' => 'fees-carry-forward-settings-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-log-view' => array(
                'module' => "Fees",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Log',
                'lang_name' => 'fees.fees_carry_forward_log',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-log-view',
                'parent_route' => 'fees',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-log-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-log-search',
                        'parent_route' => 'fees-carry-forward-log-view',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-view-fees-collection' => array(
                'module' => "fees_collection",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward',
                'lang_name' => 'fees.fees_carry_forward',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-view-fees-collection',
                'parent_route' => 'fees_collection',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-search',
                        'parent_route' => 'fees-carry-forward-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                    'fees-carry-forward-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-store',
                        'parent_route' => 'fees-carry-forward-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-settings-view-fees-collection' => array(
                'module' => "fees_collection",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Settings',
                'lang_name' => 'fees.fees_carry_forward_settings',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-settings-view-fees-collection',
                'parent_route' => 'fees_collection',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-settings-store' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Store',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-settings-store',
                        'parent_route' => 'fees-carry-forward-settings-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),
            'fees-carry-forward-log-view-fees-collection' => array(
                'module' => "fees_collection",
                'sidebar_menu' => 'fees',
                'name' => 'Fees Carry Forward Log',
                'lang_name' => 'fees.fees_carry_forward_log',
                'icon' => null,
                'svg' => null,
                'route' => 'fees-carry-forward-log-view-fees-collection',
                'parent_route' => 'fees_collection',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 3,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => 432,
                'child' => array(
                    'fees-carry-forward-log-search' => array(
                        'module' => null,
                        'sidebar_menu' => null,
                        'name' => 'Search',
                        'lang_name' => null,
                        'icon' => null,
                        'svg' => null,
                        'route' => 'fees-carry-forward-log-search',
                        'parent_route' => 'fees-carry-forward-log-view-fees-collection',
                        'is_admin' => 1,
                        'is_teacher' => 0,
                        'is_student' => 0,
                        'is_parent' => 0,
                        'position' => 434,
                        'is_saas' => 0,
                        'is_menu' => 0,
                        'status' => 1,
                        'menu_status' => 1,
                        'relate_to_child' => 0,
                        'alternate_module' => null,
                        'permission_section' => 0,
                        'user_id' => null,
                        'type' => 3,
                        'old_id' => 433,
                    ),
                ),
            ),

            'two_factor_auth_setting' => array(
                'module' => 'TwoFactorAuth',
                'sidebar_menu' => 'system_settings',
                'name' => 'Two Factor Setting',
                'lang_name' => 'auth.two_factor_setting',
                'icon' => null,
                'svg' => null,
                'route' => 'two_factor_auth_setting',
                'parent_route' => 'general_settings',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 16,
                'is_saas' => 0,
                'is_menu' => 1,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 2,
                'old_id' => null,
            ),
        );
        foreach($feesCarryForward as $carry){
            storePermissionData($carry);
        }

        Permission::where('route', 'fees_forward')->delete();
        
        Schema::table('sm_fees_carry_forwards', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_fees_carry_forwards', 'balance_type')){
                $table->string('balance_type')->nullable();
            }
            if(!Schema::hasColumn('sm_fees_carry_forwards', 'due_date')){
                $table->timestamp('due_date')->nullable();
            }
        });

        $dataPath = 'Modules/TwoFactorAuth/TwoFactorAuth.json';
        $name = 'TwoFactorAuth';
        $strJsonFileContents = file_get_contents($dataPath);
        $array = json_decode($strJsonFileContents, true);

        $version = $array[$name]['versions'][0];
        $url = $array[$name]['url'][0];
        $notes = $array[$name]['notes'][0];

        $s = InfixModuleManager::where('name', $name)->first();
        if(!$s){
            $s = new InfixModuleManager();
        }
        $s->name = $name;
        $s->email = 'support@spondonit.com';
        $s->notes = $notes;
        $s->version = $version;
        $s->update_url = $url;
        $s->is_default = 1;
        $s->purchase_code = time();
        $s->installed_domain = url('/');
        $s->activated_date = date('Y-m-d');
        $s->save();

        $controller = new \App\Http\Controllers\Admin\SystemSettings\SmAddOnsController();
        $controller->FreemoduleAddOnsEnable($name);
    }

    public function down(): void
    {
        //
    }
};
