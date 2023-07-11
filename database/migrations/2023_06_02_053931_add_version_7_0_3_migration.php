<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;

class AddVersion703Migration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $applyLeavePermissions = array(
            'apply-leave-store' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Add',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'apply-leave-store',
                'parent_route' => 'apply-leave',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 191,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 3,
                'old_id' => 190,
            ),
            'apply-leave-edit' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Edit',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'apply-leave-edit',
                'parent_route' => 'apply-leave',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 191,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 3,
                'old_id' => 190,
            ),
            'apply-leave-delete' => array(
                'module' => null,
                'sidebar_menu' => null,
                'name' => 'Delete',
                'lang_name' => null,
                'icon' => null,
                'svg' => null,
                'route' => 'apply-leave-delete',
                'parent_route' => 'apply-leave',
                'is_admin' => 1,
                'is_teacher' => 0,
                'is_student' => 0,
                'is_parent' => 0,
                'position' => 191,
                'is_saas' => 0,
                'is_menu' => 0,
                'status' => 1,
                'menu_status' => 1,
                'relate_to_child' => 0,
                'alternate_module' => null,
                'permission_section' => 0,
                'user_id' => null,
                'type' => 3,
                'old_id' => 190,
            ),
        );
        foreach($applyLeavePermissions as $item){
            storePermissionData($item);
        }
        Permission::where('sidebar_menu', 'fees_collection')->update([
            'sidebar_menu'=>'fees'
        ]);
        Permission::where('parent_route', 'fees_collection')->orWhere('parent_route', 'fees')->update([
            'sidebar_menu'=>'fees'
        ]);

        Permission::where('sidebar_menu', 'sidebar_manager')->update(['sidebar_menu'=>null]);
        Permission::where('sidebar_menu', 'dashboard')->update(['sidebar_menu'=>null]);

        Permission::where('parent_route', 'admin_section')->update(['sidebar_menu'=>'admin_section']);
        Permission::where('parent_route', 'student_info')->update(['sidebar_menu'=>'student_info']);
        Permission::where('parent_route', 'academics')->update(['sidebar_menu'=>'academics']);
        Permission::where('parent_route', 'study_material')->update(['sidebar_menu'=>'study_material']);
        Permission::where('parent_route', 'human_resource')->update(['sidebar_menu'=>'human_resource']);
        Permission::where('parent_route', 'leave')->update(['sidebar_menu'=>'leave']);
        Permission::where('parent_route', 'custom_field')->update(['sidebar_menu'=>'custom_field']);
        Permission::where('parent_route', 'examination')->update(['sidebar_menu'=>'examination']);
        Permission::where('parent_route', 'online_exam')->update(['sidebar_menu'=>'online_exam']);
        Permission::where('parent_route', 'homework')->update(['sidebar_menu'=>'homework']);
        Permission::where('sidebar_menu', 'home_work')->update(['sidebar_menu'=>'homework']);
        Permission::where('parent_route', 'communicate')->update(['sidebar_menu'=>'communicate']);
        Permission::where('parent_route', 'library')->update(['sidebar_menu'=>'library']);
        Permission::where('parent_route', 'inventory')->update(['sidebar_menu'=>'inventory']);
        Permission::where('parent_route', 'transport')->update(['sidebar_menu'=>'transport']);
        Permission::where('parent_route', 'dormitory')->update(['sidebar_menu'=>'dormitory']);
        Permission::where('parent_route', 'general_settings')->update(['sidebar_menu'=>'system_settings']);
        Permission::where('parent_route', 'style')->update(['sidebar_menu'=>'style']);

        Permission::where('parent_route', 'frontend_cms')->orWhere('route','frontend_cms')
            ->update(['sidebar_menu'=>'front_setting']);
        Permission::where('sidebar_menu', 'front_settings')->update(['sidebar_menu'=>'front_setting']);

        Permission::where('parent_route', 'fees_settings')->orWhere('route','fees_settings')
            ->update(['sidebar_menu'=>'fees_settings']);
        Permission::where('parent_route', 'exam_settings')->orWhere('route','exam_settings')
            ->update(['sidebar_menu'=>'exam_settings']);
        Permission::where('parent_route', 'students_report')->orWhere('route','students_report')
            ->update(['sidebar_menu'=>'students_report']);
        Permission::where('parent_route', 'exam_report')->orWhere('route','exam_report')
            ->update(['sidebar_menu'=>'exam_report']);
        Permission::where('parent_route', 'staff_report')->orWhere('route','staff_report')
            ->update(['sidebar_menu'=>'staff_report']);
        Permission::where('parent_route', 'fees_report')->orWhere('route','fees_report')
            ->update(['sidebar_menu'=>'fees_report']);
        Permission::where('parent_route', 'accounts_report')->orWhere('route','accounts_report')->update(['sidebar_menu'=>'accounts_report']);

        Permission::where('parent_route', 'lesson-plan')->update(['sidebar_menu'=>'lesson_plan']);
        Permission::where('parent_route', 'wallet')->update(['sidebar_menu'=>'wallet']);
        Permission::where('parent_route', 'examplan')->update(['sidebar_menu'=>'examplan']);
        Permission::where('parent_route', 'role_permission')->update(['sidebar_menu'=>'role_permission']);

        Permission::whereIn('route', ['fees_settings','exam_settings','students_report','exam_report','staff_report','fees_report',
            'accounts_report'])->whereNull('sidebar_menu')->delete();

        Schema::table('sm_temporary_meritlists', function (Blueprint $table) {
            if (!Schema::hasColumn('sm_temporary_meritlists', 'roll_no')) {
                $table->integer('roll_no')->nullable();
            }
        });

    }

    public function down()
    {
        //
    }
}
