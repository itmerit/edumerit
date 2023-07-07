<?php

namespace App\Listeners;

use App\Models\SmStaffRegistrationField;
use App\Models\SmStudentRegistrationField;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class InstituteRegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        return;
        $school = $event->institute;
        $this->staffFields($school);
        $this->StudentField($school);
    }

    public function staffFields($school){
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

        $required_fields =  ['staff_no','role', 'first_name', 'last_name', 'department', 'designation', 'email','current_address','basic_salary'];
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
        foreach ($request_fields as $key=>$value) {
            $exit = SmStaffRegistrationField::where('school_id', $school->id)->where('field_name', $value)->first();
            if (!$exit) {
                $field=new SmStaffRegistrationField;
                $field->position=$key+1;
                $field->field_name=$value;
                $field->label_name=$value;
                $field->is_required = in_array($value, $required_fields);
                $field->staff_edit = in_array($value, $staff_edit);
                $field->school_id = $school->id;
                $field->save();
            }
        }
    }

    public function StudentField($school)
    {
        $request_fields = [
            'session',
            'class',
            'section',
            'roll_number',
            'admission_number',
            'first_name',
            'last_name',
            'gender',
            'date_of_birth',
            'blood_group',
            'email_address',
            'caste',
            'phone_number',
            'id_number',
            'religion',
            'admission_date',
            'student_category_id',
            'student_group_id',
            'height',
            'weight',
            'photo',
            'fathers_name',
            'fathers_occupation',
            'fathers_phone',
            'fathers_photo',
            'mothers_name',
            'mothers_occupation',
            'mothers_phone',
            'mothers_photo',
            'guardians_name',
            'guardians_email',
            'guardians_photo',
            'guardians_phone',
            'guardians_occupation',
            'guardians_address',
            'current_address',
            'permanent_address',
            'route',
            'vehicle',
            'dormitory_name',
            'room_number',
            'national_id_number',
            'local_id_number',
            'bank_account_number',
            'bank_name',
            'previous_school_details',
            'additional_notes',
            'ifsc_code',
            'document_file_1',
            'document_file_2',
            'document_file_3',
            'document_file_4',
            'custom_field',

        ];

        if (moduleStatusCheck('Lead')) {
            $request_fields[] = 'lead_city';
            $request_fields[] = 'source_id';
        }

        $required_fields = ['session', 'class', 'first_name', 'last_name', 'gender', 'date_of_birth', 'relation', 'guardians_email', 'phone_number'];

        $student_edit = ['roll_number', 'first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number', 'email_address'];

        $parent_edit = [
            'first_name', 'last_name', 'gender', 'date_of_birth', 'phone_number', 'email_address', 'fathers_name', 'fathers_occupation', 'fathers_phone', 'fathers_photo', 'mothers_name', 'mothers_occupation', 'mothers_phone', 'mothers_photo', 'guardians_name', 'guardians_email',
            'guardians_photo', 'guardians_phone', 'guardians_occupation', 'guardians_address',
            'current_address', 'permanent_address'];

        foreach ($request_fields as $key => $value) {
            $exit = SmStudentRegistrationField::where('school_id', $school->id)->where('field_name', $value)->first();
            if (!$exit) {
                $field = new SmStudentRegistrationField;
                $field->position = $key + 1;
                $field->field_name = $value;
                $field->label_name = $value;
                $field->type = 1;
                $field->is_required = in_array($value, $required_fields);
                $field->student_edit = in_array($value, $student_edit);
                $field->parent_edit = in_array($value, $parent_edit);
                $field->school_id = $school->id;
                $field->save();
            }
        }


        if (moduleStatusCheck('ParentRegistration')) {
            $request_fields = [
                'session', 'class', 'section', 'first_name', 'last_name', 'email_address', 'gender', 'date_of_birth', 'age', 'blood_group', 'religion', 'caste', 'phone_number', 'student_category_id', 'student_group_id', 'height', 'weight', 'photo', 'fathers_name', 'fathers_occupation', 'fathers_phone', 'fathers_photo', 'mothers_name', 'mothers_occupation', 'mothers_phone', 'mothers_photo', 'guardians_name', 'relation', 'guardians_email', 'guardians_photo', 'guardians_phone', 'guardians_occupation', 'guardians_address', 'current_address', 'permanent_address', 'route', 'vehicle', 'dormitory_name', 'room_number', 'national_id_number', 'local_id_number', 'bank_account_number', 'bank_name', 'previous_school_details', 'additional_notes', 'ifsc_code', 'document_file_1', 'document_file_2', 'document_file_3', 'document_file_4', 'custom_field', 'id_number'];

            if (moduleStatusCheck('Lead')) {
                $request_fields[] = 'lead_city';
                $request_fields[] = 'source_id';
            }

            $required = ['session', 'class', 'first_name', 'last_name', 'phone_number', 'relation', 'guardians_phone'];

            $fields = [];

            foreach ($request_fields as $key => $value) {
                $fields[$key] = [
                    'position' => $key + 1,
                    'school_id' => $school->id,
                    'field_name' => $value,
                    'is_required' => in_array($value, $required) ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            DB::table('sm_student_fields')->insert($fields);
        }

    }
}
