<?php

use App\SmSchool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\SmStaffRegistrationField;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;

class CreateSmStaffRegistrationFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_staff_registration_fields', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->nullable();
            $table->string('label_name')->nullable();                
            $table->tinyInteger('active_status')->nullable()->default(1);
            $table->tinyInteger('is_required')->nullable()->default(0);
            $table->tinyInteger('staff_edit')->nullable()->default(0);           
            $table->tinyInteger('required_type')->nullable()->comment('1=switch on,2=off');
            $table->integer('position')->nullable();
            
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('set null');

            $table->timestamps();
        });

            $request_fields=[
              'staff_no',
              'role' ,             
              'department', 
              'designation', 
              'first_name',
              'last_name',
              'fathers_name',
              'mothers_name',             
              'email',
              'gender', 
              'date_of_birth',
              'date_of_joining',
              'mobile',
              'marital_status',
              'emergency_mobile',
              'driving_license',
              'current_address', 
              'permanent_address',
              'qualification',
              'experience',  
              'epf_no',               
              'basic_salary',
              'contract_type',
              'location',  
              'bank_account_name',               
              'bank_account_no',
              'bank_name',
              'bank_brach',                        
              'facebook',
              'twitter',
              'linkedin',  
              'instagram',
              'staff_photo',
              'resume',  
              'joining_letter',
              'other_document',
              'custom_fields',              
          ];
          
            $all_schools = SmSchool::get();
            foreach ($all_schools as $school) {
                foreach ($request_fields as $key=>$value) {
                    $exit = SmStaffRegistrationField::where('school_id', $school->id)->where('field_name', $value)->first();
                    if (!$exit) {
                        $field=new SmStaffRegistrationField;
                        $field->position=$key+1;
                        $field->field_name=$value;
                        $field->label_name=$value;
                        $field->school_id = $school->id;
                        $field->save();
                    }
                }
            
                $required_fields =  ['staff_no','role','first_name','email'];         
                $staff_edit =  
                [
                'first_name',
                'last_name',
                'fathers_name',
                'mothers_name',                
                'gender', 
                'date_of_birth',
                'mobile',
                'current_address', 
                'permanent_address',
                'facebook',
                'twitter',
                'linkedin',
                'instagram',
                'staff_photo',
                
            ]; 

                SmStaffRegistrationField::where('school_id', $school->id)->whereIn('field_name', $required_fields)->update(['is_required'=>1]); 
                
                SmStaffRegistrationField::where('school_id', $school->id)->whereIn('field_name', $staff_edit)->update(['staff_edit'=>1]); 

            }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_staff_registration_fields');
    }
}
