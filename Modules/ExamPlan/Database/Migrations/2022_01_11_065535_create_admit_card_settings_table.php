<?php

use App\Models\SchoolModule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\ExamPlan\Entities\AdmitCardSetting;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class CreateAdmitCardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admit_card_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('student_photo')->nullable();
            $table->boolean('student_name')->nullable();
            $table->boolean('admission_no')->nullable();
            $table->boolean('class_section')->nullable();
            $table->boolean('exam_name')->nullable();
            $table->boolean('academic_year')->nullable();
            $table->boolean('principal_signature')->nullable();
            $table->boolean('class_teacher_signature')->nullable();
            $table->boolean('gaurdian_name')->nullable();
            $table->boolean('school_address')->nullable();
            $table->boolean('student_download')->nullable();
            $table->boolean('parent_download')->nullable();
            $table->boolean('student_notification')->nullable();
            $table->boolean('parent_notification')->nullable();
            $table->string('principal_signature_photo')->nullable();
            $table->string('teacher_signature_photo')->nullable();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->integer('admit_layout')->default(1);
            $table->string('admit_sub_title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
        $column ="ExamPlan";
        if (!Schema::hasColumn('sm_general_settings', $column)) {
            Schema::table('sm_general_settings', function (Blueprint $table) use ($column) {
                $table->integer($column)->default(0);
            });
        }

        try {
            $setting = AdmitCardSetting::first();
            if(!$setting){
                $setting = new AdmitCardSetting();
                $setting->student_photo = 1; 
                $setting->student_name = 1;
                $setting->admission_no = 1;
                $setting->class_section = 1;
                $setting->exam_name = 1;
                $setting->academic_year = 1;
                $setting->principal_signature = 1;
                $setting->class_teacher_signature = 1;
                $setting->school_address =1;
                $setting->gaurdian_name =1;
                $setting->student_download =1;
                $setting->parent_download =1;
                $setting->student_notification =1;
                $setting->parent_notification =1;
                $setting->description = <<<'EOD'
                <p class="fs-18 fw-bold text-black text-center text-underline">Rules to be followed by the candidates</p>
                    <div class="h-10"></div>
                    <ul>
                        <li class="fs-14 fw-meidum text-black"><span></span>Admit card must be collected before two days of the exam.</li>
                        <li class="fs-14 fw-meidum text-black"><span></span>Candidates should take their seats 15 minutes before starting of the exam.</li>
                        <li class="fs-14 fw-meidum text-black"><span></span>Candidates can use their own pen, pencil and scientific calculator in the exam hall.</li>
                        <li class="fs-14 fw-meidum text-black"><span></span>The examination will be held on the specified date and time as per the pre-announced examination’s routine.</li>
                        <li class="fs-14 fw-meidum text-black"><span></span>No student will be allowed to enter the examination hall with any paper, books, mobile phone, except without admit card.</li>
                    </ul>
                EOD;
                $setting->save();
            }           
    
            $leadInfixModuleIds = [
                [3100, 50, 0, '1', 0,'ExamPlan','examplan','examplan','',1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22'],
    
                [3101, 50, 3100, '2', 0,'Admit Card','','','', 1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22'],
                [3102, 50, 3101, '3', 0,'Setting','admit','admit','', 1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22'],
                [3103, 50, 3101, '3', 0,'Generate','','','', 1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22'],
                [3104, 50, 3101, '3', 0,'Save','','','', 1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22'],
    
                [3105, 50, 3100, '2', 0,'Seat Plan','seatplan','seatplan','',1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22'],
                [3106, 50, 3105, '3', 0,'Seat Plan Setting','','','', 1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22'],
                [3107, 50, 3105, '3', 0,'Generate','','','', 1, 1, 1, 1, '2021-10-18 02:21:21', '2021-10-18 04:24:22']
            ];
            foreach ($leadInfixModuleIds as $key=>$data) {                   
                $check_exit=InfixModuleInfo::find($data[0]);
                if($check_exit){
                    continue;
                }
                $examPlan = new InfixModuleInfo;
                $examPlan->id = $data[0];
                $examPlan->module_id = $data[1];
                $examPlan->parent_id = $data[2];
                $examPlan->type = $data[3];
                $examPlan->is_saas = $data[4];
                $examPlan->name = $data[5];
                $examPlan->route = $data[6];
                $examPlan->lang_name = $data[7];
                $examPlan->icon_class = $data[8];
                $examPlan->active_status = $data[9];
                $examPlan->created_by = $data[10];
                $examPlan->updated_by = $data[11];
                $examPlan->school_id = $data[12];
                $examPlan->created_at = $data[13];
                $examPlan->updated_at = $data[14];       
                $examPlan->save();
            }

            $admins=[3100, 3101, 3102, 3103, 3104, 3105, 3106 , 3107];
            foreach ($admins as $key => $value) {
                $admins_check=InfixPermissionAssign::where('module_id',$value)->where('role_id',5)->first();              
                    $permission = new InfixPermissionAssign();
                    $permission->module_id = (int)$value;
                    $permission ->module_info = InfixModuleInfo::find($value) ? InfixModuleInfo::find($value)->name : '' ;
                    $permission->role_id = 5;
    
                    if($admins_check){
                        continue;
                    }
                    $permission->save();
            }
            $infix_module_student_parent_infos = [
                [2500, 50, 0, '1', 'ExamPlan','','ExamPlan','flaticon-test',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
                [2501, 50, 2500, '2', 'Admit Card','admit/card','Admit Card','chat_box','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
                [2502, 50, 0, '1', 'ExamPlan','','ExamPlan','flaticon-test',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
                [2503, 50, 2502, '2','Admit Card','admit/card','Admit Card','chat_box','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22']
            ];
            foreach ($infix_module_student_parent_infos as $key=>$value) {                   
                $check_exit=InfixModuleStudentParentInfo::find($value[0]);
                if($check_exit){
                    continue;
                }
                $examPlan = new InfixModuleStudentParentInfo;
                $examPlan->id = $value[0];
                $examPlan->module_id = $value[1];
                $examPlan->parent_id = $value[2];
                $examPlan->type = $value[3];
                $examPlan->name = $value[4];
                $examPlan->route = $value[5];
                $examPlan->lang_name = $value[6];
                $examPlan->icon_class = $value[7];
                $examPlan->active_status = 1;
                $examPlan->created_by = 1;
                $examPlan->updated_by = 1;
                $examPlan->school_id = 1;     
                $examPlan->save();
            }
            $schools = \App\SmSchool::all();
            foreach($schools as $school){
                $schoolModule = SchoolModule::where('school_id', $school->id)->first();
                if ($school->id != 1 && $schoolModule) {
                    $plan =['ExamPlan'];               
                    if ($schoolModule->modules) {                
                        $plan =  array_merge($plan, $schoolModule->modules ?? []);
                    }
                    $schoolModule->update(['modules' =>$plan ]);
                }
            }  
        } catch (\Throwable $th) {
            Log::info($th);
        }
   
    }
        

    public function down()
    {
        Schema::dropIfExists('admit_card_settings');
    }
}
