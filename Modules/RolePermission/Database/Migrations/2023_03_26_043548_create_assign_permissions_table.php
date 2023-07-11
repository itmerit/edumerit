<?php

use Illuminate\Support\Facades\Schema;
use Modules\MenuManage\Entities\Sidebar;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\AssignPermission;

class CreateAssignPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer('permission_id')->nullable();
            $table->integer('role_id')->nullable()->unsigned();
            $table->boolean('status')->default(1);
            $table->boolean('menu_status')->default(1);
            $table->text('saas_schools')->nullable();
            $table->integer('created_by')->default(1)->unsigned();
            $table->integer('updated_by')->default(1)->unsigned();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });
        $admins = 
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 82, 83, 84, 85, 86, 533, 534, 535, 536, 87, 88, 89, 90, 91, 92, 93, 94, 95, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 214, 215, 216, 217, 218, 219, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 537, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 323, 324, 325, 326, 327, 328, 329, 330, 331, 332, 333, 334, 335, 336, 337, 338, 339, 340, 341, 342, 343, 344, 345, 346, 347, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 376, 377, 378, 379, 380, 381, 382, 383, 384, 385, 386, 387, 388, 389, 390, 391, 392, 394, 395, 396, 397, 538, 539, 540, 485, 486, 487, 488, 489, 490, 491,553,577,800,801,802,803,804,805,806,807,808,809,810,811,812,813,814,815,900,901,902,903,904];

        $adminPermissionInfos = Permission::whereIn('old_id', $admins)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($adminPermissionInfos as $key => $permission) 
        {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;           
            $assignPermission->role_id = 5;
            $assignPermission->save();
        }

        // for teacher
        $teachers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 82, 83, 84, 85, 86, 533, 534, 535, 536, 87, 88, 89, 90, 91, 92, 93, 94, 95, 100, 101, 102, 103, 104, 105, 106, 107, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180, 181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 214, 215, 216, 217, 218, 219, 225, 226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240, 241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251, 252, 253, 254, 255, 256, 257, 258, 259, 260, 261, 262, 263, 264, 265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 537, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 348, 349, 350, 351, 352, 353, 354, 355, 356, 357, 358, 359, 360, 361, 362, 363, 364, 365, 366, 367, 368, 369, 370, 371, 372, 373, 374, 375, 277, 278, 279, 280, 281, 282, 283, 284, 285,553,800,801,802,803,804,805,806,807,808,809,833,834,900,901,902,903,904];

        $teachersInfos = Permission::whereIn('old_id', $teachers)->where('is_admin', 1)->orWhere('is_teacher', 1)->get(['id', 'name']);
        foreach ($teachersInfos as $key => $permission) {           
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;           
            $assignPermission->role_id = 4;
            $assignPermission->save();
        }
       
        // for receptionists
        $receptionists = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 376, 377, 378, 379, 380,553, 900,901,902,903,904];

        $receptionistInfo = Permission::whereIn('old_id', $receptionists)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($receptionistInfo as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;     
            $assignPermission->role_id = 7;
            $assignPermission->save();
        }

        // for librarians
        $librarians = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 61, 64, 65, 66, 67, 83, 84, 85, 86, 160, 161, 162, 163, 164, 188, 193, 194, 195, 298, 299, 300, 301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 312, 313, 314, 376, 377, 378, 379, 380,553,900,901,902,903,904];

        $librariansInfo = Permission::whereIn('old_id', $librarians)->where('is_admin', 1)->get(['id', 'name']);

        foreach ($librariansInfo as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 8;
            $assignPermission->save();
        }

        // for drivers
        $drivers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 188, 193, 194, 19,553,900,901,902,903,904];
        $driverInfos = Permission::whereIn('old_id', $drivers)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($driverInfos as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 9;
            $assignPermission->save();
        }

        // for accountants
        $accountants = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 64, 65, 66, 67, 68, 69, 70, 83, 84, 85, 86, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135, 160, 161, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 188, 193, 194, 195, 376, 377, 378, 379, 380, 381, 382, 383,553,900,901,902,903,904];

        $accountantsInfos = Permission::whereIn('old_id', $accountants)->where('is_admin', 1)->get(['id', 'name']);
        foreach ($accountantsInfos as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 6;
            $assignPermission->save();
        }

        // student
        for ($j = 1; $j <= 55; $j++) {
            $permission = new AssignPermission();
            $permission->permission_id = @Permission::where('old_id', $j)->where('is_student', 1)->value('id');
            $permission->role_id = 2;
            $permission->save();
        }    

        //  Student for Chat Module

        $students = [900,901,902,903,904, 800,810,815,1124,1125,1126,1156];
        $chatPermissionInfoStudents = Permission::whereIn('old_id', $students)->where('is_student', 1)->get(['id', 'name']);
        foreach ($chatPermissionInfoStudents as $key => $permission) {
            $assignPermission = new AssignPermission();
            $assignPermission->permission_id = $permission->id;
            $assignPermission->role_id = 2;
            $assignPermission->save();
        }

      

        // parent
        for ($j = 56; $j <= 99; $j++) {
            $permission = new AssignPermission();
            $permission->permission_id = @Permission::where('old_id', $j)->where('is_parent', 1)->value('id');         
            $permission->role_id = 3;
            $permission->save();
        }

        // Parent for Online Exam &  Chat Module

        $parentInfos = [910,911,912,913,914,2016,2017,2018,1127,1128,1129,1157];
        $parentPermissionInfos = Permission::whereIn('old_id', $parentInfos)->where('is_parent', 1)->get(['id', 'name']);
        foreach ($parentPermissionInfos as $key => $permission) {
            $permission = new AssignPermission();
            $permission->permission_id = $permission->id;           
            $permission->role_id = 3;
            $permission->save();
        }


        //  for sidebar

        // drop migration file and column old_id
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assign_permissions');
    }
}
