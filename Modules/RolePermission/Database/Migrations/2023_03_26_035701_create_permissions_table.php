<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();           
            $table->string('module')->nullable();
            $table->string('sidebar_menu')->nullable();
            $table->integer('old_id')->nullable();
            $table->integer('section_id')->nullable()->default(1);
            $table->integer('parent_id')->nullable()->default(0);
            $table->string('name')->nullable();           
            $table->string('route')->nullable();
            $table->string('parent_route')->nullable();
            $table->integer('type')->nullable()->comment('1 = menu, 2 = submenu, 3 = action');          
            $table->string('lang_name')->nullable();
            $table->text('icon')->nullable();
            $table->text('svg')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('menu_status')->default(1);
            $table->integer('position')->default(1);
            $table->tinyInteger('is_saas')->default(0);
            $table->tinyInteger('relate_to_child')->nullable()->default(0);
            $table->tinyInteger('is_menu')->nullable();
            $table->tinyInteger('is_admin')->nullable()->default(0);
            $table->tinyInteger('is_teacher')->nullable()->default(0);
            $table->tinyInteger('is_student')->nullable()->default(0);
            $table->tinyInteger('is_parent')->nullable()->default(0);
            $table->integer('created_by')->nullable()->default(1)->unsigned(); 
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->tinyInteger('permission_section')->nullable();
            $table->string('alternate_module')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('school_id')->nullable()->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });
     
        // this file will be first
        $adminPermissionList = include('./resources/var/permission/without_student_parent_positions.php');
         foreach($adminPermissionList as $item){           
            storePermissionData($item);
         }
        // first file end
        $studentPermissionList = include('./resources/var/permission/student_permissions.php');
        foreach($studentPermissionList as $item){           
            storePermissionData($item);
        }
        $parentPermissionList = include('./resources/var/permission/parent_permissions.php');
        foreach($parentPermissionList as $item){           
            storePermissionData($item);
        }
        //this file will be last
        $permissionSections = include('./resources/var/permission/permission_section_sidebar.php');
         foreach($permissionSections as $item){           
            storePermissionData($item , 1);
         }
        //  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
